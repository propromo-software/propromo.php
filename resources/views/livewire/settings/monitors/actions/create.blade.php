<?php

use App\Traits\MonitorCreator;
use Livewire\Volt\Component;

new class extends Component {
    use MonitorCreator;

    public $create_monitor_error;
    public $error_head;
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
                $this->create_monitor_error = $e->getMessage();
                $this->error_head = "Seems like something went wrong...";
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
        <sl-button wire:ignore wire:loading.attr="disabled" type="submit">CREATE</sl-button>
    </form>

    @if($create_monitor_error)
        <sl-alert variant="danger" open closable>
            <sl-icon wire:ignore slot="icon" name="patch-exclamation"></sl-icon>
            <strong>{{$error_head}}</strong><br />
            {{$create_monitor_error}}
        </sl-alert>
    @endif

</div>
