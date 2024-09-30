<?php

use Livewire\Volt\Component;

new class extends Component {

}; ?>

<div class="mt-4 mx-8">


    <div class="mt-10">
        <div class="flex gap-2 items-center">
            <a href="{{ route('home.index') }}" title="Settings">
                <sl-icon class="text-4xl text-primary-blue" name="arrow-left-circle"></sl-icon>
            </a>
            <a class="text-secondary-grey text-lg font-sourceSansPro font-bold rounded-md border-2 border-other-grey px-6 py-3" title="Settings">
                Settings
            </a>
        </div>

        <div class="border-other-grey border-2 rounded-md mt-4 flex justify-around gap-10">
            <livewire:settings.navigation></livewire:settings.navigation>

        </div>
    </div>



</div>
