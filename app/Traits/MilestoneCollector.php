<?php

namespace App\Traits;

use App\Models\Milestone;
use Exception;
use Illuminate\Support\Facades\Http;

trait MilestoneCollector
{
    /**
     * @throws Exception
     */
    public function collectMilestones($project)
    {
        $url = $_ENV['APP_SERVICE_URL'] . '/github/orgs/' . $project->oragnisation_name . '/projects/' . $project->project_identification . '/repositories/scoped?scope=' . 'milestones';

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


                    /*
                     * "createdAt": "2024-02-05T14:15:44Z",
                    "closedAt": null,
                    "description": "Integration of ProjectsV2:\r\nhttps://docs.github.com/en/graphql/overview/about-the-graphql-api\r\n\r\nGraphQL middleware at https://propromo-rest.duckdns.org.\r\nReason: Projects is deprecated. ProjectsV2 is only available using GraphQL.",
                    "dueOn": null,
                    "progressPercentage": 60,
                    "title": "Github Adapter",
                    "updatedAt": "2024-03-08T18:48:52Z",
                    "url": "https://github.com/propromo-software/propromo.rest/milestone/1",
                    "state": "OPEN"
                     *
                     * */

                    $milestone->progress = $milestoneData['progressPercentage'];
                    $milestone->description = $milestoneData['description'];
                    $milestone->title = $milestoneData['title'];
                    $milestone->url = $milestoneData['url'];
                    $milestone->state = $milestoneData['state'];
                    $milestone->closed_issues_count = $milestoneData['open_issues']['totalCount'];
                    $milestone->open_issues_count = $milestoneData['closed_issues']['totalCount'];

                    // save the milestone to the db
                    $project->milestones()->save($milestone);
                }
            }
        }


    }

}
