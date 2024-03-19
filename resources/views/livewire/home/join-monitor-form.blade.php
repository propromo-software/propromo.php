<?php

use Livewire\Volt\Component;
use App\Models\Monitor;
use Illuminate\Support\Facades\Auth;
use App\Traits\MonitorJoiner;

new class extends Component {
    use MonitorJoiner;

    public $join_monitor_error;
    public $error_head;


    protected $rules = [
        'monitor_hash' => 'required|min:10|max:2048'
    ];
    public $monitor_hash;

    public function submit()
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
    <form wire:submit="submit">
        <label class="text-primary-blue font-koulen text-2xl" for="url">JOIN A PROJECT: </label>
        <br>
        <div class="flex gap-5">
            <sl-input type="text" id="url"
                      placeholder="Here goes the monitor-id"
                      wire:model="monitor_hash"
                      wire:ignore
                      class="w-full"
            >
                <sl-icon name="search" slot="prefix"></sl-icon>
            </sl-input>

            <sl-button type="submit" wire:loading.attr="disabled" wire:ignore>JOIN</sl-button>
        </div>
        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
           href="{{ url('create-monitor') }}">
            No monitor yet?
        </a>
    </form>

    @if($join_monitor_error)
        <sl-alert variant="danger" open closable>
            <sl-icon wire:ignore slot="icon" name="patch-exclamation"></sl-icon>
            <strong>{{$error_head}}</strong><br/>
            {{$join_monitor_error}}
        </sl-alert>
    @endif


</div>
