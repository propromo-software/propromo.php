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
            ? $_ENV['APP_SERVICE_URL'] . '/v1/github/orgs/' . $monitor->organization_name . '/projects/' . $monitor->project_identification . '/repositories/contributions?rootPageSize=10'
            : $_ENV['APP_SERVICE_URL'] . '/v1/github/users/' . $monitor->login_name . '/projects/' . $monitor->project_identification . '/repositories/contributions?rootPageSize=10';

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

                foreach ($repositories as $repo) {
                    $commits = $repo['defaultBranchRef']['target']['history']['edges'] ?? [];

                    foreach ($commits as $commitData) {
                        $commitNode = $commitData['node'];
                        $authors = $commitNode['authors']['nodes'] ?? [];

                        foreach ($authors as $authorData) {
                            $author = Author::updateOrCreate(
                                ['email' => $authorData['email']],
                                [
                                    'name' => $authorData['name'],
                                    'avatar_url' => $authorData['avatarUrl'],
                                ]
                            );

                            Contribution::create([
                                'commit_url' => $commitNode['commitUrl'],
                                'message_headline' => strip_tags($commitNode['messageHeadlineHTML']),
                                'message_body' => strip_tags($commitNode['messageBodyHTML']),
                                'additions' => $commitNode['additions'],
                                'deletions' => $commitNode['deletions'],
                                'changed_files' => $commitNode['changedFilesIfAvailable'],
                                'committed_date' => $commitNode['committedDate'],
                                'author_id' => $author->id,
                            ]);
                        }
                    }
                }

                return Contribution::all();
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
}
