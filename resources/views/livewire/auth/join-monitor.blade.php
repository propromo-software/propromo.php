<?php

use Livewire\Volt\Component;
use App\Traits\ProjectJoiner;

new class extends Component {
    use ProjectJoiner;

    public $project_hash;

    public function join()
    {
        if (Auth::check()) {

            $project = $this->joinProject($this->project_hash);
            return redirect('/projects/'. $project->id);
        }else{
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
                <h1 class="font-koulen text-6xl text-primary-blue mb-9">JOIN MONITOR</h1>

                <form wire:submit="join">

                    <sl-input wire:ignore wire:model="project_hash" placeholder="Project-Id" type="text"></sl-input>
                    <br>
                    <iframe class="mt-2" width="100%" height="100"
                            src="https://youtu.be/SXmJH72-O5g?autoplay=1">
                    </iframe>

                    <div class="flex items-center justify-between mt-5">
                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                           href="{{ url('create-monitor') }}">
                            No monitor yet?
                        </a>

                        <sl-button wire:ignore wire:loading.attr="disabled" type="submit">JOIN</sl-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
