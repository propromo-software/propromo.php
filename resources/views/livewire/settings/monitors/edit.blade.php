<?php

use Livewire\Volt\Component;

new class extends Component {
    public $monitors;

    public function mount()
    {
        $this->monitors = auth()->user()->monitors;
    }

    public function leave($monitor_id)
    {
        $monitor = auth()->user()->monitors()->find($monitor_id);
        if ($monitor) {
            $monitor->users()->detach(auth()->id());
            $this->monitors = auth()->user()->monitors;
        }
    }
}; ?>

<div>
    <div class="grid-cols-1 gap-2 p-3.5">
        <h1 class="mb-6 text-3xl text-left text-primary-blue font-koulen">Edit Monitors</h1>

        @if(count($monitors) > 0)
            <div class="flex flex-col gap-3">
                @foreach($monitors as $monitor)
                    <div class="flex justify-between items-center p-3.5 rounded-md border-2 border-other-grey">
                        <h1 class="text-2xl font-koulen text-primary-blue">{{$monitor->organization_name}}</h1>
                        <sl-button variant="danger" size="small" wire:click="leave({{$monitor->id}})">
                            <sl-icon slot="prefix" name="door-open"></sl-icon>
                            Leave
                        </sl-button>
                    </div>
                @endforeach
            </div>
        @else
            <x-info-box variant="info">Currently no Monitors available!</x-info-box>
        @endif
    </div>
</div>
