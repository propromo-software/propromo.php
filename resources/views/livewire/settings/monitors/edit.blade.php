<?php

use App\Models\Monitor;
use App\Models\User;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $monitors = [];

    public function mount()
    {
        $this->monitors = User::find(Auth::user()->id)->monitors()->get();
    }

    public function leave($monitor_id)
    {
        try {
            $user = Auth::user();
            $user->monitors()->detach($monitor_id);

            $this->monitors = $user->monitors()->get();
            $this->mount();
        } catch (Exception $e) {

        }
    }
}; ?>

<div>
    <div class="grid-cols-1 p-3.5 gap-2">
        <h1 class="text-primary-blue text-2xl font-koulen text-left mb-3">EDIT MONITORS</h1>

        <div class="overflow-auto max-h-24">
            @if(count($monitors) > 0)
                @foreach($monitors as $monitor)
                    <div class="flex justify-between items-center p-3.5 rounded-md border-other-grey border-2 mb-2">
                        <h1 class="text-2xl font-koulen text-primary-blue">{{$monitor->organization_name}}</h1>
                        <sl-icon class="text-2xl text-additional-red cursor-pointer" name="door-open" wire:ignore wire:click="leave({{$monitor->id}})"></sl-icon>
                    </div>
                @endforeach
            @else
                <h1 class="text-xl font-bold text-primary-blue">No Monitors available.</h1>

            @endif
        </div>
    </div>
</div>
