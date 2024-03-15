<?php

use Livewire\Volt\Component;
use \App\Models\User;

new class extends Component {

    public $projects = [];

    public function mount()
    {
        $this->projects = User::find(Auth::user()->id)->projects()->get();
    }
}; ?>

<div>
    @php
        $project_count = count($projects);
    @endphp

    @if($project_count > 0)
        @foreach($projects as $project)
            <div class="mx-8 border-other-grey border-2 rounded-2xl mt-6" wire:key="{{$project->id}}">
                <livewire:projects.card lazy="true" :project="$project" :key="$project->id"/>
            </div>
        @endforeach
    @else
        <h1 class="text-primary-blue font-koulen text-2xl text-center">Currently no Project avaibale! </h1>
    @endif

</div>
