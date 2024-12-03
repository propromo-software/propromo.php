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

        $this->milestones = $milestones ;
    }
};
?>

<div class="w-full lg:w-1/2">
    <h2 class="m-2 text-2xl font-koulen text-primary-blue">Milestones</h2>
    <div class="m-2 overflow-auto h-40 border border-other-grey rounded-md p-2.5">
        <div class="grid grid-cols-1 gap-2 xs:grid-cols-2">
            @foreach($milestones as $milestone)
                @php
                    $maxLength = 12;
                    $shortTitle = \Illuminate\Support\Str::limit($milestone->title, $maxLength, '...');
                @endphp

                <a href="/monitors/{{ $milestone->repository->monitor->id }}/milestones/{{ $milestone->id }}" wire:key="{{$milestone->id}}"
                   class="cursor-pointer flex border-other-grey border-2 rounded-md p-2.5 justify-start gap-2 items-center mb-2">
                    <sl-icon wire:ignore class="text-2xl text-primary-blue" name="flag"></sl-icon>
                    <h1 class="text-xl text-primary-blue font-koulen">{{$shortTitle}}</h1>
                </a>
            @endforeach
        </div>
    </div>
</div>
