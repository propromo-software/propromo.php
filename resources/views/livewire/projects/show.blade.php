<?php

use Livewire\Volt\Component;
use \App\Models\Project;

new class extends Component {
    public Project $project;

}; ?>

<div>
    <livewire:repositories.list lazy="true" :project="$project"/>
</div>
