<?php

use App\Models\Monitor;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    #[Validate(['pat_token' => 'required'])]
    public string $pat_token = "";
    #[Validate(['monitor_id' => 'required'])]
    public $monitor_id= 0;
    public $monitors;

    public function mount()
    {
        $this->monitors = User::find(Auth::user()->id)->monitors()->get();
    }

    public function save()
    {
        try {
            $this->validate();
            $monitor = Monitor::whereId($this->monitor_id)->first();
            $monitor->pat_token = $this->pat_token;
            $monitor->save();

            $this->dispatch('show-error-alert', [
                'head' => 'PAT saved',
                'message' => 'Successfully updated the PAT.'
            ]);
        } catch (Exception $e) {
            $message = $e->getMessage();
            logger()->error('Save PAT Error', ['message' => $message]);

            $this->dispatch('show-error-alert', [
                'head' => 'Save PAT Error',
                'message' => 'Something unexpected happened!'
            ]);
        }
    }
}; ?>

<div>
    <form wire:submit="save" class="p-3.5">
        <h1 class="text-2xl text-left text-primary-blue font-koulen">UPDATE PAT-TOKEN</h1>
        <br>
        <select id="customSelect" wire:model="monitor_id" class="block p-3 w-full text-gray-700 bg-white rounded-md border-2 appearance-none border-other-grey">
            <option value="">Select an Organization</option> <!-- Default Option -->
            @foreach($monitors as $iterator)
                <option value="{{ $iterator->id }}">{{ $iterator->organization_name }}</option>
            @endforeach
        </select>

        <br>
        <sl-input wire:ignore wire:model="pat_token" type="text" placeholder="PAT-TOKEN"></sl-input>
        <br/>
        <div class="[&_sl-button::part(base)]:w-full">
            <sl-button wire:ignore type="submit" variant="default" wire:loading.attr="disabled">
                <sl-icon slot="prefix" name="check-lg" class="text-base"></sl-icon>
                Save Changes
            </sl-button>
        </div>
    </form>
</div>
