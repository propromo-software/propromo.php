<?php

use App\Models\Monitor;
use App\Models\Repository;
use Livewire\Volt\Component;

new class extends Component {
    public $total_issues_open = 0;
    public $total_issues_closed = 0;
    public $total_issues= 0;
    public $total_repos = 0;
    public $total_percentage = 0;
    public $top_milestones = [];
    public Monitor $monitor;

    public function mount(Monitor $monitor)
    {
        $this->monitor = $monitor;
        $this->calculate_statistics();
    }

    protected function calculate_statistics(): void
    {
        $this->total_repos = $this->monitor->repositories()->count();

        $this->total_issues_open = $this->monitor->repositories->flatMap(function ($repo) {
            return $repo->milestones->flatMap(function ($milestone) {
                return $milestone->tasks->whereNotNull('closed_at');
            });
        })->count();

        $this->total_issues_closed = $this->monitor->repositories->flatMap(function ($repo) {
            return $repo->milestones->flatMap(function ($milestone) {
                return $milestone->tasks->whereNull('closed_at');
            });
        })->count();

        $this->total_issues = $this->total_issues_closed + $this->total_issues_open;

        $this->top_milestones = $this->monitor->repositories->flatMap(function ($repo) {
            return $repo->milestones;
        })->sortByDesc('progress')->take(5);

        $totalProgress = $this->monitor->repositories->flatMap(function ($repo) {
            return $repo->milestones->pluck('progress');
        })->sum() / 100;

        $totalMilestones = $this->monitor->repositories->flatMap(function ($repo) {
            return $repo->milestones;
        })->count();

        $this->total_percentage = ($this->total_issues_closed / $this->total_issues) * 100;
    }
}; ?>


<div>
    <div class="w-full p-5 items-center rounded-xl">
        <h2 class="m-2 font-koulen text-3xl text-primary-blue">Overview</h2>
        <div class="flex items-center justify-between mb-5">

            <div class="m-2">
                <div class="grid grid-cols-2 gap-2">

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
</div>
