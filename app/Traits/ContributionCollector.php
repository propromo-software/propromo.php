<?php

namespace App\Traits;

use App\Models\Author;
use App\Models\Contribution;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait ContributionCollector
{

    public function collect_contributions(?string $rootContinueAfter = null, ?string $continueAfter = null)
    {
        try {
            $baseUrl = $this->type == 'ORGANIZATION'
                ? $_ENV['APP_SERVICE_URL'] . '/v1/github/orgs/' . $this->organization_name . '/projects/' . $this->project_identification . '/repositories/contributions'
                : $_ENV['APP_SERVICE_URL'] . '/v1/github/users/' . $this->login_name . '/projects/' . $this->project_identification . '/repositories/contributions';

            $url = $baseUrl . '?rootPageSize=10&pageSize=50';
            if ($rootContinueAfter) {
                $url .= '&rootContinueAfter=' . $rootContinueAfter;
            }
            if ($continueAfter) {
                $url .= '&continueAfter=' . $continueAfter;
            }

            Log::info('Fetching contributions', [
                'url' => $url,
                'rootContinueAfter' => $rootContinueAfter,
                'continueAfter' => $continueAfter
            ]);

            $response = Http::timeout(15)->withHeaders([
                'content-type' => 'application/json',
                'Accept' => 'text/plain',
                'Authorization' => 'Bearer ' . $this->pat_token
            ])->get($url);

            if (!$response->successful()) {
                Log::error('Failed to fetch contributions', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return [];
            }

            $responseData = $response->json()['data'] ?? [];
            $repositories = $responseData['organization']['projectV2']['repositories']['nodes'] ?? [];

            $contributions = [];
            $hasMoreCommits = false;
            $hasMoreRepositories = false;
            $nextRootCursor = null;
            $nextCursor = null;
            $currentRepositoryName = null;

            foreach ($repositories as $repo) {
                if (!isset($repo['defaultBranchRef']['target']['history']['edges'])) {
                    continue;
                }

                $currentRepositoryName = $repo['name'] ?? null;

                $commits = $repo['defaultBranchRef']['target']['history']['edges'];
                foreach ($commits as $commitData) {
                    $commitNode = $commitData['node'] ?? null;
                    if (!$commitNode) {
                        continue;
                    }

                    $authors = $commitNode['authors']['nodes'] ?? [];
                    if (empty($authors)) {
                        continue;
                    }

                    $commitHash = $commitNode['oid'] ?? null;
                    if (!$commitHash) {
                        continue;
                    }

                    $primaryAuthor = $authors[0]; // Use the first author as primary

                    $author = Author::updateOrCreate(
                        ['id' => preg_replace('/[^0-9]/', '', $primaryAuthor['avatarUrl'])], // Extract numeric ID from avatar URL
                        [
                            'name' => $primaryAuthor['name'] ?? null,
                            'email' => $primaryAuthor['email'] ?? null,
                            'avatar_url' => $primaryAuthor['avatarUrl'] ?? null
                        ]
                    );

                    $contribution = Contribution::updateOrCreate(
                        ['id' => $commitHash],
                        [
                            'author_id' => $author->id,
                            'commit_url' => $commitNode['commitUrl'],
                            'message_headline' => strip_tags($commitNode['messageHeadlineHTML']),
                            'message_body' => strip_tags($commitNode['messageBodyHTML']),
                            'additions' => $commitNode['additions'],
                            'deletions' => $commitNode['deletions'],
                            'changed_files' => $commitNode['changedFilesIfAvailable'],
                            'committed_date' => $commitNode['committedDate']
                        ]
                    );

                    $contributions[] = $contribution->toArray();
                }

                $hasMoreCommits = $repo['defaultBranchRef']['target']['history']['pageInfo']['hasNextPage'] ?? false;
                $nextCursor = $repo['defaultBranchRef']['target']['history']['pageInfo']['endCursor'] ?? null;
            }

            // Adjust these based on actual API response structure
            $hasMoreRepositories = count($repositories) > 1;
            $nextRootCursor = $responseData['organization']['projectV2']['repositories']['pageInfo']['endCursor'] ?? null;

            Log::info('Contributions successfully collected.');

            return [
                'contributions' => $contributions,
                'has_more_commits' => $hasMoreCommits,
                'has_more_repositories' => $hasMoreRepositories,
                'next_root_cursor' => $nextRootCursor,
                'next_cursor' => $nextCursor,
                'current_repository_name' => $currentRepositoryName
            ];

        } catch (Exception $e) {
            Log::error('Error collecting contributions', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [];
        }
    }

}
