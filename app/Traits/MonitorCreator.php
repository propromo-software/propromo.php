<?php

namespace App\Traits;

use App\Models\Monitor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Traits\TokenCreator;
use Exception;

trait MonitorCreator
{
    use TokenCreator;
    /**
     * @throws Exception
     */
    public function create_monitor($project_url, $pat_token)
    {

        $monitor_hash = Hash::make($project_url, [
            'memory' => 516,
            'time' => 2,
            'threads' => 2,
        ]);

        $organization_name = Str::between($project_url, '/orgs/', '/projects/');
        preg_match('/\/projects\/(\d+)/', $project_url, $matches);
        $project_identification = null;

        if (count($matches) > 1) {
            $project_identification = intval($matches[1]);
        }

        $current_user_projects = User::find(Auth::user()->id)->monitors()->get();

        if (
            $current_user_projects->where('organization_name', '=', $organization_name)->count() > 0 &&
            $current_user_projects->where('project_identification', '=', $project_identification)->count() > 0
        ) {
            throw new Exception("You have already joined the monitor!");
        }

        $monitor = Monitor::create([
            "project_url" => "https://github.com/orgs/" . $organization_name . "/projects/" . $project_identification,
            "monitor_hash" => $monitor_hash,
            "pat_token" => $this->get_application_token($pat_token),
            "organization_name" => $organization_name,
            "project_identification" => $project_identification,
        ]);

        $url = $_ENV['APP_SERVICE_URL'] . '/v1/github/orgs/' . $monitor->organization_name . '/projects/' . $monitor->project_identification . '/info';

        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'Accept' => 'text/plain',
            'Authorization' => 'Bearer ' . $monitor->pat_token,
        ])->get($url);

        if ($response->successful()) {
            $monitor_data = $response->json()['data']['organization']['projectV2'];
            $monitor->project_url = $monitor_data['url'];
            $monitor->short_description = $monitor_data['shortDescription'];
            $monitor->title = $monitor_data['title'];
            $monitor->public = $monitor_data['public'];
            $monitor->readme = $monitor_data['readme'];
        } else {
            throw new Exception("Error occurred while requesting your project!");
        }

        $monitor->save();

        $monitor->users()->attach(Auth::user()->id);

        return $monitor;
    }
}
