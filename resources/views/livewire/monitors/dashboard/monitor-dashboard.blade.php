<?php

use App\Models\Monitor;
use App\Models\Repository;
use Livewire\Volt\Component;
use \App\Traits\IssueCollector;


new class extends Component {

    use IssueCollector;

    public $total_issues_open = 0;
    public $total_issues_closed = 0;
    public $total_issues= 0;
    public $total_repos = 0;
    public $total_percentage = 0;
    public $top_milestones = [];
    public Monitor $monitor;
    public $dataFetched = false;


    public function mount(Monitor $monitor)
    {
        $this->monitor = $monitor;
        $this->calculate_statistics();
    }

    public function reload_issues()
    {
        try {
            if (!$this->dataFetched) {
                foreach ($this->monitor->repositories as $repository) {
                    foreach ($repository->milestones as $milestone) {
                        $this->collect_tasks($milestone);
                    }
                }
                $this->dataFetched = true;
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    protected function calculate_statistics(): void
    {
        $allMilestonesEmpty = true;

        foreach ($this->monitor->repositories as $repository) {
            foreach ($repository->milestones as $milestone) {
                if (!$milestone->tasks()->get()->isEmpty()) {
                    $allMilestonesEmpty = false;
                    break 2;
                }
            }
        }

        if ($allMilestonesEmpty) {
            $this->reload_issues();
        }


        $this->total_repos = $this->monitor->repositories()->count();

        $this->total_issues_open = $this->monitor->repositories->flatMap(function ($repo) {
            return $repo->milestones->flatMap(function ($milestone) {
                return $milestone->tasks->whereNull('closed_at');
            });
        })->count();

        $this->total_issues_closed = $this->monitor->repositories->flatMap(function ($repo) {
            return $repo->milestones->flatMap(function ($milestone) {
                return $milestone->tasks->whereNotNull('closed_at');
            });
        })->count();

        $this->total_issues = $this->total_issues_open + $this->total_issues_closed;

        $this->top_milestones = $this->monitor->repositories->flatMap(function ($repo) {
            return $repo->milestones;
        })->sortByDesc('progress')->take(5);

        $totalMilestones = $this->monitor->repositories->flatMap(function ($repo) {
            return $repo->milestones;
        })->count();

        if ($totalMilestones > 0) {
            $totalProgress = $this->monitor->repositories->flatMap(function ($repo) {
                return $repo->milestones->pluck('progress');
            })->sum();

            $this->total_percentage = round($totalProgress / $totalMilestones, 2);
        } else {
            $this->total_percentage = 0;
        }

        if ($this->total_issues > 0) {
            $this->total_percentage = round(($this->total_issues_closed / $this->total_issues) * 100, 2);
        } else {
            $this->total_percentage = 0;
        }
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <h1>Loading...</h1>
            <!-- Loading spinner... -->
            <svg>...</svg>
        </div>
        HTML;
    }
}; ?>


<div>
    <h2 class="m-2 font-koulen text-2xl text-primary-blue">Overview</h2>
    <div class="flex items-center justify-between mb-5">
        <div class="m-2">
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <sl-badge class="mb-1" variant="neutral">Open Issues</sl-badge>
                    <div class="text-white bg-additional-orange border-2 rounded-md p-2 flex justify-between gap-2 items-center" style="min-width: 150px; max-width: 300px; width: 100%;">
                        <sl-icon wire:ignore name="calendar2-week" class="text-white font-sourceSansPro text-xl font-bold"></sl-icon>
                        <div class="text-white text-xl font-sourceSansPro font-bold">{{$total_issues_open}}</div>
                    </div>
                </div>

                <div>
                    <sl-badge class="mb-1" variant="neutral">Closed Issues</sl-badge>
                    <div class="border-additional-green bg-additional-green border-2 rounded-md p-2 flex justify-between gap-2 items-center" style="min-width: 150px; max-width: 300px; width: 100%;">
                        <sl-icon wire:ignore name="calendar2-x" class="text-white font-sourceSansPro text-xl font-bold"></sl-icon>
                        <div class="text-white text-xl font-sourceSansPro font-bold">{{$total_issues_closed}}</div>
                    </div>
                </div>

                <div>
                    <sl-badge class="mb-1" variant="neutral">Total Repos</sl-badge>
                    <div class="border-other-grey border-2 rounded-md p-2 flex justify-between gap-2 items-center" style="min-width: 150px; max-width: 300px; width: 100%;">
                        <sl-icon wire:ignore name="collection" class="text-secondary-grey font-sourceSansPro text-xl font-bold"></sl-icon>
                        <div class="text-secondary-grey text-xl font-sourceSansPro font-bold">{{$total_repos}}</div>
                    </div>
                </div>

                <div>
                    <sl-badge class="mb-1" variant="neutral">Total Progess</sl-badge>
                    <div class="border-other-grey border-2 rounded-md p-2 flex justify-between gap-2 items-center" style="min-width: 150px; max-width: 300px; width: 100%;">
                        <sl-icon wire:ignore name="percent" class="text-secondary-grey font-sourceSansPro text-xl font-bold"></sl-icon>
                        <div class="text-secondary-grey text-xl font-sourceSansPro font-bold">{{round($total_percentage,2)}}</div>
                    </div>
                </div>

            </div>
        </div>

    </div>
    </div>
