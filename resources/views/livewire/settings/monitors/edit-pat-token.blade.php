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

    public $pat_token_edit_error_message;
    public $pat_token_edit_success_message;
    public $error_head;

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
            $this->pat_token_edit_success_message = "Successfully updated the PAT-TOKEN.";
        } catch (Exception $e) {
            $this->pat_token_edit_error_message = $e->getMessage();
            $this->error_head = "Seems like something went wrong...";
        }
    }
}; ?>

<div>
    <form wire:submit="save" class="p-3.5">
        <h1 class="text-primary-blue text-2xl font-koulen text-left">UPDATE PAT-TOKEN</h1>
        <br>
        <select id="customSelect" wire:model="monitor_id" class="block appearance-none w-full bg-white p-3 rounded-md border-other-grey border-2 text-gray-700">
            <option value="">Select an Organization</option> <!-- Default Option -->
            @foreach($monitors as $iterator)
                <option value="{{ $iterator->id }}">{{ $iterator->organization_name }}</option>
            @endforeach
        </select>

        <br>
        <sl-input wire:ignore wire:model="pat_token" type="text" placeholder="PAT-TOKEN"></sl-input>
        <br/>
        <sl-button wire:ignore type="submit" wire:loading.attr="disabled" wire:ignore>Save</sl-button>
    </form>

    @if($pat_token_edit_error_message)
        <sl-alert variant="danger" open closable>
            <sl-icon wire:ignore slot="icon" name="patch-exclamation"></sl-icon>
            <strong>{{$error_head}}</strong><br/>
            {{$pat_token_edit_error_message}}
        </sl-alert>
    @endif

    @if($pat_token_edit_success_message)
        <sl-alert variant="success" open closable>
            <sl-icon wire:ignore slot="icon" name="check-circle"></sl-icon>
            <strong>Success</strong><br/>
            {{$pat_token_edit_success_message}}
        </sl-alert>
    @endif
</div>
