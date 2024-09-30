<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <div class="border-other-grey border-2 rounded-md m-4 grid grid-cols-1 justify-center p-3 gap-2.5 text-center w-64">
        <a href="/settings/profile" class="font-koulen rounded-md text-2xl p-2 {{ request()->is('settings/profile') ? 'bg-primary-blue text-white' : '' }}">
            PROFILE
        </a>
        <a href="/settings/monitors" class="font-koulen rounded-md text-2xl p-2 {{ request()->is('settings/monitors') ? 'bg-primary-blue text-white' : '' }}">
            MONITORS
        </a>
    </div>
</div>
