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
    }

}; ?>


<div>
    <div class="w-full p-5 items-center rounded-xl">
        <h2 class="m-2 font-koulen text-3xl text-primary-blue">Overview</h2>
        <div class="flex items-center justify-between mb-5">
            {{$total_issues_closed}}
        </div>
    </div>
</div>
