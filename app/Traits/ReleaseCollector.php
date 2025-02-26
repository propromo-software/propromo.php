<?php

namespace App\Traits;

use App\Models\Monitor;
use App\Models\Release;
use App\Models\Author;
use App\Models\Tag;
use Exception;
use Illuminate\Support\Facades\Http;
use Log;

trait ReleaseCollector
{
    /**
     * @throws Exception
     */
    public function collect_releases(Monitor $monitor)
    {
        $url = $monitor->type == 'ORGANIZATION'
            ? $_ENV['APP_SERVICE_URL'] . '/v1/github/orgs/' . $monitor->organization_name . '/projects/' . $monitor->project_identification . '/repositories/releases?rootPageSize=25&pageSize=5'
            : $_ENV['APP_SERVICE_URL'] . '/v1/github/users/' . $monitor->login_name . '/projects/' . $monitor->project_identification . '/repositories/releases?rootPageSize=25&pageSize=5';
        try {
            $response = Http::withHeaders([
                'content-type' => 'application/json',
                'Accept' => 'text/plain',
                'Authorization' => 'Bearer ' . $monitor->pat_token
            ])->get($url);

            Log::debug('Raw API Response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $monitor->releases()->delete();
                $processedKeys = [];

                $repositories = $response->json()['data']['organization']['projectV2']['repositories']['nodes'] ?? [];
                $totalCount = 0;

                foreach ($repositories as $repo) {
                    $releases = $repo['releases'] ?? [];
                    $totalCount += $releases['totalCount'] ?? 0;
                    $releaseNodes = $releases['nodes'] ?? [];

                    Log::debug('Processing repo:', [
                        'name' => $repo['name'],
                        'totalCount' => $releases['totalCount'] ?? 0,
                        'foundNodes' => count($releaseNodes)
                    ]);

                    foreach ($releaseNodes as $releaseData) {
                        // Create unique key based on repository and tag name
                        $key = md5($repo['name'] . '|' . ($releaseData['tagName'] ?? ''));

                        // Skip if we've already processed this combination
                        if (in_array($key, $processedKeys)) {
                            continue;
                        }

                        $processedKeys[] = $key;

                        // Find or create repository
                        $repository = $monitor->repositories()->firstOrCreate(
                            ['name' => $repo['name']],
                            ['is_custom' => false]
                        );

                        // Extract GitHub user ID from avatar URL
                        $authorAvatarUrl = $releaseData['tagCommit']['author']['avatarUrl'] ?? '';
                        $authorId = preg_replace('/[^0-9]/', '', $authorAvatarUrl);

                        // Find or create author using GitHub ID from avatar URL
                        $author = Author::firstOrCreate(
                            ['id' => $authorId],
                            [
                                'name' => $releaseData['tagCommit']['author']['name'] ?? '',
                                'email' => $releaseData['tagCommit']['author']['email'] ?? '',
                                'avatar_url' => $authorAvatarUrl
                            ]
                        );

                        $release = new Release([
                            'name' => $releaseData['name'] ?? '',
                            'description' => $releaseData['description'] ?? '',
                            'is_draft' => $releaseData['isDraft'] ?? false,
                            'is_latest' => $releaseData['isLatest'] ?? false,
                            'is_prerelease' => $releaseData['isPrerelease'] ?? false,
                            'url' => $releaseData['url'] ?? '',
                            'created_at' => $releaseData['createdAt'],
                            'updated_at' => $releaseData['updatedAt'],
                            'repository_id' => $repository->id,
                            'monitor_id' => $monitor->id
                        ]);

                        $release = $monitor->releases()->save($release);

                        // Create associated tag
                        $tag = new Tag([
                            'name' => $releaseData['tagName'] ?? '',
                            'additions' => $releaseData['tagCommit']['additions'] ?? 0,
                            'deletions' => $releaseData['tagCommit']['deletions'] ?? 0,
                            'changed_files' => $releaseData['tagCommit']['changedFilesIfAvailable'] ?? 0,
                            'authored_at' => $releaseData['tagCommit']['authoredDate'],
                            'author_id' => $author->id,
                            'release_id' => $release->id
                        ]);

                        $release->tag()->save($tag);
                    }
                }

                Log::info('Release collection completed', [
                    'total_count' => $totalCount,
                    'unique_count' => count($processedKeys),
                    'loaded_count' => $monitor->releases()->count()
                ]);

                return [
                    'releases' => $monitor->releases()->get(),
                    'total_count' => $totalCount
                ];
            } else {
                throw new Exception("Failed to fetch releases for " . $monitor->title . "!");
            }
        } catch (Exception $e) {
            Log::error('Release collection error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
