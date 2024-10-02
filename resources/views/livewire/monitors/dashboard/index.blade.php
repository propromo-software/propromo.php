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
    <div class="p-5 items-center rounded-xl flex gap-10">
        <livewire:monitors.dashboard.monitor-dashboard :monitor="$monitor" lazy="true"></livewire:monitors.dashboard.monitor-dashboard>
        <livewire:monitors.dashboard.mini-repository-list :monitor="$monitor" lazy="true"></livewire:monitors.dashboard.mini-repository-list>
        <livewire:monitors.dashboard.top-milestone-list></livewire:monitors.dashboard.top-milestone-list>
    </div>
</div>
