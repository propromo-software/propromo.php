<?php

namespace App\Jobs;

use App\Models\Monitor;
use App\Models\Milestone;
use App\Traits\IssueCollector;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Exception;
use Log;

class CollectIssues implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IssueCollector;

    public Monitor $monitor;

    /**
     * Create a new job instance.
     *
     * @param Monitor $monitor
     */
    public function __construct(Monitor $monitor)
    {
        $this->monitor = $monitor;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $cacheKey = 'collect_issues_job_' . $this->monitor->id;

        if (Cache::has($cacheKey)) {
            Log::info("The job for monitor {$this->monitor->id} is already running.");
            return;
        }

        Cache::put($cacheKey, 'running', now()->addMinutes(10));

        try {
            $repositories = $this->monitor->repositories;

            foreach ($repositories as $repository) {
                foreach ($repository->milestones as $milestone) {
                    $this->collect_tasks($milestone);
                }
            }
        } catch (Exception $e) {
            Log::error('Error while collecting issues: ' . $e->getMessage());
        } finally {
            Cache::forget($cacheKey);
        }
    }
}
