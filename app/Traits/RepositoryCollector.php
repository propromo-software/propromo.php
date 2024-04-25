<?php

namespace App\Traits;

use App\Models\Milestone;
use App\Models\Monitor;
use App\Models\Repository;
use Exception;
use Illuminate\Support\Facades\Http;


trait RepositoryCollector
{
    /**
     * @throws Exception
     */
    public function collect_repositories(Monitor $monitor)
    {
        // milestones
        $url = $monitor->type == 'ORGANIZATION' ?  $_ENV['APP_SERVICE_URL'] . '/v1/github/orgs/' . $monitor->organization_name . '/projects/' . $monitor->project_identification . '/repositories/milestones/issues' . "?rootPageSize=10&milestonesPageSize=10&issuesPageSize=100&issues_states=open,closed"
        :  $_ENV['APP_SERVICE_URL'] . '/v1/github/users/' . $monitor->login_name . '/projects/' . $monitor->project_identification . '/repositories/milestones/issues' . "?rootPageSize=10&milestonesPageSize=10&issuesPageSize=100&issues_states=open,closed";

        try {
            $response = Http::withHeaders([
                'content-type' => 'application/json',
                'Accept' => 'text/plain',
                'Authorization' => 'Bearer ' . $monitor->pat_token
            ])->get($url);
        } catch (Exception $e) {
            throw new Exception("Seems like you have no internet connection!");
        }

        // delete existing milestones
        if ($response->successful()) {

            $repositories = $response->json()['data'][$monitor->type == 'ORGANIZATION' ? 'organization' : 'user']['projectV2']['repositories']['nodes'];
            $monitor->repositories()->delete();

            foreach ($repositories as $repositoryData) {

                $repository = new Repository();

                $repository->name = $repositoryData["name"];

                $get_repository = $monitor->repositories()->save($repository); // Save the repository

                $milestones = $repositoryData["milestones"]["nodes"];
                if (count($milestones) > 0) {
                    foreach ($milestones as $milestoneData) {
                        if (count($milestoneData) > 0) {
                            $milestone = new Milestone([
                                'title' => $milestoneData['title'],
                                'url' => $milestoneData['url'],
                                'state' => $milestoneData['state'],
                                'due_on' => ($timestamp = strtotime($milestoneData['dueOn'])) !== false ? date('Y-m-d H:i:s', $timestamp) : null,
                                'description' => $milestoneData['description'],
                                'milestone_id' => $milestoneData['number'],
                                'progress' => $milestoneData['progressPercentage'],
                                'open_issues_count' => intval($milestoneData['open_issues']['totalCount']),
                                'closed_issues_count' => intval($milestoneData['closed_issues']['totalCount']),
                                'repository_id' => $get_repository->id
                            ]);
                            $repository->milestones()->save($milestone);
                        }
                    }
                }
            }
            return Repository::where("monitor_id", "=", $monitor->id)->get();
        } else {
            throw new Exception("Looks like you ran out of tokens for " . $monitor->title . "! " . $response->body() );
        }
    }
}
