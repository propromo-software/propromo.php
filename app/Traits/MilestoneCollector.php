<?php

namespace App\Traits;

use App\Models\Milestone;
use App\Models\Project;
use App\Models\Repository;
use Exception;
use Illuminate\Support\Facades\Http;


trait MilestoneCollector
{
    /**
     * @throws Exception
     */
    public function collectMilestones(Repository $repository)
    {

        $url = $_ENV['APP_SERVICE_URL'] . '/v1/github/orgs/' . $repository->project()->first()->organisation_name . '/projects/' . $repository->project()->first()->project_identification . '/repositories/scoped?scope=' . 'milestones';

        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'Accept' => 'text/plain',
            'Authorization' => 'Bearer ' . $repository->project()->first()->pat_token
        ])->get($url);

        // delete existing milestones

        if ($response->successful()) {

            $repositories = $response->json()['data']['organization']['projectV2']['repositories']['nodes'];

            $repository->delete();

        }
        throw new Exception("Error occured while fetching tokens!");
    }
}
