<?php

use App\Jobs\CreateMonitor;
use App\Models\Monitor;
use App\Models\MonitorLogEntries;
use App\Models\MonitorLogs;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Traits\MonitorCreator;

new class extends Component {
    use MonitorCreator;

    public $notifications = [];
    public $create_monitor_error;
    public $error_head;
    public $project_url;
    public $pat_token;
    public $disable_pat_token = true;
    public $monitor = null;
    public $logs = [];
    public $latest_monitor_log_id = null;

    protected $rules = [
        'project_url' => 'required|url|min:10|max:2048',
        'pat_token' => 'required|string|min:10'
    ];

    #[On('echo:monitors,MonitorProcessed')]
    public function handleMonitorUpdated()
    {
        Log::info("Handle Monitor entered.");
    }


    public function create()
    {
        $monitorLog = null;
        if (!Auth::check()) {
            return redirect()->to('/register');
        }

        try {
            $this->validate();
            $this->monitor = $this->create_monitor($this->project_url, $this->pat_token);
            $monitorLog = MonitorLogs::create([
                'monitor_id' => $this->monitor->id,
                'status' => 'started',
                'summary' => 'Initial monitor log created.',
            ]);
            MonitorLogEntries::create([
                'monitor_log_id' => $monitorLog->id,
                'message' => 'Monitoring Creator Job initiated and monitor created successfully.',
                'level' => 'info',
                'context' => [
                    'project_url' => $this->project_url,
                    'pat_token' => $this->pat_token ? 'Provided' : 'Not Provided',
                    'disable_pat_token' => $this->disable_pat_token,
                ],
            ]);

            //return redirect()->to('/monitors/' . $project->id);
        } catch (ValidationException $e) {
            $this->create_monitor_error = "Invalid input: " . implode(", ", $e->validator->errors()->all());
            $this->error_head = "Validation Failed!";
        } catch (Exception $e) {
            $this->create_monitor_error = $e->getMessage();
            $this->error_head = "Something went wrong...";
        }
    }

    public function on_create()
    {
        $this->create();
        if ($this->monitor != null) {
            CreateMonitor::dispatch($this->monitor);
            $latestMonitorLog = Monitor::whereId($this->monitor->id)
                ->first()
                ->monitor_logs()
                ->latest()
                ->first();
            if ($latestMonitorLog) {
                $this->latest_monitor_log_id = $latestMonitorLog->id;
            }
            Log::info("JOB DISPATCHED");
        }
    }

    public function pollLogs()
    {
        if ($this->latest_monitor_log_id) {
            $this->logs = MonitorLogEntries::where('monitor_log_id', $this->latest_monitor_log_id)
                ->latest()
                ->take(20)
                ->get()
                ->reverse() // Reverse collection order
                ->toArray();
            $this->dispatch('updateLogs');
        }
    }

    public function switchTo()
    {
        return redirect()->to('/create-open-source-monitor');
    }
}
?>
<div class="flex flex-col items-center mt-4 bg-gray-100 dark:bg-gray-900 sm:justify-center sm:pt-0">
    <div class="w-full sm:max-w-4xl mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">

        <!-- Create Monitor Form -->
        <div class="p-12 bg-white dark:bg-gray-800 border border-border-color sm:rounded-lg shadow-lg">
            <div class="text-center">
                <h1 class="mb-6 text-4xl font-bold text-primary-blue">Create Monitor</h1>
            </div>

            <form wire:submit.prevent="create" class="space-y-4">
                <sl-input required wire:model.defer="project_url" placeholder="Your Project URL" type="text"></sl-input>
                <br>
                <sl-input wire:model.defer="pat_token" placeholder="Your PAT Token (Optional)" type="text"></sl-input>
                <sl-switch wire:click="switchTo()">Open Source</sl-switch>

                <div class="flex justify-between items-center mt-4">
                    <a class="text-sm text-gray-600 underline dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100"
                       href="{{ url('join') }}">
                        Already have a monitor?
                    </a>
                    <sl-button wire:click="on_create">Create</sl-button>
                </div>
            </form>

            @if($create_monitor_error)
                <sl-alert variant="danger" open closable class="mt-4">
                    <sl-icon wire:ignore slot="icon" name="patch-exclamation"></sl-icon>
                    <strong>{{ $error_head }}</strong><br/>
                    {{ $create_monitor_error }}
                </sl-alert>
            @endif
        </div>

        <div class="p-4 bg-white dark:bg-gray-800 border border-border-color sm:rounded-lg shadow-lg">
            <h2 class="text-lg font-semibold mb-2">Monitor Logs</h2>
            <div wire:poll.100ms="pollLogs" class="h-64 overflow-y-auto p-2 rounded-md">
                <ul class="space-y-1">
                    @foreach ($logs as $log)
                        <li class="text-sm p-1 rounded-md
                                {{ $log['level'] === 'error' ? 'text-additional-red' :
                                ($log['level'] === 'warning' ? 'text-additional-orange' :
                                ($log['level'] === 'success' ? 'text-additional-green' : 'bg-gray-700')) }}">
                            <strong>[{{ strtoupper($log['level']) }}]</strong> {{ $log['message'] }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <script>
            window.addEventListener('updateLogs', function () {
                const logContainer = document.getElementById('log-container');
                logContainer.scrollTop = logContainer.scrollHeight; // Auto-scroll to latest log
            });
        </script>
    </div>
</div>

