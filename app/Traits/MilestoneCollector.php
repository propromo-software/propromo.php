<?php

namespace App\Traits;

use App\Models\Milestone;
use App\Models\Project;
use Exception;
use Illuminate\Support\Facades\Http;


trait MilestoneCollector
{
    /**
     * @throws Exception
     */
    public function collectMilestones(Project $project)
    {
        $url = $_ENV['APP_SERVICE_URL'] . '/v1/github/orgs/' . $project->organisation_name . '/projects/' . $project->project_identification . '/repositories/scoped?scope=' . 'milestones';

        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'Accept' => 'text/plain',
            'Authorization' => 'Bearer ' . $project->pat_token
        ])->get($url);

        // delete existing milestones
        $project->milestones()->delete();

        if ($response->successful()) {

            $repositories = $response->json()['data']['organization']['projectV2']['repositories']['nodes'];

            // save milestone-data into db
            foreach ($repositories as $repositoryData) {

                $milestones = $repositoryData["milestones"]["nodes"];

                foreach ($milestones as $milestoneData) {
                    $milestone = new Milestone();

                    $milestone->progress = $milestoneData['progressPercentage'];
                    $milestone->description = $milestoneData['description'];
                    $milestone->title = $milestoneData['title'];
                    $milestone->url = $milestoneData['url'];
                    $milestone->state = $milestoneData['state'];

                    // save the milestone to the db
                    $project->milestones()->save($milestone);
                }
            }
            return $project;
        }
    }
}
