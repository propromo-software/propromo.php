<div wire:key="{{$project->id}}" class="p-5 m-3 prose border-4 border-blue-900 rounded-lg">

    <a href="/projects/{{ $project->id }}" class="m-1 btn btn-info delete-header btn-sm" title="Show User">
            {{$project->organisation_name}}
    </a>
</div>
