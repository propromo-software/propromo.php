<?php

use Livewire\Volt\Component;
use App\Models\Project;
use \App\Traits\RespositoryCollector;

new class extends Component {
    use RespositoryCollector;

    public Project $project;

    public function mount(Project $project): void
    {
        if ($project->repositories()->get()->isEmpty()) {
            $this->collectRepositories($project);
        }
        $this->project = $project;
    }

    public function reload_repositories()
    {
        $this->project->repositories()->delete();
        $this->collectRepositories($this->project);

        $this->dispatch("repositories-updated", project_id: $this->project->id);
    }

    public function get_repositories()
    {
        return $this->project->repositories()->get();
    }

  /*  public function placeholder()
    {
        return <<<'HTML'
        <center class="p-10">
            <sl-spinner class="text-7xl" style="--track-width: 9px;"></sl-spinner>
        </center>
        HTML;
    }*/

};
?>

<div class="w-full p-5 items-center rounded-xl">
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

            <sl-icon-button class="text-3xl" name="cloud-arrow-down" label="Reload" type="submit" wire:ignore wire:click="reload_repositories"></sl-icon-button>
        </div>
    </div>
    <livewire:repositories.list :project_id="$project->id"/>
</div>
