<?php

namespace App\Traits;

use App\Models\Deployment;
use App\Models\Monitor;
use Exception;
use Illuminate\Support\Facades\Http;
use Log;

trait DeploymentCollector
{
    /**
     * @throws Exception
     */
    public function collect_deployments(Monitor $monitor)
    {
        $url = $monitor->type == 'ORGANIZATION'
            ? $_ENV['APP_SERVICE_URL'] . '/v1/github/orgs/' . $monitor->organization_name . '/projects/' . $monitor->project_identification . '/repositories/deployments?rootPageSize=10'
            : $_ENV['APP_SERVICE_URL'] . '/v1/github/users/' . $monitor->login_name . '/projects/' . $monitor->project_identification . '/repositories/deployments?rootPageSize=10';

        try {
            $response = Http::withHeaders([
                'content-type' => 'application/json',
                'Accept' => 'text/plain',
                'Authorization' => 'Bearer ' . $monitor->pat_token
            ])->get($url);

            Log::debug('Raw API Response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $monitor->deployments()->delete();

                $repositories = $response->json()['data']['organization']['projectV2']['repositories']['nodes'] ?? [];

                foreach ($repositories as $repo) {
                    $deployments = $repo['deployments']['nodes'] ?? [];

                    foreach ($deployments as $deploymentData) {
                        $latestStatus = $deploymentData['latestStatus'] ?? [];

                        $deployment = new Deployment([
                            'description' => $latestStatus['description'] ?? '',
                            'log_url' => $latestStatus['logUrl'] ?? '#',
                            'environment_url' => $latestStatus['environmentUrl'] ?? '#',
                            'state' => $latestStatus['state'] ?? 'UNKNOWN',
                            'created_at' => $deploymentData['createdAt'] ?? now(),
                            'updated_at' => $deploymentData['updatedAt'] ?? now()
                        ]);

                        $monitor->deployments()->save($deployment);
                    }
                }

                return $monitor->deployments;
            } else {
                if (app()->environment('local')) {
                    throw new Exception("Looks like you ran out of tokens for " . $monitor->title . "! " . $response->body());
                } else {
                    throw new Exception("Looks like you ran out of tokens for " . $monitor->title . "!");
                }
            }
        } catch (Exception $e) {
            Log::error('Deployment collection error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }
}
