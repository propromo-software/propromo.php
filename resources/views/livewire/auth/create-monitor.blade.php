<?php

use Livewire\Volt\Component;
use App\Traits\MonitorCreator;

new class extends Component
{
    use MonitorCreator;

    public $create_monitor_error;
    public $error_head;
    public $project_url;
    public $pat_token;
    public $disable_pat_token = true; // New property to manage the checkbox state

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

    function switchTo()
    {
        return $this->redirect('create-open-source-monitor');
    }
}; ?>


<div class="flex flex-col items-center mt-4 bg-gray-100 sm:justify-center sm:pt-0 dark:bg-gray-900">
    <div class="w-full sm:max-w-md mt-6 p-12 bg-white dark:bg-gray-800 border-[1px] border-border-color overflow-hidden sm:rounded-lg">
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <h1 class="text-6xl font-koulen text-primary-blue mb-9">CREATE MONITOR</h1>

                <form wire:submit.prevent="create">
                    <sl-input required wire:model="pat_token" placeholder="Your PAT-Token" type="text"></sl-input>
                    <br>
                    <sl-input required wire:model="project_url" placeholder="Your Project-URL" type="text"></sl-input>
                    <br>
                    <sl-switch wire:click="switchTo()">Open Source</sl-switch>

                    <div class="relative w-full mt-2 aspect-video">
                        <iframe 
                            class="absolute top-0 left-0 w-full h-full rounded-lg"
                            src="https://player.vimeo.com/video/953693369?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479&background=1&responsive=1" 
                            frameborder="0" 
                            allow="autoplay; fullscreen; picture-in-picture; clipboard-write" 
                            title="Propromo Preview">
                        </iframe>
                    </div>

                    <div class="flex items-center justify-between mt-5">
                        <a class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ url('join') }}">
                            Already existing monitor?
                        </a>

                        <sl-button wire:loading.attr="disabled" type="submit">Create</sl-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($create_monitor_error)
        <sl-alert variant="danger" open closable>
            <sl-icon wire:ignore slot="icon" name="patch-exclamation"></sl-icon>
            <strong>{{$error_head}}</strong><br />
            {{$create_monitor_error}}
        </sl-alert>
    @endif

</div>
