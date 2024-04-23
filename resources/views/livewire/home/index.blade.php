<?php

use Livewire\Volt\Component;

new class extends Component {


}; ?>

<div class="flex justify-center">
    <div class="columns-1 w-fit">
        <div class="flex items-center justify-center mb-10 gap-14">
            <div class="w-80">
                <img src="{{asset('/assets/logo/Propromo-Logo-circle.svg')}}" alt="propromo-logo">
            </div>
            <div>
                <h1 class="flex-initial font-koulen text-7xl text-primary-blue">PROPROMO</h1>
                <h2 class="flex-initial text-4xl font-koulen text-secondary-grey">PROJECT PROGRESS MONITORING</h2>
                <p class="flex-initial mt-5 text-2xl font-koulen text-other-grey">WORKS WITH:</p>
                <div>
                    <a href="https://github.com/" target="_blank">
                        <sl-icon wire:ignore name="github" class="text-4xl mt-0.5"></sl-icon>
                    </a>
                    <!-- <sl-icon name="github"></sl-icon> -->
                </div>
            </div>
        </div>

        <livewire:home.join-monitor-form class="mt-20"/>

        <h1 class="mt-20 text-4xl text-center font-koulen text-secondary-grey">PROJECT PREVIEW</h1>

        <iframe width="100%" height="315" src="https://www.youtube-nocookie.com/embed/SXmJH72-O5g?si=if48WPOjUBCZZyi4&amp;controls=0&amp;autoplay=1&amp;loop=1&amp;mute=1&amp;showinfo=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>
</div>


