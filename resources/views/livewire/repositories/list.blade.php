<?php

use Livewire\Volt\Component;
use \App\Traits\RespositoryCollector;
use \App\Models\Project;
use \App\Models\Repository;

new class extends Component {
    use RespositoryCollector;

    public $repositories = [];

    public $selectedRepository = null;

    public function mount(Project $project)
    {
        $repositories = Project::find($project->id)->repositories()->get();
        if ($repositories->isNotEmpty()) {
            $this->repositories = $repositories;
        } else {
            $this->repositories = $this->collectRepositories($project);
        }
    }

    public function updatedSelectedRepository($value)
    {
        if ($value) {
            $this->repositories = Repository::whereId($value)->first()->get();
        } else {
            $this->selectedRepository = null;
        }
    }

    public function placeholder()
    {
        return <<<'HTML'
        <center>
            <sl-spinner class="text-8xl" style="--track-width: 10px;"></sl-spinner>
        </center>
        HTML;
    }
};
?>

<div>
    <div class="overflow-x-auto flex items-center gap-8">
        @foreach($repositories as $repository)
            @php
                $milestonesCount = $repository->milestones()->count();
            @endphp

            @if($milestonesCount > 0)
                <div>
                    <h2 class="m-2 font-koulen text-3xl text-primary-blue">{{$repository->name}}</h2>
                    <div class="border-other-grey border-2 rounded-2xl p-8 m-2">
                        <livewire:milestones.list :repository="$repository"/>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
