<?php

namespace App\Traits;

use App\Models\Milestone;
use App\Models\Project;
use App\Models\Repository;
use Exception;
use Illuminate\Support\Facades\Http;


trait RespositoryCollector
{
    /**
     * @throws Exception
     */
    public function collectRepositories(Project $project)
    {
        // milestones
        $url = $_ENV['APP_SERVICE_URL'] . '/v1/github/orgs/' . $project->organisation_name . '/projects/' . $project->project_identification . '/repositories/scoped?scope=' . 'issues';

        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'Accept' => 'text/plain',
            'Authorization' => 'Bearer ' . $project->pat_token
        ])->get($url);

        // delete existing milestones
        if ($response->successful()) {

            $repositories = $response->json()['data']['organization']['projectV2']['repositories']['nodes'];

            $project->repositories()->delete();

            foreach ($repositories as $repositoryData) {
                $repository = new Repository();

                $repository->name = $repositoryData["name"];

                $get_repository = $project->repositories()->save($repository); // Save the repository

                $milestones = $repositoryData["milestones"]["nodes"];

                foreach ($milestones as $milestoneData) {
                    if (count($milestoneData) > 0) {
                        $milestone = new Milestone([
                            'title' => $milestoneData['title'],
                            'url' => $milestoneData['url'],
                            'state' => $milestoneData['state'],
                            'description' => $milestoneData['description'],
                            'progress' => $milestoneData['progressPercentage'],
                            'open_issues_count' => intval($milestoneData['open_issues']['totalCount']),
                            'closed_issues_count' => intval($milestoneData['closed_issues']['totalCount']),
                            'repository_id' => $get_repository->id
                        ]);

                        $repository->milestones()->save($milestone);
                    }
                }
            }
            return Repository::where("project_id", "=", $project->id)->get();
        }
        throw new Exception("Error while fetching repositories!");
    }
}
