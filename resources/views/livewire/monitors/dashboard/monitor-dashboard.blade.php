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

    public $tasks = [];


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
            $repositoryTasks = $repository->milestones->flatMap(function ($milestone) {
                return $milestone->tasks;
            });

            $this->tasks = array_merge($this->tasks, $repositoryTasks->all());
        }

        /*if (empty($this->tasks)) {
            $this->reload_issues();
        }*/


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
        <sl-spinner></sl-spinner>
        HTML;
    }
}; ?>

<div class="w-full lg:w-auto lg:flex-shrink-0">
    <h2 class="m-2 text-2xl font-koulen text-primary-blue">Overview</h2>
    <div class="mb-5">
        <div class="m-2 w-full">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-5">
                <div>
                    <sl-badge class="mb-1" variant="neutral">Open Issues</sl-badge>
                    <div class="flex gap-2 justify-between items-center p-2 text-white rounded-md border-2 bg-additional-orange" style="min-width: 150px; max-width: 300px; width: 100%;">
                        <sl-icon wire:ignore name="calendar2-week" class="text-xl font-bold text-white font-sourceSansPro"></sl-icon>
                        <div class="text-xl font-bold text-white font-sourceSansPro">{{$total_issues_open}}</div>
                    </div>
                </div>

                <div>
                    <sl-badge class="mb-1" variant="neutral">Closed Issues</sl-badge>
                    <div class="flex gap-2 justify-between items-center p-2 rounded-md border-2 border-additional-green bg-additional-green" style="min-width: 150px; max-width: 300px; width: 100%;">
                        <sl-icon wire:ignore name="calendar2-x" class="text-xl font-bold text-white font-sourceSansPro"></sl-icon>
                        <div class="text-xl font-bold text-white font-sourceSansPro">{{$total_issues_closed}}</div>
                    </div>
                </div>

                <div>
                    <sl-badge class="mb-1" variant="neutral">Total Repos</sl-badge>
                    <div class="flex gap-2 justify-between items-center p-2 rounded-md border-2 border-other-grey" style="min-width: 150px; max-width: 300px; width: 100%;">
                        <sl-icon wire:ignore name="collection" class="text-xl font-bold text-secondary-grey font-sourceSansPro"></sl-icon>
                        <div class="text-xl font-bold text-secondary-grey font-sourceSansPro">{{$total_repos}}</div>
                    </div>
                </div>

                <div>
                    <sl-badge class="mb-1" variant="neutral">Total Progess</sl-badge>
                    <div class="flex gap-2 justify-between items-center p-2 rounded-md border-2 border-other-grey" style="min-width: 150px; max-width: 300px; width: 100%;">
                        <sl-icon wire:ignore name="percent" class="text-xl font-bold text-secondary-grey font-sourceSansPro"></sl-icon>
                        <div class="text-xl font-bold text-secondary-grey font-sourceSansPro">{{round($total_percentage,2)}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
