<?php

use Livewire\Volt\Component;
use App\Models\Milestone;
use App\Models\Project;
use App\Traits\MilestoneCollector;

new class extends Component {
    use MilestoneCollector;

    public $milestones = [];

    public function mount(Project $project)
    {
        $this->collectMilestones($project);
        $this->milestones = $project->milestones;
    }
}; ?>

<div class="rounded-md border-2 border-other-grey p-5 overflow-x-auto h-full flex gap-2 items-center">
    @foreach($milestones as $milestone)
            <livewire:milestones.card :milestone="$milestone"/>
    @endforeach
</div>
