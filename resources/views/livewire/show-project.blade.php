<div class="flex flex-col items-center">
    <h1 class="text-4xl prose">{{strtoupper($project->organisation_name)}}</h1>
    <livewire:show-milestones :project="$project"/>
</div>
