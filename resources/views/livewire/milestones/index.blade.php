<?php

use Livewire\Volt\Component;
use App\Models\Milestone;


new class extends Component {
    public milestones = [];

    public function mount(Project $project)
    {
        $this->project = $project;
    }
}; ?>

<div class="rounded-md border-2 border-other-grey p-5 flex gap-2 items-center">
    @foreach($milestones as $milestone)
        @include('livewire.includes.milestone-card', ['milestone' => $milestone])
        <div class="border-primary-blue bg-primary-blue w-11 h-8 rounded-md">
            <!-- Additional content related to milestone -->
        </div>
    @endforeach
</div>
