<?php

namespace App\Jobs;

use App\Events\MonitorProcessed;
use App\Models\MonitorLogEntries;
use App\Models\MonitorLogs;
use App\Services\ContributionService;
use App\Services\DeploymentService;
use App\Services\IssueService;
use App\Services\MonitorCreatorService;
use App\Services\MonitorJoinerApiService;
use App\Services\RepositoryFetcherService;
use App\Services\RepositoryIssueFetcherService;
use App\Services\TokenCreatorService;
use App\Services\VulnerabilityService;
use App\Traits\ContributionCollector;
use App\Traits\DeploymentCollector;
use App\Traits\IssueCollector;
use App\Traits\MonitorCreator;
use App\Traits\RepositoryCollector;
use App\Traits\RepositoryIssueCollector;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class CreateMonitor implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        MonitorCreator,
        RepositoryCollector,
        DeploymentCollector,
        ContributionCollector,
        IssueCollector;

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
        try {
            $monitor = $this->create_monitor($this->project_url, $this->pat_token);
            $monitorLog = MonitorLogs::create([
                'monitor_id' => $monitor->id,
                'status' => 'started',
                'summary' => 'Initial monitor log created.',
            ]);
            MonitorLogEntries::create([
                'monitor_log_id' => $monitorLog->id,
                'message'        => 'Monitoring Creator Job initiated and monitor created successfully.',
                'level'          => 'info',
                'context'        => [
                    'project_url'       => $this->project_url,
                    'pat_token'         => $this->pat_token ? 'Provided' : 'Not Provided',
                    'disable_pat_token' => $this->disable_pat_token,
                ],
            ]);

            $repositories = $this->collect_repositories($monitor);

            /*
            foreach ($monitor->repositories as $repository) {
                foreach ($repository->milestones as $milestone) {
                    Log::info('Started fetching milestone: ' . $milestone->title);
                    $issues = $this->collect_tasks($milestone);
                }
            }

            $deployments = $this->collect_deployments($monitor);
             */
            # Todo: fix
            #$contribtions = $this->collect_contributions();

        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        broadcast(new MonitorProcessed($monitorId, 'Monitor has been successfully created.'));
    }
}
