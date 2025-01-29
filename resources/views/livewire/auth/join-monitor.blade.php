<?php

use Livewire\Volt\Component;
use App\Traits\MonitorJoiner;

new class extends Component
{
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


<div class="flex flex-col items-center mt-4 bg-gray-100 sm:justify-center sm:pt-0 dark:bg-gray-900">
    <div class="w-full sm:max-w-md mt-6 p-12 bg-white dark:bg-gray-800 border-[1px] border-border-color sm:rounded-lg">

        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <h1 class="text-6xl font-koulen text-primary-blue mb-9">JOIN MONITOR</h1>

                <form wire:submit="join">

                    <sl-input wire:ignore wire:model="monitor_hash" placeholder="Project-Id" type="text"></sl-input>

                    <br>

                    <!-- <div class="relative w-full mt-2 aspect-video">
                        <iframe 
                            class="absolute top-0 left-0 w-full h-full rounded-lg"
                            src="https://player.vimeo.com/video/953693432?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479&background=1&responsive=1" 
                            frameborder="0" 
                            allow="autoplay; fullscreen; picture-in-picture; clipboard-write" 
                            title="Propromo Preview">
                        </iframe>
                    </div> -->

                    <div class="flex items-center justify-between mt-5">
                        <a class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ url('create-monitor') }}">
                            No monitor yet?
                        </a>

                        <sl-button wire:ignore wire:loading.attr="disabled" type="submit">JOIN</sl-button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @if($join_monitor_error)
    <sl-alert variant="danger" open closable>
        <sl-icon wire:ignore slot="icon" name="patch-exclamation"></sl-icon>
        <strong>{{$error_head}}</strong><br />
        {{$join_monitor_error}}
    </sl-alert>
    @endif

</div>