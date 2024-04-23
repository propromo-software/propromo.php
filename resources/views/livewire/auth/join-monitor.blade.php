<?php

use Livewire\Volt\Component;
use App\Traits\MonitorJoiner;

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


<div class="flex flex-col items-center mt-4 bg-gray-100 sm:justify-center sm:pt-0 dark:bg-gray-900">
    <div
        class="w-full sm:max-w-md mt-6 p-12 bg-white dark:bg-gray-800 border-[1px] border-border-color overflow-hidden sm:rounded-lg">

        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <h1 class="text-6xl font-koulen text-primary-blue mb-9">JOIN MONITOR</h1>

                <form wire:submit="join">

                    <sl-input wire:ignore wire:model="monitor_hash" placeholder="Project-Id" type="text"></sl-input>

                    <br>

                    <iframe width="100%" height="315" src="https://www.youtube-nocookie.com/embed/SXmJH72-O5g?si=if48WPOjUBCZZyi4&amp;controls=0&amp;autoplay=1&amp;loop=1&amp;mute=1&amp;showinfo=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

                    <div class="flex items-center justify-between mt-5">
                        <a class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                           href="{{ url('create-monitor') }}">
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
            <strong>{{$error_head}}</strong><br/>
            {{$join_monitor_error}}
        </sl-alert>
    @endif

</div>
