<?php

use App\Models\MonitorLogs;
use Livewire\Volt\Component;
use App\Traits\MonitorCreator;

new class extends Component {
    use MonitorCreator;

    public $create_monitor_error;
    public $error_head;
    public $project_url;
    public $pat_token = "use-open-source-program";
    public $disable_pat_token = true;

    protected $rules = [
        'project_url' => 'required|min:10|max:2048'
    ];

    public function create()
    {
        if (Auth::check()) {
            try {
                $this->validate();
                $project = $this->create_monitor($this->project_url, $this->pat_token);
                $monitorLog = MonitorLogs::create([
                    'monitor_id' => $project->id,
                    'status' => 'started',
                    'summary' => 'Initial monitor log created.',
                ]);
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
        return $this->redirect('create-monitor');
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
            <h1 class="mb-8 text-6xl uppercase font-koulen text-primary-blue">Create Monitor</h1>

            <form wire:submit.prevent="create" class="flex flex-col gap-2">
                <sl-input size="medium" required wire:model="project_url" placeholder="Your Project URL"
                          type="text"></sl-input>
                <sl-switch class="pt-1 text-secondary-grey" size="medium" wire:click="switchTo()" checked>Open Source
                </sl-switch>

                <div class="flex justify-between items-end mt-5">
                    <a class="text-sm no-underline text-primary-blue hover:underline"
                       href="{{ url('join') }}">
                        Already have a monitor?
                    </a>

                    <sl-button wire:loading.attr="disabled" type="submit">Create</sl-button>
                </div>
            </form>
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

<!-- <div class="relative mt-2 w-full aspect-video">
    <iframe
        class="absolute top-0 left-0 w-full h-full rounded-lg"
        src="https://player.vimeo.com/video/953693369?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479&background=1&responsive=1"
        frameborder="0"
        allow="autoplay; fullscreen; picture-in-picture; clipboard-write"
        title="Propromo Preview">
    </iframe>
</div> -->
