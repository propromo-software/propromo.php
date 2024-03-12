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
    <div class="mx-8 border-other-grey border-2 rounded-2xl mt-6">
        @php
            $project_count = count($projects);
        @endphp

        @if($project_count > 0)
            @foreach($projects as $project)
                <livewire:projects.card :project="$project" :key="$project->id"/>
            @endforeach
        @else
            <h1 class="text-primary-blue font-koulen text-2xl text-center">Currently no Project avaibale! </h1>
        @endif

    </div>
</div>
