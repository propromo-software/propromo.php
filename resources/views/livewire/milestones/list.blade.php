<?php

use Livewire\Volt\Component;
use App\Models\Milestone;
use App\Models\Project;
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

<div class="h-full flex gap-2 items-center">
    @foreach($milestones as $milestone)
        <livewire:milestones.card :milestone="$milestone" :key="$milestone->id"/>
        <div class="bg-primary-blue rounded border p-4 px-6rounded-lg"></div>
    @endforeach
</div>
