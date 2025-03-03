<?php

use App\Traits\MonitorCreator;
use Livewire\Volt\Component;

new class extends Component {
    use MonitorCreator;

    public $project_url;
    public $pat_token;
    public $disable_pat_token = true;

    protected $rules = [
        'project_url' => 'required|min:10|max:2048'
    ];

    public function create()
    {
        if (Auth::check()) {
            try {
                $this->validate();
                if(empty($this->pat_token)){
                    $this->pat_token = "use-open-source-program";
                }
                $project = $this->create_monitor($this->project_url, $this->pat_token);
                return redirect('/monitors/' . $project->id);
            } catch (Exception $e) {
                $message = $e->getMessage();
                logger()->error('Create Monitor Error', ['message' => $message]);

                $this->dispatch('show-error-alert', [
                    'head' => 'Create Monitor Error',
                    'message' => 'Something unexpected happened!'
                ]);
            }
        } else {
            return redirect('/register');
        }
    }

}; ?>

<div>
    <form wire:submit.prevent="create" class="p-3">
        <sl-input wire:ignore  wire:model="pat_token" placeholder="Your PAT-Token" type="text"></sl-input>
        <br>
        <sl-input wire:ignore required wire:model="project_url" placeholder="Your Project-URL" type="text"></sl-input>
        <br>
        <div class="[&_sl-button::part(base)]:w-full">
            <sl-button type="submit" wire:loading.attr="disabled" wire:ignore>
                <sl-icon slot="prefix" name="plus-lg" class="text-base"></sl-icon>
                Create Monitor
            </sl-button>
        </div>
    </form>
</div>
