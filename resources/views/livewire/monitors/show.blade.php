<?php

use Livewire\Volt\Component;
use \App\Models\Monitor;

new class extends Component {
    public Monitor $monitor;

    public function mount(Monitor $monitor)
    {
        $this->monitor = $monitor;
    }

}; ?>

<div>
    <div class="mx-8 mt-6">
        <a href="/monitors" title="Show Monitor" class="flex items-center mb-2">
            <sl-icon class="cursor-pointer text-primary-blue text-4xl rounded-md border-2 p-2 border-other-grey" name="arrow-left-short" wire:ignore></sl-icon>
        </a>
        <div class="border-other-grey border-2 rounded-2xl">
            <livewire:monitors.card lazy="true" :monitor="$monitor"/>
        </div>

        <div class="border-other-grey border-2 rounded-2xl mt-8">
            <livewire:monitors.dashboard.index :monitor="$monitor" lazy="true"/>
        </div>

        <div class="mt-8 grid grid-cols-3 gap-8">
            <div class="col-span-2">
                <livewire:monitors.read-me-view :monitor="$monitor"/>
            </div>
            <div class="border-other-grey border-2 rounded-2xl p-5">Deployments</div>
        </div>
    </div>
</div>
