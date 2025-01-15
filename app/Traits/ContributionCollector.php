<?php

namespace App\Traits;

use App\Models\Monitor;
use App\Models\Author;
use App\Models\Contribution;
use Exception;
use Illuminate\Support\Facades\Http;
use Log;

trait ContributionCollector
{
    private $collectedContributions = [];

    public function collect_contributions(?string $rootContinueAfter = null, ?string $continueAfter = null)
    {
        try {
            $baseUrl = $this->type == 'ORGANIZATION'
                ? $_ENV['APP_SERVICE_URL'] . '/v1/github/orgs/' . $this->organization_name . '/projects/' . $this->project_identification . '/repositories/contributions'
                : $_ENV['APP_SERVICE_URL'] . '/v1/github/users/' . $this->login_name . '/projects/' . $this->project_identification . '/repositories/contributions';

            // Build URL with both pagination parameters
            $url = $baseUrl . '?rootPageSize=1&pageSize=50';
            if ($rootContinueAfter) {
                $url .= '&rootContinueAfter=' . $rootContinueAfter;
            }
            if ($continueAfter) {
                $url .= '&continueAfter=' . $continueAfter;
            }

            Log::info('Fetching contributions:', [
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
                Log::error('Failed to fetch contributions:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return [
                    'contributions' => [],
                    'next_root_cursor' => null,
                    'next_cursor' => null,
                    'has_more_repositories' => false,
                    'has_more_commits' => false,
                    'current_repository_name' => null
                ];
            }

            $responseData = $response->json();
            Log::info('Raw API Response:', [
                'response' => $responseData
            ]);

            $data = $responseData['data'] ?? null;

            if (!$data) {
                Log::error('No data in response:', [
                    'response' => $responseData
                ]);
                return [
                    'contributions' => [],
                    'next_root_cursor' => null,
                    'next_cursor' => null,
                    'has_more_repositories' => false,
                    'has_more_commits' => false,
                    'current_repository_name' => null
                ];
            }

            $repositories = $data['organization']['projectV2']['repositories']['nodes'] ?? [];
            $repoPageInfo = $data['organization']['projectV2']['repositories']['pageInfo'] ?? null;

            if (empty($repositories)) {
                return [
                    'contributions' => [],
                    'next_root_cursor' => null,
                    'next_cursor' => null,
                    'has_more_repositories' => false,
                    'has_more_commits' => false,
                    'current_repository_name' => null
                ];
            }

            $repo = $repositories[0];
            $currentRepoName = $repo['name'] ?? 'unknown';
            $contributions = [];

            if (!isset($repo['defaultBranchRef']) || !isset($repo['defaultBranchRef']['target']['history']['edges'])) {
                // If repository has no commits, move to next repository
                return [
                    'contributions' => [],
                    'next_root_cursor' => $repoPageInfo['endCursor'],
                    'next_cursor' => null,
                    'has_more_repositories' => $repoPageInfo['hasNextPage'],
                    'has_more_commits' => false,
                    'current_repository_name' => $currentRepoName
                ];
            }

            $commits = $repo['defaultBranchRef']['target']['history']['edges'];
            $commitPageInfo = $repo['defaultBranchRef']['target']['history']['pageInfo'];

            foreach ($commits as $commitData) {
                $commitNode = $commitData['node'] ?? null;
                if (!$commitNode) {
                    Log::warning('Empty commit node');
                    continue;
                }

                $authors = $commitNode['authors']['nodes'] ?? [];
                if (empty($authors)) {
                    Log::warning('No authors for commit', [
                        'commit' => $commitNode['oid'] ?? 'unknown'
                    ]);
                    continue;
                }

                try {
                    $commitHash = $commitNode['oid'] ?? null;

                    if (!$commitHash) {
                        Log::error('No commit hash (oid) found:', [
                            'commit' => $commitNode
                        ]);
                        continue;
                    }

                    Log::info('Creating contribution:', [
                        'hash' => $commitHash,
                        'headline' => $commitNode['messageHeadlineHTML'] ?? '',
                        'authors_count' => count($authors)
                    ]);

                    $contribution = Contribution::firstOrCreate(
                        ['id' => $commitHash],
                        [
                            'commit_url' => $commitNode['commitUrl'],
                            'message_headline' => strip_tags($commitNode['messageHeadlineHTML']),
                            'message_body' => strip_tags($commitNode['messageBodyHTML']),
                            'additions' => $commitNode['additions'],
                            'deletions' => $commitNode['deletions'],
                            'changed_files' => $commitNode['changedFilesIfAvailable'],
                            'committed_date' => $commitNode['committedDate']
                        ]
                    );

                    foreach ($authors as $authorData) {
                        preg_match('/\/u\/(\d+)/', $authorData['avatarUrl'], $matches);
                        $githubUserId = $matches[1] ?? null;

                        if (!$githubUserId) {
                            Log::error('Failed to extract GitHub user ID:', [
                                'avatar_url' => $authorData['avatarUrl']
                            ]);
                            continue;
                        }

                        Log::info('Creating author:', [
                            'id' => $githubUserId,
                            'name' => $authorData['name']
                        ]);

                        if ($githubUserId) {
                            $author = Author::updateOrCreate(
                                ['id' => $githubUserId],
                                [
                                    'name' => $authorData['name'],
                                    'email' => $authorData['email'],
                                    'avatar_url' => $authorData['avatarUrl']
                                ]
                            );

                            $contribution->authors()->syncWithoutDetaching([$githubUserId]);
                        }
                    }

                    $contributions[] = $contribution;
                    Log::info('Contribution created successfully', [
                        'hash' => $commitHash,
                        'total_contributions' => count($contributions)
                    ]);
                } catch (Exception $e) {
                    Log::error('Failed to create contribution:', [
                        'error' => $e->getMessage(),
                        'commit' => $commitNode
                    ]);
                    continue;
                }
            }

            // Determine if we should continue with commits or move to next repository
            $hasMoreCommits = $commitPageInfo['hasNextPage'] ?? false;
            $nextCursor = $commitPageInfo['endCursor'] ?? null;
            $hasMoreRepositories = $repoPageInfo['hasNextPage'] ?? false;

            // If no more commits in current repository, move to next repository
            if (!$hasMoreCommits) {
                $nextCursor = null;
                if ($hasMoreRepositories) {
                    $nextRootCursor = $repoPageInfo['endCursor'];
                }
            }

            return [
                'contributions' => $contributions,
                'next_root_cursor' => !$hasMoreCommits && $hasMoreRepositories ? $repoPageInfo['endCursor'] : null,
                'next_cursor' => $hasMoreCommits ? $nextCursor : null,
                'has_more_repositories' => $hasMoreRepositories,
                'has_more_commits' => $hasMoreCommits,
                'current_repository_name' => $currentRepoName
            ];

        } catch (Exception $e) {
            Log::error('Failed to collect contributions:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }
}
