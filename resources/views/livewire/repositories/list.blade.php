<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;
use \App\Traits\RepositoryCollector;
use \App\Models\Monitor;
use \App\Models\Repository;

new class extends Component
{
    use RepositoryCollector;

    public $monitor_id;
    public $repositories;

    public function mount($monitor_id)
    {
        $this->repositories = Repository::whereMonitorId($monitor_id)->get();
        $this->monitor_id = $monitor_id;
    }

    #[On('repositories-updated')]
    public function repositories_updated($monitor_id)
    {
        if ($this->monitor_id === $monitor_id) {
            $this->mount($monitor_id);
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
                <livewire:milestones.list :repository="$repository" :key="$repository->id" />
            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>
