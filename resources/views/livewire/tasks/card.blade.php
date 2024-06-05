<?php

use Livewire\Volt\Component;
use App\Models\Task;


new class extends Component {

    public Task $task;

    public function mount(Task $task){
        $this->task = $task;
    }

    //
}; ?>

<div class="w-full items-center rounded-xl">
    <div class="px-6 py-4 border-2 rounded-xl border-other-grey w-max max-h-full">

        <div class="flex justify-between gap-20">

            <div>
                <h1 class="text-primary-blue text-4xl font-koulen">
                    fafaaf
                </h1>

                <div class="flex gap-2 items-center">
                    <sl-icon class="text-secondary-grey text-xl font-sourceSansPro font-bold" name="clock"></sl-icon>
                    <p class="text-secondary-grey text-xl font-sourceSansPro font-bold">no date</p>
                </div>

                <div class="flex gap-2 mt-8">
                    <sl-button>
                        <a href="/monitors/?scope=sprints">
                            View Sprints
                        </a>
                    </sl-button>
                    <sl-button>
                        <a href="/monitors/?scope=tasks">
                            View Tasks
                        </a>
                    </sl-button>
                </div>
            </div>

            <a class="text-primary-blue font-bold flex flex-row-reverse text-xl cursor-pointer"
               href="/monitors//milestones/?scope=issues"
            >
                <sl-icon name="arrows-angle-expand"></sl-icon>
            </a>

        </div>
    </div>
</div>
