<?php

use App\Models\Milestone;
use App\Models\Repository;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public $repository_id = -1;
    public $milestones = [];

    public function mount()
    {
        $milestones = Repository::find($this->repository_id)->milestones ?? [];
        $this->milestones = $milestones;
    }

    #[On('repository_changed')]
    public function switch_repositories($repository_id): void
    {
        $this->repository_id = intval($repository_id);

        $milestones = Repository::find($this->repository_id)?->milestones()->get();

        $this->milestones = $milestones;
    }
};
?>

<div>
    <h2 class="m-2 text-2xl font-koulen text-primary-blue">MILESTONES</h2>
    <div class="overflow-auto p-2.5 m-2 h-40 rounded-md border border-other-grey">
        @if($repository_id === -1)
            <div class="flex justify-center items-center h-32 text-primary-blue/70">
                Select a repository
            </div>
        @else
            <div class="grid grid-cols-1 gap-2 xs:grid-cols-2">
                @foreach($milestones as $milestone)
                    @php
                        $maxLength = 12;
                        $shortTitle = \Illuminate\Support\Str::limit($milestone->title, $maxLength, '...');
                    @endphp

                    <a href="/monitors/{{ $milestone->repository->monitor->id }}/milestones/{{ $milestone->id }}" wire:key="{{$milestone->id}}"
                       class="flex gap-2 justify-start items-center p-2.5 mb-2 rounded-md border-2 cursor-pointer border-other-grey">
                        <sl-icon wire:ignore class="text-2xl text-primary-blue" name="flag"></sl-icon>
                        <h1 class="text-xl text-primary-blue font-koulen">{{$shortTitle}}</h1>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
