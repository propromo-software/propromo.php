<?php

namespace App\Traits;

use App\Models\Label;
use App\Models\Milestone;
use App\Models\MonitorLogEntries;
use App\Models\Task;
use Exception;
use Illuminate\Support\Facades\Http;

trait IssueCollector
{
    /**
     * @throws Exception
     */
    public function collect_tasks(Milestone $milestone)
    {
        $repository = $milestone->repository;
        $monitor = $repository->monitor;

        $url = $monitor->type == 'ORGANIZATION'
            ? $_ENV['APP_SERVICE_URL'] . '/v1/github/orgs/' . $monitor->organization_name . '/projects/' . $monitor->project_identification . '/repositories/milestones/' . $milestone->milestone_id . '/issues?rootPageSize=10&issuesPageSize=100'
            : $_ENV['APP_SERVICE_URL'] . '/v1/github/users/' . $monitor->login_name . '/projects/' . $monitor->project_identification . '/repositories/milestones/' . $milestone->milestone_id . '/issues?rootPageSize=10&issuesPageSize=100';
        try {
            $response = Http::withHeaders([
                'content-type' => 'application/json',
                'Accept' => 'text/plain',
                'Authorization' => 'Bearer ' . $monitor->pat_token
            ])->get($url);
        } catch (Exception $e) {
            throw new Exception("Seems like you have no internet connection!");
        }

        if ($response->successful()) {
            $milestone->tasks()->delete();

            $repositories = $response->json()['data'][$monitor->type == 'ORGANIZATION' ? 'organization' : 'user']['projectV2']['repositories']['nodes'];

            foreach ($repositories as $repoData) {
                if ($repoData['name'] == $repository->name) {
                    try {
                        $milestoneData = $repoData['milestone'];
                        if ($milestoneData) {

                            foreach ($milestoneData['open_issues']['nodes'] as $issueData) {
                                $this->save_task($issueData, $milestone);
                            }
                            foreach ($milestoneData['closed_issues']['nodes'] as $issueData) {
                                $this->save_task($issueData, $milestone);
                            }
                        }
                        break;
                    }catch (Exception $e) {
                        MonitorLogEntries::create([
                            'monitor_log_id' => $monitor->monitor_logs()->first()->id,
                            'message' => 'Looks like no milestone defined in your gh-project!' . 'Check out for https://propromo-docs.vercel.app/blog/how-to-use-propromos-github-scrum-template-project.mdx for further assistance.',
                            'level' => 'error',
                            'context' => [
                            ],
                        ]);
                    }
                }
            }

            return $milestone->tasks;
        } else {
            throw new Exception("Looks like you ran out of tokens for " . $monitor->title . "! " . $response->body());
        }
    }

    /**
     * Save a single issue as a task to the milestone
     * @param array $issueData
     * @param Milestone $milestone
     */
    private function save_task(array $issueData, Milestone $milestone)
    {
        $task = new Task([
            'title' => $issueData['title'] ?? '',
            'url' => $issueData['url'] ?? '',
            'body_url' => $issueData['bodyUrl'] ?? '',
            'created_at' => isset($issueData['createdAt']) ? date('Y-m-d H:i:s', strtotime($issueData['createdAt'])) : null,
            'updated_at' => isset($issueData['updatedAt']) ? date('Y-m-d H:i:s', strtotime($issueData['updatedAt'])) : null,
            'closed_at' => isset($issueData['closedAt']) ? date('Y-m-d H:i:s', strtotime($issueData['closedAt'])) : null,
            'description' => $issueData['body'] ?? '',
            'milestone_id' => $milestone->id
        ]);
        $task->save();

        if (isset($issueData['labels']['nodes'])) {
            foreach ($issueData['labels']['nodes'] as $labelData) {
                $label = new Label([
                    'name' => $labelData['name'] ?? '',
                    'color' => $labelData['color'] ?? '',
                    'description' => $labelData['description'] ?? '',
                    'task_id' => $task->id
                ]);
                $label->save();
            }
        }
    }
}
