<?php

use Livewire\Volt\Component;
use \App\Models\User;
use Livewire\With\Url;

new class extends Component {

    public $projects = [];
    #[Url]
    public $search = '';

    public function mount()
    {
        $this->load_projects();
    }

    public function load_projects()
    {
        $this->projects = User::find(Auth::user()->id)->projects()->get();
    }

    public function get_projects()
    {
        return $this->projects;
    }

    public function updatedSearch()
    {
        $this->load_projects();
        $this->projects = $this->projects->filter(function ($project) {
            return stripos($project->organisation_name, $this->search) !== false;
        });
    }
}; ?>

<div class="mt-4 mx-8">

        <sl-input wire:ignore wire:model.live="search" class="w-max" placeholder="Search for a project...">
            <sl-icon name="search" slot="prefix"></sl-icon>
        </sl-input>

    @php
        $project_count = count($projects);
    @endphp

    @if($project_count > 0)
        @foreach($projects as $project)
            <div class="border-other-grey border-2 rounded-2xl mt-4" wire:key="{{$project->id}}">
                <livewire:projects.card lazy="true" :project="$project" :key="$project->id"/>
            </div>
        @endforeach
    @else
        <h1 class="text-primary-blue font-koulen text-2xl text-center">Currently no Project avaibale! </h1>
    @endif

</div>
