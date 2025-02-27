<?php

namespace App\Jobs;

use App\Events\MonitorProcessed;
use App\Models\Monitor;
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

    public $monitor;
    protected $logId;

    public function __construct($monitor)
    {
        $this->monitor = $monitor;
    }

    public function handle(): void
    {
        try {
            $monitorLog = MonitorLogs::where('monitor_id', $this->monitor->id)
                ->latest()
                ->first();

            if (!$monitorLog) {
                Log::warning("No monitor log found for monitor ID: {$this->monitor->id}");
                return;
            }

            $this->logId = $monitorLog->id;

            $this->log("Starting monitor process...", "info");

            $repositories = $this->collect_repositories($this->monitor);
            $this->log("Found " . $repositories->count() . " repositories.", "info");

            foreach ($repositories as $repository) {
                Log::info("REPOSITORY {$repository->id} created.", "info");

                $this->log("Processing repository: {$repository->name}", "info");

                foreach ($repository->milestones as $milestone) {
                    $issues = $this->collect_tasks($milestone);
                    $this->log("Milestone {$milestone->title}: Found " .$issues->count() . " issues.", "info");
                }
            }

            // Log successful completion
            $this->log("Monitor process completed successfully.", "success");

        } catch (Exception $e) {
            Log::error($e->getMessage());
            $this->log("Error: " . $e->getMessage(), "error");
        }

        // Broadcast event after processing
        // broadcast(new MonitorProcessed($this->monitor->id, 'Monitor has been successfully created.'));
    }

    protected function log($message, $level = "info")
    {
        MonitorLogEntries::create([
            'monitor_log_id' => $this->logId,
            'message' => $message,
            'level' => $level,
        ]);
    }
}
