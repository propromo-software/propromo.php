<?php

use Livewire\Volt\Component;

new class extends Component {
    public $monitor_hash;


}

?>


<div class="flex justify-between items-center gap-2 mt-5 mx-8 border-2 border-other-grey p-6 rounded-2xl">
    <a class="font-koulen text-primary-blue text-5xl" href="{{ url('/') }}">PROPROMO</a>

    <div class="flex gap-x-5">

        @if(count(request()->segments()) == 2 && request()->segment(1) == 'monitors')
            <livewire:base.copy-monitor-id>
        @endif

        <div class="flex items-center gap-2">

            <a href="/settings/profile">
                <sl-icon name="gear-wide-connected" class="text-3xl font-bold text-primary-blue"></sl-icon>
            </a>
            <a href="/settings/profile">
                <sl-icon name="person-circle" class="text-3xl font-bold text-primary-blue"></sl-icon>
            </a>
        </div>
    </div>
</div>
