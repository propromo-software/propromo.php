<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

    public string $section;


    #[On('content_changed')]
    public function update_section($section)
    {
        $this->section = $section;
    }
}; ?>

<div>
    @if($section == 'join')
        <livewire:settings.monitors.actions.join></livewire:settings.monitors.actions.join>
    @else($section == 'create')
        <livewire:settings.monitors.actions.create></livewire:settings.monitors.actions.create>
    @endif
</div>
