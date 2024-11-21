<?php

use App\Traits\MonitorJoiner;
use Livewire\Volt\Component;

new class extends Component {

    use MonitorJoiner;

    public $join_monitor_error;
    public $error_head;

    public $monitor_hash;

    public function join()
    {
        if (Auth::check()) {
            try {
                $monitor = $this->join_monitor($this->monitor_hash);
                return redirect('/monitors/' . $monitor->id);
            } catch (Exception $e) {
                $this->join_monitor_error = $e->getMessage();
                $this->error_head = "Seems like something went wrong...";
            }
        } else {
            return redirect('/register');
        }
    }
}; ?>

<div>
    <form wire:submit="join" class="p-3.5">
        <sl-input wire:ignore wire:model="monitor_hash" type="text" placeholder="Monitor-Hash"></sl-input>
        <br/>
        <sl-button wire:ignore type="submit" wire:loading.attr="disabled" wire:ignore>JOIN</sl-button>
    </form>

    @if($join_monitor_error)
        <sl-alert variant="danger" open closable>
            <sl-icon wire:ignore slot="icon" name="patch-exclamation"></sl-icon>
            <strong>{{$error_head}}</strong><br />
            {{$join_monitor_error}}
        </sl-alert>
    @endif
</div>
