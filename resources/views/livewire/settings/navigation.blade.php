<?php

use Livewire\Volt\Component;

new class extends Component {
    public $current_section = 'profile';

    public function switch_section($section)
    {
        $this->current_section = $section;
        $this->dispatch('section_changed', $section);
    }
}; ?>

<div class="flex flex-col gap-1 p-4 text-center bg-white rounded-lg border-2 border-other-grey">
    <button 
        wire:click="switch_section('profile')" 
        class="{{ $current_section == 'profile' ? 'bg-primary-blue text-white' : 'bg-[#F5F5F5] text-gray-500 hover:bg-gray-200' }} w-full py-2 rounded-md font-koulen text-xl transition-colors duration-200"
    >
        PROFILE
    </button>
    <button 
        wire:click="switch_section('monitors')" 
        class="{{ $current_section == 'monitors' ? 'bg-primary-blue text-white' : 'bg-[#F5F5F5] text-gray-500 hover:bg-gray-200' }} w-full py-2 rounded-md font-koulen text-xl transition-colors duration-200"
    >
        MONITORS
    </button>
</div>
