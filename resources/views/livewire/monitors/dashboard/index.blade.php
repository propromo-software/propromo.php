<?php

use App\Models\Monitor;
use Livewire\Volt\Component;

new class extends Component {
    public Monitor $monitor;

    public function mount(Monitor $monitor)
    {
        $this->monitor = $monitor;
    }

}; ?>

<div>
    <div class="flex flex-col items-center gap-5 p-3 sm:p-5 rounded-xl lg:flex-row lg:gap-10">
        <livewire:monitors.dashboard.monitor-dashboard :monitor="$monitor" lazy="true"></livewire:monitors.dashboard.monitor-dashboard>
        <livewire:monitors.dashboard.mini-repository-list :monitor="$monitor" lazy="true"></livewire:monitors.dashboard.mini-repository-list>
        <livewire:monitors.dashboard.top-milestone-list></livewire:monitors.dashboard.top-milestone-list>
    </div>
</div>
