<?php

use Livewire\Volt\Component;
use \App\Models\Project;

new class extends Component {
    public Project $project;

    public function mount(Project $project)
    {
        Session::put('project_hash', $project->project_hash);
        $this->project = $project;
    }

}; ?>

<div class="mx-8 mt-6">
    <div class="border-other-grey border-2 rounded-2xl">
        <livewire:projects.card lazy="true" :project="$project"/>
    </div>



</div>
