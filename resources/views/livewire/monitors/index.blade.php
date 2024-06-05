<?php

use Livewire\Volt\Component;
use \App\Models\User;

new class extends Component
{

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

<div class="mt-4 mx-8">

    <sl-input wire:ignore wire:model.live="search" class="w-max" placeholder="Search for a monitor...">
        <sl-icon name="search" slot="prefix"></sl-icon>
    </sl-input>

    @php
        $monitor_count = count($monitors);
    @endphp

    @if($monitor_count > 0)
        @foreach($monitors as $monitor)
        <div class="border-other-grey border-2 rounded-2xl mt-4" wire:key="{{ $monitor->id }}">
            <livewire:monitors.card lazy="true" :monitor="$monitor"/>
        </div>
        @endforeach
    @else
    <h1 class="text-primary-blue font-koulen text-2xl text-center">Currently no Monitors avaibale! </h1>
    @endif

</div>
