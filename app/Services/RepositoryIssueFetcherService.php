<?php

namespace App\Services;

use App\Models\Repository;
use App\Models\Task;
use Exception;
use Illuminate\Support\Facades\Http;
use Log;

/**
 * Class RepositoryIssueFetcherService.
 */
class RepositoryIssueFetcherService
{
    /**
     * @throws Exception
     */
    public function collect_repository_issues($monitor)
    {
        $url = $_ENV['APP_SERVICE_URL'] . '/v1/github/'
            . ($monitor->type === 'ORGANIZATION' ? 'orgs/' . $monitor->organization_name : 'users/' . $monitor->login_name)
            . '/projects/' . $monitor->project_identification
            . '/repositories/issues?rootPageSize=10&issuesPageSize=10';

        try {
            $response = Http::withHeaders([
                'content-type' => 'application/json',
                'Accept' => 'text/plain',
                'Authorization' => 'Bearer ' . $monitor->pat_token
            ])->get($url);
        } catch (Exception $e) {
            throw new Exception("It seems like you have no internet connection!");
        }

        if ($response->successful()) {

            $repositories = $response->json()['data'][$monitor->type === 'ORGANIZATION' ? 'organization' : 'user']['projectV2']['repositories']['nodes'];
            #$monitor->repositories->delete();

            foreach ($repositories as $repoData) {

                Log::info("Processing repository: " . ($repoData['name'] ?? 'Unknown'));

                $repository = Repository::create([
                    'name' => $repoData['name'],
                    'description' => $repoData['description'] ?? '',
                    'monitor_id' => $monitor->id,
                    'is_custom' => true,
                    'custom_repository_id' => $this->generate_unique_key(),
                ]);

                $repository->save();

                Log::info("Repository created with ID: " . $repository->id . " and Custom ID: " . $repository->custom_repository_id);

                #Task::where('custom_repository_id', $repository->custom_repository_id)->delete();

                $openIssues = $repoData['closed_issues']['nodes'] ?? [];
                foreach ($openIssues as $issueData) {
                    if (isset($issueData['title'])) {
                        $task = Task::create([
                            'title' => $issueData['title'],
                            'custom_repository_id' => $repository->custom_repository_id,
                        ]);
                        $task->save();
                        \Log::info('Task saved with title: ' . $issueData['title']);
                    } else {
                        \Log::warning('Issue data missing title:', $issueData);
                    }
                }

                /*
                $closedIssues = $repoData['closed_issues']['nodes'] ?? [];
                foreach ($closedIssues as $issueData) {
                    $task= Task::create([
                        'title' => $issueData['title'] ?? '',
                        'url' => $issueData['url'] ?? '',
                        'body_url' => $issueData['bodyUrl'] ?? '',
                        'closed_at' => false ? date('Y-m-d H:i:s', strtotime($issueData['closedAt'] ?? now())) : null,
                        'last_edited_at' => isset($issueData['lastEditedAt']) ? date('Y-m-d H:i:s', strtotime($issueData['lastEditedAt'])) : null,
                        'body' => $issueData['body'] ?? '',
                        'custom_repository_id' => $repository->custom_repository_id,
                        'is_active' => true,
                    ]);
                    $task->save();
                }
                 */
            }

            return true;
        } else {
            throw new Exception("It seems you've run out of tokens for " . $monitor->title . "! " . $response->body());
        }
    }

    /**
     * Save a single issue as a task.
     *
     * @param array $issueData
     * @param Repository $repository
     * @param bool $isClosed
     */
    private function save_task(array $issueData, Repository $repository, bool $isClosed)
    {
        Task::create([
            'title' => $issueData['title'] ?? '',
            'url' => $issueData['url'] ?? '',
            'body_url' => $issueData['bodyUrl'] ?? '',
            'closed_at' => $isClosed ? date('Y-m-d H:i:s', strtotime($issueData['closedAt'] ?? now())) : null,
            'last_edited_at' => isset($issueData['lastEditedAt']) ? date('Y-m-d H:i:s', strtotime($issueData['lastEditedAt'])) : null,
            'body' => $issueData['body'] ?? '',
            'custom_repository_id' => $repository->custom_repository_id,
            'is_active' => !$isClosed,
        ]);
    }

    /**
     * Create a repository.
     *
     * @param array $repoData
     * @param $monitor
     * @return Repository
     */
    private function create_repository(array $repoData, $monitor)
    {
        return Repository::create([
            'name' => $repoData['name'],
            'description' => $repoData['description'] ?? '',
            'monitor_id' => $monitor->id,
            'is_custom' => true,
            'custom_repository_id' => $this->generate_unique_key(),
        ]);
    }

    /**
     * Generate a unique custom repository ID.
     *
     * @return int
     */
    private function generate_unique_key()
    {
        do {
            $key = random_int(100000, 999999);
        } while (Repository::where('custom_repository_id', $key)->exists());

        return $key;
    }
}
