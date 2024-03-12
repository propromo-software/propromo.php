<?php

use Livewire\Volt\Component;
use App\Traits\ProjectCreator;

new class extends Component {
    use ProjectCreator;

    public $project_url;

    public $pat_token;

    protected $rules = [
        'project_url' => 'required|min:10|max:2048'
    ];

    public function create()
    {
        if (Auth::check()) {
            $this->validate();
            $project = $this->createProject($this->project_url, $this->pat_token);
            return redirect('/projects/' . $project->id);
        } else {
            return redirect('/register');
        }
    }
}; ?>


<div class="mt-4 flex flex-col sm:justify-center items-center sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div>
        <a href="/">
            HOME
        </a>
    </div>

    <div
            class="w-full sm:max-w-md mt-6 p-12 bg-white dark:bg-gray-800 border-[1px] border-border-color overflow-hidden sm:rounded-lg">

        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <h1 class="font-koulen text-6xl text-primary-blue mb-9">CREATE MONITOR</h1>

                <form wire:submit="create">

                    <sl-input wire:ignore wire:model="pat_token" placeholder="Your PAT-Token" type="text"></sl-input>
                    <br>
                    <sl-input wire:ignore wire:model="project_url" placeholder="Your Project-URL"
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
</div>

