<?php

use App\Models\User;
use Livewire\Volt\Component;

new class extends Component {
    public $monitors = [];
    public $search = '';

    public function mount()
    {
        $this->load_monitors();
    }

    public function load_monitors()
    {
        $this->monitors = User::find(Auth::user()->id)->monitors()->get();
    }

    public function get_monitors()
    {
        return $this->monitors;
    }

    public function updatedSearch()
    {
        $this->load_monitors();
        $this->monitors = $this->monitors->filter(function ($monitor) {
            return stripos($monitor->title, $this->search) !== false;
        });
    }
}; ?>

<div class="flex flex-col min-h-screen">
    <div class="container flex-grow px-8 mx-auto">
        <sl-input wire:ignore wire:model.live="search" class="w-max" placeholder="Search for a monitor...">
            <sl-icon name="search" slot="prefix"></sl-icon>
        </sl-input>

        @php
            $monitor_count = count($monitors);
        @endphp

        @if($monitor_count > 0)
            @foreach($monitors as $monitor)
                <div class="flex mt-4 rounded-2xl border-2 border-other-grey" wire:key="{{ $monitor->id }}">
                    <livewire:monitors.custom.card class="flex-1" lazy="true" :monitor="$monitor"/>
                </div>
            @endforeach
        @else
            <x-info-box variant="info">Currently no Monitors available!</x-info-box>
        @endif
    </div>
</div>
