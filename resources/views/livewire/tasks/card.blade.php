<?php

use Livewire\Volt\Component;
use App\Models\Task;
use Carbon\Carbon;

new class extends Component {

    public Task $task;
    public $taskIdCounter;

    public function mount(Task $task, $taskIdCounter){
        $this->task = $task;
        $this->taskIdCounter = $taskIdCounter;
    }

    public function getFormattedDate() {
        return $this->task->created_at ? Carbon::parse($this->task->created_at)->format('F j, Y') : null;
    }

    //
}; ?>

<div class="flex flex-wrap gap-4">
    <div class="flex flex-col justify-between px-6 py-4 border-2 rounded-xl border-other-grey w-96 h-64 mb-4"> <!-- Adjusted width to w-80 -->

        <div>
            <div class="flex justify-between">
                <h1 class="text-primary-blue text-4xl font-koulen">
                    Issue {{$taskIdCounter+1}}
                </h1>

                <a class="text-primary-blue font-bold flex flex-row-reverse text-4xl cursor-pointer"
                   href="{{$task->url}}"
                   target="_blank"
                >
                    <sl-icon name="link-45deg"></sl-icon>
                </a>
            </div>

            <div class="flex gap-2 items-center mt-2">
                <sl-icon class="text-secondary-grey text-xl font-sourceSansPro font-bold" name="clock"></sl-icon>
                @if($formattedDate = $this->getFormattedDate())
                    <p class="text-secondary-grey text-xl font-sourceSansPro font-bold">{{ $formattedDate }}</p>
                @else
                    <p class="text-secondary-grey text-xl font-sourceSansPro font-bold">no date</p>
                @endif
            </div>

            <p class="text-secondary-grey text-sm font-sourceSansPro mt-2 break-words">
                @if(strlen($task->title) > 50)
                    {{ substr($task->title, 0, 50) }}...
                @else
                    {{ $task->title }}
                @endif
            </p>
        </div>

        <div class="h-24 overflow-auto">
            <div class="flex flex-wrap gap-2 mt-4">
                @if($task->labels()->exists())
                    @foreach($task->labels as $label)
                        <a href="{{url('/')}}/monitors/1/milestones/{{$task->milestone->id}}?scope={{$label->name}}" class="py-1.5 px-4 text-white rounded-xl text-sm border-primary-blue font-bold font-sourceSansPro cursor-pointer bg-primary-blue text-center">
                            {{$label->name}}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
