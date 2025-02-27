<?php

use Livewire\Volt\Component;
use App\Models\Monitor;
use App\Traits\RepositoryCollector;

new class extends Component
{
    use RepositoryCollector;

    public $collect_repos_error;
    public $error_head;
    public $log_error = false;
    public Monitor $monitor;

    public function mount(Monitor $monitor): void
    {
        try {
            if ($monitor->repositories()->get()->isEmpty()) {
                $this->collect_repositories($monitor);
            }
            $this->monitor = $monitor;
            $this->dispatch('monitor-hash-changed', $this->monitor->monitor_hash);
            $firstLog = $this->monitor
                ->monitor_logs()
                ->orderBy('created_at', 'desc')
                ->latest()
                ->first();
            $hasError = false;

            if ($firstLog) {

               // dd($firstLog);
                foreach ($firstLog->monitorLogEntries() as $log) {
                    if ($log->level == 'error') {
                        $hasError = true;
                        break;
                    }
                }
                if ($hasError) {
                    $this->log_error = true;
                }
            }

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

        @if(!$log_error)
        <a class="text-secondary-grey text-lg font-sourceSansPro font-bold rounded-md border-2 border-other-grey px-6 py-3 flex items-center gap-2" href="/monitors/{{ $monitor->id }}" title="Show Monitor">
            {{ strtoupper($monitor->type == 'USER' ? $monitor->login_name : $monitor->organization_name) }} / {{ strtoupper($monitor->title) }}
        </a>
        @else
            <a class="text-additional-red text-lg font-sourceSansPro font-bold rounded-md border-2 border-additional-red px-6 py-3 flex items-center gap-2" href="/monitors/{{ $monitor->id }}" title="Show Monitor">
                {{ strtoupper($monitor->type == 'USER' ? $monitor->login_name : $monitor->organization_name) }} / {{ strtoupper($monitor->title) }}
            </a>
        @endif

        <div class="flex items-center gap-2">
            @if($log_error)
                <button wire:click="openErrorLog" class="bg-red-500 text-additional-red px-2 py-2 rounded-md flex items-center gap-1">
                    <sl-icon  class="text-4xl" name="bug"></sl-icon>
                </button>
            @endif
            <sl-icon-button class="text-5xl text-secondary-grey" name="arrow-repeat" label="Reload" type="submit" wire:ignore wire:click="reload_repositories"></sl-icon-button>
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
