<?php

use Livewire\Volt\Component;
use \App\Models\Milestone;

new class extends Component {
    public Milestone $milestone;

    public function mount(Milestone $milestone){
        $this->milestone =$milestone;
    }
}; ?>

<div class="px-6 py-4 border-2 rounded-xl border-other-grey w-max max-h-full" wire:key="{{$milestone->id}}}">

    <div class="flex justify-between gap-20">

        <div>
            <h1 class="text-primary-blue text-4xl font-koulen">
                {{$milestone->title}}
            </h1>

            <div class="flex gap-2 items-center">
                <sl-icon class="text-secondary-grey text-xl font-sourceSansPro font-bold" name="clock"></sl-icon>
                <p class="text-secondary-grey text-xl font-sourceSansPro font-bold">{{date('d.m.y',strtotime($milestone->created_at))}}</p>
            </div>

            <div class="flex gap-2 mt-8">
                <sl-button>View Sprints</sl-button>
                <sl-button>View Tasks</sl-button>
            </div>
        </div>

        <a class="text-primary-blue font-bold flex flex-row-reverse text-xl cursor-pointer">
            <sl-icon name="arrows-angle-expand"></sl-icon>
        </a>
    </div>


    <div class="mt-5 w-full">

        @if($milestone['progress'] >= 80)
            <div class="flex justify-between">
                <div class="text-additional-green font-sourceSansPro font-bold">7/9 Tasks</div>
                <div class="text-additional-green font-sourceSansPro font-bold">{{round($milestone->progress,2)}}%</div>
            </div>

            <div class="h-8 bg-additional-green rounded-md"></div>
        @elseif($milestone['progress'] >= 50)

            <div class="flex justify-between">
                <div class="text-additional-orange font-sourceSansPro font-bold">5/5 Tasks</div>
                <div class="text-additional-orange font-sourceSansPro font-bold">{{round($milestone->progress,2)}}%</div>
            </div>

            <div class="h-8 bg-additional-orange rounded-md"></div>

        @else

            <div class="flex justify-between">
                <div class="text-additional-red font-sourceSansPro font-bold">8/9 Tasks</div>
                <div class="text-additional-red font-sourceSansPro font-bold">{{round($milestone->progress,2)}}%</div>
            </div>

            <div class="h-8 bg-additional-red rounded-md"></div>

        @endif
    </div>
</div>
