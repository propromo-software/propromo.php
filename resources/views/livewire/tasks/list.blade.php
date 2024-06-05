<?php

use Livewire\Volt\Component;
use App\Models\Task;

new class extends Component {
    public $tasks = [];

    public function mount(){
        $this->tasks = [
            new Task([
                'id' => 1,
                'is_active' => true,
                'body_url' => 'http://example.com/task1',
                'created_at' => now(),
                'updated_at' => now(),
                'last_edited_at' => now(),
                'closed_at' => null,
                'body' => 'This is the body of task 1',
                'title' => 'Task 1',
                'url' => 'http://example.com/task1',
                'milestone_id' => 1,
            ]),
            new Task([
                'id' => 2,
                'is_active' => false,
                'body_url' => 'http://example.com/task2',
                'created_at' => now(),
                'updated_at' => now(),
                'last_edited_at' => now(),
                'closed_at' => null,
                'body' => 'This is the body of task 2',
                'title' => 'Task 2',
                'url' => 'http://example.com/task2',
                'milestone_id' => 1,
            ]),
        ];
    }

}; ?>

<div>
    <div class="overflow-x-auto flex items-center">
        @foreach($tasks as $task)
            <livewire:tasks.card :task="$task"></livewire:tasks.card>
        @endforeach
    </div>
</div>
