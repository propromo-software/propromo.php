<?php

use Livewire\Volt\Component;
use App\Traits\MonitorCreator;

new class extends Component {
    use MonitorCreator;

    public $create_monitor_error;

    public $error_head;

    public $project_url;

    public $pat_token;

    protected $rules = [
        'project_url' => 'required|min:10|max:2048'
    ];

    public function create()
    {
        if (Auth::check()) {
            try {
                $this->validate();
                $project = $this->create_monitor($this->project_url, $this->pat_token);
                return redirect('/monitors/' . $project->id);
            } catch (Exception $e) {
                $this->create_monitor_error = $e->getMessage();
                $this->error_head = "Seems like something went wrong...";
            }
        } else {
            return redirect('/register');
        }
    }
}; ?>


<div class="mt-4 flex flex-col sm:justify-center items-center sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div
        class="w-full sm:max-w-md mt-6 p-12 bg-white dark:bg-gray-800 border-[1px] border-border-color overflow-hidden sm:rounded-lg">

        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <h1 class="font-koulen text-6xl text-primary-blue mb-9">CREATE MONITOR</h1>

                <form wire:submit="create">

                    <sl-input required wire:ignore wire:model="pat_token" placeholder="Your PAT-Token"
                              type="text"></sl-input>
                    <br>
                    <sl-input required wire:ignore wire:model="project_url" placeholder="Your Project-URL"
                              type="text"></sl-input>
                    <br>

                    <iframe class="mt-2" width="100%" height="100"
                            src="https://youtu.be/SXmJH72-O5g?autoplay=1">
                    </iframe>

                    <div class="flex items-center justify-between mt-5">
                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                           href="{{ url('join') }}">
                            Already existing monitor?
                        </a>

                        <sl-button wire:loading.attr="disabled" wire:ignore type="submit">Create</sl-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($create_monitor_error)
        <sl-alert variant="danger" open closable>
            <sl-icon wire:ignore slot="icon" name="patch-exclamation"></sl-icon>
            <strong>{{$error_head}}</strong><br/>
            {{$create_monitor_error}}
        </sl-alert>
    @endif

</div>

