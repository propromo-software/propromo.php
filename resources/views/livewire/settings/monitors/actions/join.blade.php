<?php

use App\Traits\MonitorJoiner;
use Livewire\Volt\Component;

new class extends Component {
    use MonitorJoiner;

    public $monitor_hash;

    public function join()
    {
        if (Auth::check()) {
            try {
                $monitor = $this->join_monitor($this->monitor_hash);
                return redirect('/monitors/' . $monitor->id);
            } catch (Exception $e) {
                $message = $e->getMessage();
                logger()->error('Join Monitor Error', ['message' => $message]);

                $this->dispatch('show-error-alert', [
                    'head' => 'Join Monitor Error',
                    'message' => 'Something unexpected happened!'
                ]);
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
</div>
