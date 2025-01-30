<?php

namespace App\Traits;

use App\Models\Monitor;
use App\Models\Release;
use App\Models\Author;
use App\Models\Tag;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait ReleaseCollector
{
    /**
     * Collects releases for a given monitor.
     *
     * @param Monitor $monitor
     * @return array
     * @throws Exception
     */
    public function collectReleases(Monitor $monitor): array
    {
        if (empty($monitor->pat_token)) {
            throw new Exception("No personal access token (PAT) provided for monitor: {$monitor->id}");
        }

        $baseUrl = $_ENV['APP_SERVICE_URL'] . '/v1/github/';
        $monitorType = $monitor->type === 'ORGANIZATION' ? 'orgs' : 'users';
        $identifier = $monitor->type === 'ORGANIZATION' ? $monitor->organization_name : $monitor->login_name;

        $url = "{$baseUrl}{$monitorType}/{$identifier}/projects/{$monitor->project_identification}/repositories/releases?rootPageSize=25&pageSize=5";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'text/plain',
                'Authorization' => 'Bearer ' . $monitor->pat_token
            ])->get($url);

            Log::debug('GitHub API Response', [
                'status' => $response->status(),
                'monitor_id' => $monitor->id,
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                throw new Exception("Failed to fetch releases for monitor: {$monitor->id} ({$monitor->title})");
            }

            $data = $response->json();
            $repositories = $data['data']['organization']['projectV2']['repositories']['nodes'] ?? [];

            if (empty($repositories)) {
                Log::warning("No repositories found for monitor: {$monitor->id}");
                return ['releases' => [], 'total_count' => 0];
            }

            $monitor->releases()->delete();
            $processedKeys = [];
            $totalCount = 0;

            foreach ($repositories as $repo) {
                $releases = $repo['releases'] ?? [];
                $releaseNodes = $releases['nodes'] ?? [];
                $totalCount += $releases['totalCount'] ?? 0;

                Log::debug('Processing repository', [
                    'repository_name' => $repo['name'],
                    'total_releases' => $releases['totalCount'] ?? 0,
                    'found_nodes' => count($releaseNodes)
                ]);

                foreach ($releaseNodes as $releaseData) {
                    $tagName = $releaseData['tagName'] ?? '';
                    $uniqueKey = md5($repo['name'] . '|' . $tagName);

                    if (in_array($uniqueKey, $processedKeys)) {
                        continue;
                    }
                    $processedKeys[] = $uniqueKey;

                    // Find or create repository
                    $repository = $monitor->repositories()->firstOrCreate(
                        ['name' => $repo['name']],
                        ['is_custom' => false]
                    );

                    // Extract GitHub user ID from avatar URL (fallback to null)
                    $authorData = $releaseData['tagCommit']['author'] ?? [];
                    $authorAvatarUrl = $authorData['avatarUrl'] ?? '';
                    $authorId = preg_replace('/[^0-9]/', '', $authorAvatarUrl) ?: null;

                    // Find or create author
                    $author = Author::firstOrCreate(
                        ['id' => $authorId],
                        [
                            'name' => $authorData['name'] ?? 'Unknown',
                            'email' => $authorData['email'] ?? null,
                            'avatar_url' => $authorAvatarUrl
                        ]
                    );

                    // Create and save release
                    $release = $monitor->releases()->create([
                        'name' => $releaseData['name'] ?? '',
                        'description' => $releaseData['description'] ?? '',
                        'is_draft' => $releaseData['isDraft'] ?? false,
                        'is_latest' => $releaseData['isLatest'] ?? false,
                        'is_prerelease' => $releaseData['isPrerelease'] ?? false,
                        'url' => $releaseData['url'] ?? '',
                        'created_at' => $releaseData['createdAt'] ?? now(),
                        'updated_at' => $releaseData['updatedAt'] ?? now(),
                        'repository_id' => $repository->id,
                        'monitor_id' => $monitor->id
                    ]);

                    // Create and attach tag
                    $release->tag()->create([
                        'name' => $tagName,
                        'additions' => $releaseData['tagCommit']['additions'] ?? 0,
                        'deletions' => $releaseData['tagCommit']['deletions'] ?? 0,
                        'changed_files' => $releaseData['tagCommit']['changedFilesIfAvailable'] ?? 0,
                        'authored_at' => $releaseData['tagCommit']['authoredDate'] ?? now(),
                        'author_id' => $author->id
                    ]);
                }
            }

            Log::info('Release collection completed', [
                'monitor_id' => $monitor->id,
                'total_releases' => $totalCount,
                'unique_releases' => count($processedKeys),
                'stored_releases' => $monitor->releases()->count()
            ]);

            return [
                'releases' => $monitor->releases()->get(),
                'total_count' => $totalCount
            ];

        } catch (Exception $e) {
            Log::error('Release collection error', [
                'monitor_id' => $monitor->id,
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new Exception("Error collecting releases: " . $e->getMessage());
        }
    }
}
