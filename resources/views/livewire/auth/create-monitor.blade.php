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

            $project = $this->create_monitor($this->project_url, $this->pat_token);

            return redirect()->to('/monitors/' . $project->id);
        } catch (ValidationException $e) {
            $message = implode(", ", $e->validator->errors()->all());
            logger()->error('Create Monitor Error', ['errors' => $message]);

            $this->dispatch('show-error-alert', [
                'head' => 'Create Monitor Error',
                'message' => $message
            ]);
        } catch (Exception $e) {
            $message = $e->getMessage();
            logger()->error('Create Monitor Error', ['message' => $message]);

            $this->dispatch('show-error-alert', [
                'head' => 'Create Monitor Error',
                'message' => 'Something unexpected happened!'
            ]);
        }

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
                ->reverse()
                ->toArray();
            $this->dispatch('updateLogs');
        }
    }

    public function switchTo()
    {
        return redirect()->to('/create-open-source-monitor');
    }
};
?>

<div class="flex relative flex-col items-center w-full h-full bg-gray-100">
    <div class="flex flex-col justify-center mt-10">
        <div class="flex gap-1 items-center mt-16 mb-2 sm:mt-10">
            <div class="w-[30px] h-[30px] rounded-full bg-primary-blue"></div>
            <div class="w-2 h-1 bg-primary-blue"></div>
            <div class="w-[30px] h-[30px] bg-primary-blue rounded-full"></div>
        </div>

        <div class="px-10 pt-8 pb-8 mx-auto w-96 max-w-full bg-white rounded-lg border border-border-color">
            <h1 class="mb-8 text-6xl uppercase font-koulen text-primary-blue">Create Monitor</h1>

            <form wire:submit="create" class="flex flex-col gap-2">
                <sl-input size="medium" required wire:model.defer="project_url" placeholder="Your Project URL" type="text"></sl-input>
                <sl-input size="medium" wire:model.defer="pat_token" placeholder="Your PAT Token (Optional)" type="text"></sl-input>
                <sl-switch class="pt-1 text-secondary-grey" size="medium" wire:click="switchTo()">Open Source</sl-switch>

                <div class="flex justify-between items-end mt-5">
                    <a class="text-sm no-underline text-primary-blue hover:underline"
                        href="{{ url('join') }}">
                        Already have a monitor?
                    </a>

                    <sl-button type="submit" wire:loading.attr="disabled" wire:ignore size="medium">Create</sl-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.addEventListener('updateLogs', function () {
            const logContainer = document.getElementById('log-container');
            logContainer.scrollTop = logContainer.scrollHeight;
        });
    </script>
</div>
