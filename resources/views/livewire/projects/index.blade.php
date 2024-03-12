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

<div class=" mx-8 border-other-grey border-2 rounded-2xl mt-6">
    @foreach($projects as $project)
        <livewire:projects.card :project="$project" :key="$project->id"/>
    @endforeach
</div>

