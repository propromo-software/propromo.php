<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component {
    public string $section = 'profile';
    protected $listeners = ["section_changed" => "update_section"];

    #[On('section_changed')]
    public function update_section($section)
    {
      $this->section = $section;
    }

}; ?>

<div class="p-6 bg-white rounded-lg border-2 border-other-grey">
    @if($section == 'profile')
        <livewire:settings.profile.index></livewire:settings.profile.index>
    @elseif($section == 'monitors')
        <livewire:settings.monitors.index></livewire:settings.monitors.index>
    @elseif($section == 'ui')
        <livewire:settings.ui.index></livewire:settings.ui.index>
    @endif
</div>
