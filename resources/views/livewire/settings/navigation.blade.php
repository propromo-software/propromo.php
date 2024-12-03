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

<div>
    <div class="border-other-grey border-2 rounded-md grid grid-cols-1 justify-center p-3 gap-2.5 text-center">
        <button wire:click="switch_section('profile')" class="{{ $current_section == 'profile' ? 'bg-primary-blue text-white border-primary-blue' : '' }} w-full py-2 border-other-grey border-2 rounded-md font-koulen text-2xl h-min">
            PROFILE
        </button>
        <button wire:click="switch_section('monitors')" class="{{ $current_section == 'monitors' ? 'bg-primary-blue text-white border-primary-blue' : '' }} w-full py-2 border-2 border-other-grey rounded-md font-koulen text-2xl h-min">
            MONITORS
        </button>
        <button wire:click="switch_section('ui')" class="{{ $current_section == 'ui' ? 'bg-primary-blue text-white border-primary-blue' : '' }} w-full py-2 border-2 border-other-grey rounded-md font-koulen text-2xl h-min">
            UI
        </button>

    </div>
</div>
