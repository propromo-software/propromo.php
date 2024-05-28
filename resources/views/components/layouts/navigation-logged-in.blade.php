<?php

use Livewire\Volt\Component;

new class extends Component {

    public $monitor_hash;



}

?>


<div class="flex justify-between items-center gap-2 mt-5 mx-8 border-2 border-other-grey p-6 rounded-2xl">
    <a class="font-koulen text-primary-blue text-5xl" href="{{ url('/') }}">PROPROMO</a>

    <div class="flex gap-x-5">

        @php
            $monitor_hash = Session::has("monitor_hash") ? Session::get("monitor_hash") : 'no monitors available';
        @endphp

        @if(Route::current()->parameter('monitor'))
            <livewire:base.copy-monitor-id :monitor_hash="$monitor_hash">
        @endif

        <div class="flex items-center gap-2">
            <sl-icon name="gear-wide-connected" class="text-3xl font-bold text-primary-blue"></sl-icon>
            <sl-icon name="person-circle" class="text-3xl font-bold text-primary-blue"></sl-icon>
        </div>
    </div>
</div>
