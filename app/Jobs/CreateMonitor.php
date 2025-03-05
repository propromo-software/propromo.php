<?php

namespace App\Jobs;

use App\Models\Monitor;
use App\Traits\ContributionCollector;
use App\Traits\DeploymentCollector;
use App\Traits\IssueCollector;
use App\Traits\MonitorCreator;
use App\Traits\ReleaseCollector;
use App\Traits\RepositoryCollector;
use App\Traits\VulnerabilityCollector;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Log;
use Exception;

class CreateMonitor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MonitorCreator, RepositoryCollector, DeploymentCollector, ContributionCollector, DeploymentCollector, VulnerabilityCollector, ReleaseCollector, IssueCollector;

    public Monitor $monitor;

    public function __construct($monitor)
    {
        $this->monitor = $monitor;
    }

    public function handle(): void
    {
        $cacheKey = 'monitor_processing_' . $this->monitor->id;

        if (Cache::has($cacheKey)) {
            Log::info("Monitor job for monitor ID {$this->monitor->id} is already running.");
            return;
        }

        try {
            Cache::put($cacheKey, true, now()->addMinutes(5));

            $repositories = $this->collect_repositories($this->monitor);

            foreach ($repositories as $repository) {
                foreach ($repository->milestones as $milestone) {
                    $this->collect_tasks($milestone);
                }
            }

            $this->collect_releases($this->monitor);
            $this->collect_deployments($this->monitor);
            $this->collect_vulnerabilities($this->monitor);
        } catch (Exception $e) {
            Log::error("Error processing monitor: " . $e->getMessage());
        } finally {
            Cache::forget($cacheKey);
        }
    }
}
