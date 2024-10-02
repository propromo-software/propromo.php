<?php

use Livewire\Volt\Component;

new class extends Component {

    public $current_section = 'create';

    public function mount(): void
    {
        $this->dispatch('content_changed', $this->current_section);
    }

    public function switch_section($section)
    {
        $this->current_section = $section;
        $this->dispatch('content_changed', $section);
    }
}; ?>

<div>
    <div class="p-3">
        <h1 class="text-primary-blue text-2xl font-koulen text-left">Monitor Actions</h1>
        <div class="flex gap-2">
            <button wire:click="switch_section('create')" class="{{ $current_section == 'create' ? 'bg-primary-blue text-white border-primary-blue' : '' }} w-full py-2 border-2 rounded-md font-koulen text-xl h-min">
                CREATE
            </button>
            <button wire:click="switch_section('join')" class="{{ $current_section == 'join' ? 'bg-primary-blue text-white border-primary-blue' : '' }} w-full py-2 border-2 rounded-md font-koulen text-xl h-min">
                JOIN
            </button>
        </div>
    </div>
</div>
