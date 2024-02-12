
<div class="w-full p-5 items-center rounded-xl my-3" wire:key="{{$project->id}}">

    <div class="flex items-center justify-between mb-5">

        <a class="text-secondary-grey text-xl font-sourceSansPro font-bold rounded-md border-2 border-other-grey px-6 py-3" href="/projects/{{ $project->id }}" title="Show User">
                {{strtoupper($project->organisation_name)}}
        </a>

            <a class="flex items-center gap-1 rounded-md border-2 border-other-grey px-6 py-3" href="/projects/{{ $project->id }}" title="Show User">
                <sl-icon class="text-secondary-grey font-sourceSansPro text-2xl font-bold" name="chat">

                </sl-icon>
                <div class="text-secondary-grey font-sourceSansPro text-xl font-bold ">
                    CONTACT
                </div>
            </a>
    </div>

    @include('livewire.includes.milestones-card', ['milestones' => $project->milestones])

</div>
