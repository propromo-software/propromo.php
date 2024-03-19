<?php

use Livewire\Volt\Component;
use \App\Models\Monitor;

new class extends Component {
    public Monitor $monitor;

    public function mount(Monitor $monitor)
    {
        Session::put('monitor_hash', $monitor->monitor_hash);
        $this->monitor = $monitor;
    }

}; ?>

<div class="mx-8 mt-6">
    <div class="border-other-grey border-2 rounded-2xl">
        <livewire:monitors.card lazy="true" :monitor="$monitor"/>
    </div>

    <livewire:monitors.read-me-view :markdown="$monitor->readme"/>
</div>
