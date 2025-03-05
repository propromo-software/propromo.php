<?php

use Livewire\Volt\Component;

new class extends Component {
    public int $monitor_id;

    function open_monitor()
    {
        return redirect()->to('/monitors/' . $project->id);
    }
}; ?>

<div>
    <sl-dialog id="creationDialog" label="Console Output" no-header>
        <div class="w-96 h-48 bg-black text-lime-400 font-mono p-2 rounded overflow-y-auto border border-gray-700">
            <div class="whitespace-pre-wrap"></div>
        </div>
        <sl-button id="openMonitor" slot="footer" variant="primary" disabled>Open</sl-button>
    </sl-dialog>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dialog = document.getElementById('creationDialog');
            let monitorLogMessages = [];
            let redirectUrl = "";

            document.addEventListener('livewire:init', () => {
                Livewire.on('monitor-creation-called', (event) => {
                    const openMonitorButton = document.getElementById("openMonitor");
                    console.log('Success alert event received:');
                    dialog.show();
                });

                Livewire.on('monitor-log-sent', (data) => {
                    console.log('Received message:', data[0].message);

                    const logContainer = document.querySelector('.w-96.h-48.bg-black');

                    if (logContainer) {
                        monitorLogMessages.unshift(data[0].message);

                        logContainer.innerHTML = '';

                        monitorLogMessages.forEach((message) => {
                            const logEntry = document.createElement('div');
                            logEntry.className = 'whitespace-pre-wrap';
                            logEntry.textContent = message;
                            logContainer.appendChild(logEntry);
                        });

                        logContainer.scrollTop = 0;
                    }
                });

                Livewire.on('monitor-created', (data) => {
                    const openMonitorButton = document.getElementById("openMonitor");
                    console.log(data[0].monitorId)
                    openMonitorButton.disabled = false;
                    redirectUrl = '/monitors/' + data[0].monitorId;

                    openMonitorButton.addEventListener('click', () => {
                        window.location.href = redirectUrl;
                    });
                })
            });
        });
    </script>

</div>
