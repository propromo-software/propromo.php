<?php

use Livewire\Volt\Component;
use App\Traits\MonitorJoiner;

new class extends Component
{
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

<div class="flex flex-col items-center w-full h-full bg-gray-100">
    <div class="flex flex-col justify-center mt-10">
        <div class="flex gap-1 items-center mt-16 mb-2 sm:mt-10">
            <div class="w-[30px] h-[30px] rounded-full bg-primary-blue"></div>
            <div class="w-2 h-1 bg-primary-blue"></div>
            <div class="w-[30px] h-[30px] bg-primary-blue rounded-full"></div>
        </div>

        <div class="px-10 pt-8 pb-8 mx-auto w-96 max-w-full bg-white rounded-lg border border-border-color">
            <h1 class="mb-8 text-6xl uppercase font-koulen text-primary-blue">Join Monitor</h1>

            <form wire:submit="join" class="flex flex-col gap-2">
                <sl-input size="medium" required wire:ignore wire:model="monitor_hash" placeholder="Project-Id" type="text"></sl-input>

                <div class="flex justify-between items-end mt-5">
                    <a class="text-sm no-underline text-primary-blue hover:underline"
                        href="{{ url('create-monitor') }}">
                        No monitor yet?
                    </a>

                    <sl-button size="medium" wire:ignore wire:loading.attr="disabled" type="submit">Join</sl-button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <div class="relative mt-2 w-full aspect-video">
    <iframe 
        class="absolute top-0 left-0 w-full h-full rounded-lg"
        src="https://player.vimeo.com/video/953693432?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479&background=1&responsive=1" 
        frameborder="0" 
        allow="autoplay; fullscreen; picture-in-picture; clipboard-write" 
        title="Propromo Preview">
    </iframe>
</div> -->
