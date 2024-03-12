<?php

use Livewire\Volt\Component;
use \App\Traits\RespositoryCollector;
use \App\Models\Project;
use \App\Models\Repository;

new class extends Component {
    use RespositoryCollector;

    public $repositories;

    public $selectedRepository = null;

    public function mount(Project $project)
    {
        $this->repositories = $this->collectRepositories($project);
    }

    public function updatedSelectedRepository($value)
    {
        if ($value) {
            $this->repositories = Repository::whereId($value)->first()->get();
        } else {
            $this->selectedRepository = null;
        }
    }


}; ?>

<div>
    <label>
        <select wire:model.live="selectedRepository" class="flex w-fit mb-4 font-sourceSansPro" label="Select repository">
            @foreach ($repositories as $repository)
                <option value="{{ $repository->id }}">{{ $repository->name }}</option>
            @endforeach
        </select>
    </label>

    <div class="overflow-x-auto flex items-center gap-8 ">
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
