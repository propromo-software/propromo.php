<?php

use App\Models\Monitor;
use Livewire\Volt\Component;

new class extends Component {
    public Monitor $monitor;

    public function mount($monitor)
    {
        $this->monitor = $monitor;
    }

    public function emit_repository_change($milestone_id)
    {
        $this->dispatch('repository_changed', $milestone_id);
    }
}; ?>

<div class="w-full mb-5 lg:mb-0 lg:w-1/2">
    <h2 class="m-2 text-2xl font-koulen text-primary-blue">Repositories</h2>
    <div class="m-2 overflow-auto h-40 border border-other-grey rounded-md p-2.5">
        <div class="grid grid-cols-1 gap-2 xs:grid-cols-2">
            @foreach($monitor->repositories as $repository)
                @php
                    $maxLength = 12;
                    $shortName = \Illuminate\Support\Str::limit($repository->name, $maxLength, '...');
                @endphp

                <div wire:click="emit_repository_change({{$repository->id}})" wire:key="{{$repository->id}}" class="cursor-pointer flex border-other-grey border-2 rounded-md p-2.5 justify-start gap-2 items-center mb-2">
                    <sl-icon wire:ignore class="text-2xl text-primary-blue" name="code-square"></sl-icon>
                    <h1 class="text-xl text-primary-blue font-koulen">{{$shortName}}</h1>
                </div>
            @endforeach
        </div>
    </div>
</div>
