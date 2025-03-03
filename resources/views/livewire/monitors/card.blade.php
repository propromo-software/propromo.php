<?php

use Livewire\Volt\Component;
use App\Models\Monitor;
use App\Traits\RepositoryCollector;

new class extends Component {
    use RepositoryCollector;

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
            $message = $e->getMessage();
            logger()->error('Failed to load repositories Error', ['message' => $message]);

            $this->dispatch('show-error-alert', [
                'head' => 'Failed to load repositories Error',
                'message' => 'Something unexpected happened!'
            ]);
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

<div class="items-center p-5 w-full rounded-xl">
    <div class="flex justify-between items-center mb-5">

        @if(!$log_error)
        <a class="flex gap-2 items-center px-6 py-3 text-lg font-bold rounded-md border-2 text-secondary-grey font-sourceSansPro border-other-grey" href="/monitors/{{ $monitor->id }}" title="Show Monitor">
            {{ strtoupper($monitor->type == 'USER' ? $monitor->login_name : $monitor->organization_name) }} / {{ strtoupper($monitor->title) }}
        </a>
        @else
            <a class="flex gap-2 items-center px-6 py-3 text-lg font-bold rounded-md border-2 text-additional-red font-sourceSansPro border-additional-red" href="/monitors/{{ $monitor->id }}" title="Show Monitor">
                {{ strtoupper($monitor->type == 'USER' ? $monitor->login_name : $monitor->organization_name) }} / {{ strtoupper($monitor->title) }}
            </a>
        @endif

        <div class="flex gap-2 items-center">
            @if($log_error)
                <button wire:click="openErrorLog" class="flex gap-1 items-center px-2 py-2 bg-red-500 rounded-md text-additional-red">
                    <sl-icon  class="text-4xl" name="bug"></sl-icon>
                </button>
            @endif
            <sl-icon-button class="text-5xl text-secondary-grey" name="arrow-repeat" label="Reload" type="submit" wire:ignore wire:click="reload_repositories"></sl-icon-button>
        </div>
    </div>
    <livewire:repositories.list :monitor_id="$monitor->id" />
</div>
