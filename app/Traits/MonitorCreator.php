<?php

namespace App\Traits;

use App\Models\Monitor;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Log;

trait MonitorCreator
{
    use TokenCreator;
    /**
     * @throws \Exception
     */
    public function create_monitor($project_url, $pat_token)
    {
        try {
            Log::debug('Creating monitor:', [
                'project_url' => $project_url,
                'pat_token' => $pat_token
            ]);

            // https://github.com/users/Stevan06v/projects/1
            $monitor_hash = rawurlencode(base64_encode(Hash::make($project_url, [
                'memory' => 516,
                'time' => 2,
                'threads' => 2,
            ])));

            $type = null;
            $organization_name = null;
            $login_name = null;
            $project_identification = null;
            $project_url = trim($project_url);
            preg_match('/\/projects\/(\d+)/', $project_url, $matches);
            if (count($matches) > 1) {
                $project_identification = intval($matches[1]);
            } else {
                throw new Exception("Invalid project-link!");
            }

            $current_user_projects = User::find(Auth::user()->id)->monitors()->get();

            if (Str::contains($project_url,'/orgs/')){
                $organization_name = Str::between($project_url, '/orgs/', '/projects/');
                if (
                    $current_user_projects->where('organization_name', '=', $organization_name)->count() > 0 &&
                    $current_user_projects->where('project_identification', '=', $project_identification)->count() > 0
                ) {
                    throw new Exception("You have already joined the monitor!");
                }
                $type = "ORGANIZATION";
            } else{
                $login_name = strtolower(Str::between($project_url, '/users/', '/projects/'));
                if (
                    $current_user_projects->where('login_name', '=', $login_name)->count() > 0 &&
                    $current_user_projects->where('project_identification', '=', $project_identification)->count() > 0
                ) {
                    throw new \Exception("You have already joined the monitor!");
                }
                $type = "USER";
            }

            $monitor = Monitor::create([
                "project_url" => $project_url,
                "login_name"=> $login_name,
                "type" => $type,
                "monitor_hash" => $monitor_hash,
                "pat_token" => $this->get_application_token($pat_token),
                "organization_name" => $organization_name,
                "project_identification" => $project_identification,
            ]);

            $url = null;

            if($monitor->type == 'ORGANIZATION'){
                $url = $_ENV['APP_SERVICE_URL'] . '/v1/github/orgs/' . $monitor->organization_name . '/projects/' . $monitor->project_identification . '/info';
            }else{
                $url = $_ENV['APP_SERVICE_URL'] . '/v1/github/users/' . $monitor->login_name . '/projects/' . $monitor->project_identification . '/info';
            }

            $response = Http::withHeaders([
                'content-type' => 'application/json',
                'Accept' => 'text/plain',
                'Authorization' => 'Bearer ' . $monitor->pat_token,
            ])->get($url);

            Log::debug('Monitor API Response:', [
                'status' => $response->status(),
                'body' => $response->body(),
                'url' => $url
            ]);

            if ($response->successful()) {
                $monitor_data = $response->json()['data'][$monitor->type == 'ORGANIZATION' ? 'organization' : 'user']['projectV2'];
                $monitor->project_url = $monitor_data['url'];
                $monitor->short_description = $monitor_data['shortDescription'];
                $monitor->title = $monitor_data['title'];
                $monitor->public = $monitor_data['public'];
                $monitor->readme = $monitor_data['readme'];
            } else {
                throw new Exception("Error occurred while requesting your project! Status: " . $response->status());
            }

            $monitor->save();

            $monitor->users()->attach(Auth::user()->id);

            return $monitor;
        } catch (Exception $e) {
            Log::error('Monitor Creation Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
