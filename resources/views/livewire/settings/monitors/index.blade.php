<?php

use Livewire\Volt\Component;
use function Livewire\Volt\layout;

new class extends Component {

}; ?>

<div>
    <div class="border-other-grey border-2 rounded-md grid grid-cols-1 justify-center p-3 gap-2.5 text-center w-full">
        <livewire:settings.monitors.edit></livewire:settings.monitors.edit>
        <livewire:settings.monitors.edit-pat-token></livewire:settings.monitors.edit-pat-token>
        <livewire:settings.monitors.actions.index></livewire:settings.monitors.actions.index>

    </div>
</div>
