<?php

use App\Models\Monitor;
use App\Models\Repository;
use Livewire\Volt\Component;

new class extends Component {
    public $total_issues_open = 0;
    public $total_issues_closed = 0;
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

        $this->top_milestones = $this->monitor->repositories->flatMap(function ($repo) {
            return $repo->milestones;
        })->sortByDesc('progress')->take(5);

        $totalProgress = $this->monitor->repositories->flatMap(function ($repo) {
            return $repo->milestones->pluck('progress');
        })->sum() / 100;

        $totalMilestones = $this->monitor->repositories->flatMap(function ($repo) {
            return $repo->milestones;
        })->count();

        $this->total_percentage = $totalMilestones > 0 ? ($totalProgress / $totalMilestones) : 0;
    }
}; ?>


<div>
    <div class="w-full p-5 items-center rounded-xl">
        <h2 class="m-2 font-koulen text-3xl text-primary-blue">Overview</h2>
        <div class="flex items-center justify-between mb-5">
            {{$total_issues_closed}}
            {{$total_issues_open}}

        @foreach($top_milestones as $iterator)
                {{$iterator->title}}
            @endforeach

        </div>
    </div>
</div>
