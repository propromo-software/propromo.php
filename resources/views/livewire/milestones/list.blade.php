<?php

use Livewire\Volt\Component;
use App\Models\Milestone;
use App\Models\Monitor;
use App\Traits\MilestoneCollector;
use \App\Models\Repository;


new class extends Component {

    public $milestones = [];

    public function mount(Repository $repository)
    {
        $this->milestones = Milestone::whereRepositoryId($repository->id)->get();
    }
};
?>

<div class="h-full flex gap-2 items-center ">
    @foreach($milestones as $key => $milestone)
        @if ($loop->last)
            <livewire:milestones.card :milestone="$milestone" :key="$milestone->id"/>
        @else
            <livewire:milestones.card :milestone="$milestone" :key="$milestone->id"/>
        @endif
    @endforeach
</div>
