<?php

use Livewire\Volt\Component;

new class extends Component
{
}; ?>

<div class="flex flex-col items-center justify-center md:flex-row">
    <div class="columns-1">
        <div class="flex flex-col items-center justify-center gap-0 mb-10 md:gap-7 md:flex-row md:items-start">
            <div class="w-full md:w-80">
                <img src="{{asset('/assets/logo/Propromo-Logo-circle.svg')}}" alt="propromo-logo">
            </div>
            <div class="flex flex-col items-center my-auto md:block">
                <h1 class="flex-initial font-koulen text-7xl text-primary-blue text-uppercase">Propromo</h1>
                <h2 class="flex-initial text-4xl text-center font-koulen text-secondary-grey text-uppercase md:text-left">Project Progress Monitoring</h2>
                <p class="flex-initial mt-5 text-2xl font-koulen text-other-grey text-uppercase">works with:</p>
                <div>
                    <a href="https://github.com/" target="_blank">
                        <sl-icon wire:ignore name="github" class="text-4xl mt-0.5"></sl-icon>
                    </a>
                    <!-- <sl-icon name="github"></sl-icon> -->
                </div>
            </div>
        </div>

        <livewire:home.join-monitor-form class="mt-20" />

        <!-- <h1 class="mt-20 text-4xl text-center font-koulen text-secondary-grey">PROJECT PREVIEW</h1>

        <div class="mt-8">
            <div class="relative w-full aspect-video">
                <iframe 
                    class="absolute top-0 left-0 w-full h-full rounded-lg"
                    src="https://player.vimeo.com/video/953693432?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479&background=1&responsive=1" 
                    frameborder="0" 
                    allow="autoplay; fullscreen; picture-in-picture; clipboard-write" 
                    title="Propromo Preview">
                </iframe>
            </div>
        </div> -->

        <br>
    </div>
</div>
