<?php

use Livewire\Volt\Component;
use \App\Models\Project;

new class extends Component {
    public Project $project;

}; ?>

<div class="mx-8 border-other-grey border-2 rounded-2xl mt-6">
    <livewire:projects.card lazy="true" :project="$project"/>
</div>
