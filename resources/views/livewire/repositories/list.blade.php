<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;
use \App\Traits\RespositoryCollector;
use \App\Models\Project;
use \App\Models\Repository;

new class extends Component {
    use RespositoryCollector;

    public $project_id;
    public $repositories;

    public function mount($project_id)
    {
        $this->repositories = Repository::whereProjectId($project_id)->get();
        $this->project_id = $project_id;
    }

    #[On('repositories-updated')]
    public function repositories_updated($project_id)
    {
        if ($this->project_id === $project_id) {
            $this->mount($project_id);
        }
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
                <div wire:key="{{ $repository->id }}">
                    <h2 class="m-2 font-koulen text-3xl text-primary-blue">{{$repository->name}}</h2>
                    <div class="border-other-grey border-2 rounded-2xl p-8 m-2">
                        <livewire:milestones.list :repository="$repository" :key="$repository->id"/>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
