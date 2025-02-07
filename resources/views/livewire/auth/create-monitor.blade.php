<?php

use App\Jobs\CreateMonitor;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Traits\MonitorCreator;

new class extends Component {
    use MonitorCreator;

    public function getListeners()
    {
        return [
            'echo:monitors,MonitorProcessed' => 'handleMonitorUpdated',
        ];
    }

    public $notifications = [];
    public $create_monitor_error;
    public $error_head;
    public $project_url;
    public $pat_token;
    public $disable_pat_token = true;

    protected $rules = [
        'project_url' => 'required|url|min:10|max:2048',
        'pat_token' => 'nullable|string|min:10'
    ];

    public function handleMonitorUpdated()
    {
        Log::info("Handle Monitor entered.");
    }


    public function create()
    {
        if (!Auth::check()) {
            return redirect()->to('/register');
        }

        try {
            $this->validate();

            $project = $this->create_monitor($this->project_url, $this->pat_token);

            return redirect()->to('/monitors/' . $project->id);
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
        CreateMonitor::dispatch($this->project_url, $this->pat_token, $this->disable_pat_token);

    }

    public function switchTo()
    {
        return redirect()->to('/create-open-source-monitor');
    }
};
?>

<div class="flex flex-col items-center mt-4 bg-gray-100 dark:bg-gray-900 sm:justify-center sm:pt-0">
    <div
        class="w-full sm:max-w-md mt-6 p-12 bg-white dark:bg-gray-800 border border-border-color sm:rounded-lg shadow-lg">
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

                <sl-dialog wire:ignore label="Dialog" class="dialog-overview">
                    <sl-button slot="footer" onclick="closeModal()">Open Monitor</sl-button>
                </sl-dialog>

                <sl-button wire:click="on_create">Create</sl-button>

                <script>
                    document.addEventListener("livewire:load", function () {
                        Livewire.on('open-modal', () => {
                            document.querySelector('.dialog-overview').show();
                        });
                    });
                    window.Echo.channel('monitors')
                        .listen('.MonitorProcessed', (event) => {
                            console.log("Received Event: ", event);
                            Livewire.dispatch('handleMonitorUpdated', event);
                        });

                    function closeModal() {
                        document.querySelector('.dialog-overview').hide();
                    }
                </script>


            </div>
        </form>


        <script>
            const dialog = document.querySelector('.dialog-overview');
            const openButton = dialog.nextElementSibling;
            const closeButton = dialog.querySelector('sl-button[slot="footer"]');

            openButton.addEventListener('click', () => dialog.show());
            closeButton.addEventListener('click', () => dialog.hide());
        </script>

        @if($create_monitor_error)
            <sl-alert variant="danger" open closable class="mt-4">
                <sl-icon wire:ignore slot="icon" name="patch-exclamation"></sl-icon>
                <strong>{{ $error_head }}</strong><br/>
                {{ $create_monitor_error }}
            </sl-alert>
        @endif
    </div>
</div>
