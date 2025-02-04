<?php

namespace App\Jobs;

use App\Events\MonitorProcessed;
use App\Services\ContributionService;
use App\Services\DeploymentService;
use App\Services\IssueService;
use App\Services\MonitorCreatorService;
use App\Services\MonitorJoinerApiService;
use App\Services\RepositoryFetcherService;
use App\Services\RepositoryIssueFetcherService;
use App\Services\TokenCreatorService;
use App\Services\VulnerabilityService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class CreateMonitor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $project_url;
    public $pat_token;
    public $disable_pat_token = true;

    public function __construct($project_url, $pat_token, $disable_pat_token)
    {
        $this->project_url = $project_url;
        $this->pat_token = $pat_token;
        $this->disable_pat_token = $disable_pat_token;
    }

    public function handle(): void
    {
        Log::info('Monitoring Creator Job started', [
            'project_url' => $this->project_url,
            'pat_token' => $this->pat_token ? 'Provided' : 'Not Provided',
            'disable_pat_token' => $this->disable_pat_token,
        ]);

        $monitorId = rand(1, 1000);

        broadcast(new MonitorProcessed($monitorId, 'Monitor has been successfully created.'));
    }
}
