<?php

use Livewire\Volt\Component;
use \App\Models\Milestone;

new class extends Component {
    public Milestone $milestone;

    public function mount(Milestone $milestone)
    {
        $this->milestone = $milestone;
    }
};

?>

<div class="px-6 py-4 border-2 rounded-xl border-other-grey w-max max-h-full">

    <div class="flex justify-between gap-20">

        @php
            $shortenedTitle = strlen($milestone->title) > 15 ? substr($milestone->title, 0, 15) . '...' : $milestone->title;
        @endphp


        <div>
            <h1 class="text-primary-blue text-4xl font-koulen">
                {{$shortenedTitle}}
            </h1>

            <div class="flex gap-2 items-center">
                <sl-icon class="text-secondary-grey text-xl font-sourceSansPro font-bold" name="clock"></sl-icon>
                <p class="text-secondary-grey text-xl font-sourceSansPro font-bold">{{!is_null($milestone->due_on) ? date('d.m.y',strtotime($milestone->due_on)): "no date"}}</p>
            </div>

            <div class="flex gap-2 mt-8">
                <sl-button>
                    <a href="/monitors/{{ $milestone->repository->monitor->id }}/milestones/{{ $milestone->id }}?scope=sprint">
                        View Sprints
                    </a>
                </sl-button>
                <sl-button>
                    <a href="/monitors/{{ $milestone->repository->monitor->id }}/milestones/{{ $milestone->id }}?scope=task">
                        View Tasks
                    </a>
                </sl-button>
            </div>
        </div>

        <a class="text-primary-blue font-bold flex flex-row-reverse text-xl cursor-pointer"
           href="/monitors/{{ $milestone->repository->monitor->id }}/milestones/{{ $milestone->id }}"
        >
                <sl-icon name="arrows-angle-expand"></sl-icon>
        </a>

    </div>


    @php
        $total_issue_count = $milestone->open_issues_count + $milestone->closed_issues_count;
        $closed_issue_count = $milestone->closed_issues_count;
    @endphp


    <div class="mt-5 w-full">

        @if($milestone->progress >= 80)
            <div class="flex justify-between">
                <div class="text-additional-green font-sourceSansPro font-bold">{{$closed_issue_count}}/{{$total_issue_count}} Tasks
                </div>
                <div class="text-additional-green font-sourceSansPro font-bold">{{round($milestone->progress,2)}}%</div>
            </div>

            <sl-progress-bar class="caret-additional-green"
                             style="--indicator-color: #229342; --track-color:#22934244; --height: 2rem;"
                             value="{{$milestone->progress}}"></sl-progress-bar>

        @elseif($milestone->progress >= 50)

            <div class="flex justify-between">
                <div class="text-additional-orange font-sourceSansPro font-bold">{{$closed_issue_count}}/{{$total_issue_count}} Tasks
                </div>
                <div class="text-additional-orange font-sourceSansPro font-bold">{{round($milestone->progress,2)}}%
                </div>
            </div>

            <sl-progress-bar class="caret-additional-green "
                             style="--indicator-color: #FBC116; --track-color:#fbc2164f; --height: 2rem;"
                             value="{{$milestone->progress}}"></sl-progress-bar>
        @else

            <div class="flex justify-between">
                <div class="text-additional-red font-sourceSansPro font-bold">{{$closed_issue_count}}/{{$total_issue_count}} Tasks
                </div>
                <div class="text-additional-red font-sourceSansPro font-bold">{{round($milestone->progress,2)}}%</div>
            </div>

            <sl-progress-bar class="caret-additional-green"
                             style="--indicator-color: #E33B2E;--track-color:#e33a2e4e; --height: 2rem;"
                             value="{{$milestone->progress}}"></sl-progress-bar>

        @endif
    </div>
</div>


