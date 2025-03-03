<?php

use Livewire\Volt\Component;
use App\Models\Monitor;
use Illuminate\Support\Facades\Auth;
use App\Traits\MonitorJoiner;

new class extends Component {
    use MonitorJoiner;

    protected $rules = [
        'monitor_hash' => 'required|min:10|max:2048'
    ];
    public $monitor_hash;

    public function submit()
    {
        if (Auth::check()) {
            try {
                if ($this->monitor_hash === null) {
                    $message = $e->getMessage();
                    logger()->error('Join Monitor Error', ['message' => $message]);

                    $this->dispatch('show-error-alert', [
                        'head' => 'Join Monitor Error',
                        'message' => 'You have to input a monitor hash first!'
                    ]);
                }

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
    <form wire:submit="submit">
        <label class="text-2xl text-primary-blue font-koulen text-uppercase" for="url">Join a project: </label>
        <br>
        <div class="flex gap-2">
            <sl-input type="text" id="url"
                      placeholder="Here goes the monitor-id"
                      wire:model="monitor_hash"
                      wire:ignore
                      class="w-full"
            >
                <sl-icon name="search" slot="prefix"></sl-icon>
            </sl-input>

            <sl-button type="submit" wire:loading.attr="disabled" wire:ignore>Join</sl-button>
        </div>
        <a class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
           href="{{ url('create-monitor') }}">
            No monitor yet?
        </a>
    </form>
</div>
