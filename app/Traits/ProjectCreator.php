<?php

namespace App\Traits;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Traits\TokenCreator;
use Exception;

trait ProjectCreator
{
    use TokenCreator;
    /**
     * @throws Exception
     */
    public function createProject($projectUrl, $pat_token)
    {

        $projectHash = Hash::make($projectUrl, [
            'memory' => 516,
            'time' => 2,
            'threads' => 2,
        ]);

        $organisationName = Str::between($projectUrl, '/orgs/', '/projects/');
        preg_match('/\/projects\/(\d+)/', $projectUrl, $matches);
        $projectIdentification = null;

        if (count($matches) > 1) {
            $projectIdentification = intval($matches[1]);
        }

        $current_user_projects = User::find(Auth::user()->id)->projects()->get();

        if (
            $current_user_projects->where('organisation_name', '=', $organisationName)->count() > 0 &&
            $current_user_projects->where('project_identification', '=', $projectIdentification)->count() > 0
        ) {
            throw new Exception("You have already joined the monitor!");
        }

        $project = Project::create([
            "project_url" => "https://github.com/orgs/" . $organisationName . "/projects/" . $projectIdentification,
            "project_hash" => $projectHash,
            "pat_token" => $this->getApplicationToken($pat_token),
            "organisation_name" => $organisationName,
            "project_identification" => $projectIdentification,
        ]);

        $url = $_ENV['APP_SERVICE_URL'] . '/v0/github/orgs/' . $project->organisation_name . '/projects/' . $project->project_identification . '/infos';

        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'Accept' => 'text/plain',
            'Authorization' => 'Bearer '. $project->pat_token,
        ])->get($url);

        if ($response->successful()) {
            $projectData = $response->json()['data']['organization']['projectV2'];

            $project->project_url = $projectData['url'];
            $project->short_description = $projectData['shortDescription'];
            $project->title = $projectData['title'];
            $project->public = $projectData['public'];
            $project->readme = $projectData['readme'];
        }else{
            throw new Exception("Error occurred while requeting your project!");
        }

        $project->save();

        $project->users()->attach(Auth::user()->id);

        return $project;
    }
}