<?php

use Livewire\Volt\Component;
use App\Models\Task;

new class extends Component {
    public $tasks = [];

    public function mount($tasks)
    {
        $this->tasks = $tasks;
    }

}; ?>

<div class="flex items-center justify-center h-full">
    @if(count($tasks) > 0)
        <div class="overflow-x-auto flex items-center gap-5">
            @foreach($tasks as $index => $task)
                <livewire:tasks.card :task="$task" :key="$index" :taskIdCounter="$index"></livewire:tasks.card>
            @endforeach
        </div>
    @else
        <div class="text-center">
            <p class="font-koulen text-primary-blue text-3xl font-semibold">No tasks available.</p>
        </div>
    @endif
</div>
