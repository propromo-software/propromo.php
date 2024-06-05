<?php

use Livewire\Volt\Component;
use App\Models\Monitor;
use App\Traits\RepositoryCollector;

new class extends Component
{
    use RepositoryCollector;

    public $collect_repos_error;
    public $error_head;

    public Monitor $monitor;

    public function mount(Monitor $monitor): void
    {
        try {
            if ($monitor->repositories()->get()->isEmpty()) {
                $this->collect_repositories($monitor);
            }
            $this->monitor = $monitor;
            $this->dispatch('monitor-hash-changed', $this->monitor->monitor_hash);
        } catch (Exception $e) {
            $this->collect_repos_error = $e->getMessage();
            $this->error_head = "Seems like something went wrong...";
        }
    }

    public function reload_repositories()
    {
        $this->monitor->repositories()->delete();
        $this->collect_repositories($this->monitor);

        $this->dispatch("repositories-updated", monitor_id: $this->monitor->id);
    }

    public function get_repositories()
    {
        return $this->monitor->repositories()->get();
    }

    /*
        public function placeholder()
        {
            return <<<'HTML'
            <center class="p-10" wire:key="{{ $monitor->id }}">
                <sl-spinner class="text-7xl" style="--track-width: 9px;"></sl-spinner>
            </center>
            HTML;
        }
    */

};
?>

<div class="w-full p-5 items-center rounded-xl">
    <div class="flex items-center justify-between mb-5">
        <a  class="text-secondary-grey text-lg font-sourceSansPro font-bold rounded-md border-2 border-other-grey px-6 py-3" href="/monitors/{{ $monitor->id }}" title="Show Monitor">
            {{ strtoupper($monitor->type == 'USER' ? $monitor->login_name : $monitor->organization_name) }} / {{ strtoupper($monitor->title) }}
        </a>

        <div class="flex items-center gap-2">
            <a class="flex items-center gap-1 rounded-md border-2 border-other-grey px-6 py-3" wire:click="fireMonitorHashChangedEvent" href="/monitors/{{ $monitor->id }}" title="Show User">
                <sl-icon wire:ignore class="text-secondary-grey font-sourceSansPro text-xl font-bold" name="chat"></sl-icon>
                <div>
                    <div class="text-secondary-grey font-sourceSansPro text-lg font-bold">
                        CONTACT
                    </div>
                </div>
            </a>

            <sl-icon-button class="text-3xl text-secondary-grey" name="arrow-repeat" label="Reload" type="submit" wire:ignore wire:click="reload_repositories"></sl-icon-button>
        </div>
    </div>
    <livewire:repositories.list :monitor_id="$monitor->id" />


    @if($collect_repos_error)
        <sl-alert variant="danger" open closable>
            <sl-icon wire:ignore slot="icon" name="patch-exclamation"></sl-icon>
            <strong>{{$error_head}}</strong><br />
            {{$collect_repos_error}}
        </sl-alert>
    @endif

</div>
