<?php

use Livewire\Volt\Component;
use App\Models\Project;


new class extends Component {
    public Project $project;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function reloadRepositories()
    {
        $repositories = $this->project->repositories();
        if($repositories->get()->isNotEmpty()){
            $repositories->delete();
        }
        return $this->render();
    }
    public function test()
    {
        dd("fsfdsa");
    }
};
?>

<div class="w-full p-5 items-center rounded-xl" wire:key="{{$project->id}}">

    <div class="flex items-center justify-between mb-5">

        <a class="text-secondary-grey text-lg font-sourceSansPro font-bold rounded-md border-2 border-other-grey px-6 py-3"
           href="/projects/{{ $project->id }}" title="Show User">
            {{strtoupper($project->organisation_name)}}
        </a>

        <div class="flex items-center gap-2">
            <a class="flex items-center gap-1 rounded-md border-2 border-other-grey px-6 py-3"
               href="/projects/{{ $project->id }}" title="Show User">
                <sl-icon wire:ignore class="text-secondary-grey font-sourceSansPro text-xl font-bold" name="chat"></sl-icon>
                <div>
                    <div class="text-secondary-grey font-sourceSansPro text-lg font-bold">
                        CONTACT
                    </div>
                </div>
            </a>
            <sl-icon-button class="text-3xl" name="cloud-arrow-down" label="Reload" type="button" wire:ignore wire:click="reloadRepositories"></sl-icon-button>
        </div>
    </div>
    <livewire:repositories.list :project="$project" lazy="true"/>
</div>
