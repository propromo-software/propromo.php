<?php

namespace App\Traits;

use App\Models\Monitor;
use App\Models\Author;
use App\Models\Contribution;
use Exception;
use Illuminate\Support\Facades\Http;

trait ContributionCollector
{
    /**
     * @throws Exception
     */
    public function collect_contributions(Monitor $monitor)
    {
        $url = $monitor->type == 'ORGANIZATION'
            ? $_ENV['APP_SERVICE_URL'] . '/v1/github/orgs/' . $monitor->organization_name . '/projects/' . $monitor->project_identification . '/repositories/contributions?rootPageSize=25&pageSize=50'
            : $_ENV['APP_SERVICE_URL'] . '/v1/github/users/' . $monitor->login_name . '/projects/' . $monitor->project_identification . '/repositories/contributions?rootPageSize=25&pageSize=50';

        \Log::info('Fetching contributions from URL:', ['url' => $url]);

        try {
            $response = Http::withHeaders([
                'content-type' => 'application/json',
                'Accept' => 'text/plain',
                'Authorization' => 'Bearer ' . $monitor->pat_token
            ])->get($url);

            \Log::debug('API Response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $repositories = $response->json()['data']['organization']['projectV2']['repositories']['nodes'] ?? [];
                $contributions = [];

                foreach ($repositories as $repo) {
                    $commits = $repo['defaultBranchRef']['target']['history']['edges'] ?? [];

                    foreach ($commits as $commitData) {
                        $commitNode = $commitData['node'] ?? null;
                        if ($commitNode) {
                            $authors = $commitNode['authors']['nodes'] ?? [];

                            \Log::debug('Authors for commit:', [
                                'commit_url' => $commitNode['commitUrl'],
                                'authors' => $authors
                            ]);

                            $contribution = Contribution::create([
                                'commit_url' => $commitNode['commitUrl'],
                                'message_headline' => strip_tags($commitNode['messageHeadlineHTML']),
                                'message_body' => strip_tags($commitNode['messageBodyHTML']),
                                'additions' => $commitNode['additions'],
                                'deletions' => $commitNode['deletions'],
                                'changed_files' => $commitNode['changedFilesIfAvailable'],
                                'committed_date' => $commitNode['committedDate'],
                                'author_id' => $authors ? $this->getOrCreateAuthor($authors[0]) : null,
                            ]);

                            $contribution->authors = $authors;
                            $contributions[] = $contribution;
                        }
                    }
                }

                return $contributions;
            } else {
                \Log::error('Error fetching contributions:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new Exception("Fehler beim Abrufen der Beiträge: " . $response->body());
            }
        } catch (Exception $e) {
            \Log::error('Exception in collect_contributions:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function getOrCreateAuthor(array $authorData)
    {
        return Author::firstOrCreate(
            ['email' => $authorData['email']],
            [
                'name' => $authorData['name'],
                'avatar_url' => $authorData['avatarUrl'],
            ]
        )->id;
    }
}
