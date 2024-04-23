<?php

use Livewire\Volt\Component;

new class extends Component {
    public $tasks = [];

    public function mount(){

    }

}; ?>

<div class="h-full flex gap-2 items-center">
    @foreach($tasks as $key => $task)
        @if ($loop->last)
            <livewire:milestones.card :milestone="$milestone" :key="$task->id"/>
        @else
            <livewire:milestones.card :milestone="$milestone" :key="$milestone->id"/>
            <div class="bg-primary-blue rounded-md border p-4 px-6"></div>
        @endif
    @endforeach
</div>
