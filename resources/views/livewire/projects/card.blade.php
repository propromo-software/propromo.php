<?php

use Livewire\Volt\Component;
use App\Models\Project;

new class extends Component {
    public Project $project;

    public function mount(Project $project)
    {
        $this->project = $project;
    }
}; ?>


<div class="w-full p-5 items-center rounded-xl" wire:key="{{$project->id}}">

    <div class="flex items-center justify-between mb-5">

        <a class="text-secondary-grey text-lg font-sourceSansPro font-bold rounded-md border-2 border-other-grey px-6 py-3" href="/projects/{{ $project->id }}" title="Show User">
            {{strtoupper($project->organisation_name)}}
        </a>

        <a class="flex items-center gap-1 rounded-md border-2 border-other-grey px-6 py-3" href="/projects/{{ $project->id }}" title="Show User">
            <sl-icon class="text-secondary-grey font-sourceSansPro text-xl font-bold" name="chat">

            </sl-icon>
            <div class="text-secondary-grey font-sourceSansPro text-lg font-bold ">
                CONTACT
            </div>
        </a>
    </div>
    <h1>FICK DICH WOLFSBURGER</h1>
</div>
