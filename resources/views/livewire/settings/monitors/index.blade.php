<?php

use Livewire\Volt\Component;
use function Livewire\Volt\layout;

new class extends Component {

}; ?>

<div>
    <div class="grid grid-cols-1 gap-2.5 justify-center w-full text-center">
        <livewire:settings.monitors.edit></livewire:settings.monitors.edit>
        <livewire:settings.monitors.edit-pat-token></livewire:settings.monitors.edit-pat-token>
        <livewire:settings.monitors.actions.index></livewire:settings.monitors.actions.index>
    </div>
</div>
