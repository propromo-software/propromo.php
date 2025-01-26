<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component {
    public $monitor_hash = null;

    public function mount($monitor_hash = null)
    {
        $this->$monitor_hash = $monitor_hash;
    }

    #[On('monitor-hash-changed')]
    public function updateMonitorHash($monitor_hash)
    {
        $this->monitor_hash = $monitor_hash;
        $this->mount($monitor_hash);
    }
}; ?>

<div>
    <div class="flex gap-4 items-center">
        @if($monitor_hash)
            <label for="monitor_hash" class="sr-only">Monitor Hash</label>
            <input id="monitor_hash"
                   type="text"
                   value="{{$monitor_hash}}"
                   disabled
                   class="px-4 py-2 text-sm bg-transparent border-none text-primary-blue/70"/>

            <x-bi-copy onclick="copyToClipboard('{{url('/')}}/monitors/join/{{ $monitor_hash }}')"
                       id="copyIcon"
                       name="copy"
                       class="text-xl cursor-pointer text-primary-blue/70 hover:text-primary-blue"
                       from="monitor_hash"/>

            <x-bi-check id="checkIcon"
                        class="hidden text-xl cursor-pointer text-primary-blue/70"/>

            <!-- QR Code Button -->
            <sl-icon-button wire:ignore name="qr-code" id="qr-button" label="QR Code"></sl-icon-button>

            <!-- QR Dialog -->
            <sl-dialog wire:ignore class="qr-dialog" label="Monitor QR Code">
                <div class="flex flex-col items-center p-2 bg-white">
                    <sl-qr-code wire:ignore 
                                value="{{url('/')}}/monitors/join/{{ $monitor_hash }}" 
                                size="200"
                                background="white"
                                foreground="black"
                                radius="0">
                    </sl-qr-code>
                </div>
                <div slot="footer" class="w-full">
                    <sl-button wire:ignore 
                               slot="footer" 
                               variant="primary" 
                               class="w-full">
                        Close
                    </sl-button>
                </div>
            </sl-dialog>
        @else
            <sl-spinner class="text-xl text-primary-blue/70"></sl-spinner>
        @endif

        <div class="w-px h-6 bg-primary-blue/20"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const initDialog = () => {
                const dialog = document.querySelector('.qr-dialog');
                const openButton = document.getElementById('qr-button');

                if (!dialog || !openButton) {
                    setTimeout(initDialog, 100);
                    return;
                }

                openButton.addEventListener('click', () => {
                    dialog.show();
                });

                const closeButton = dialog.querySelector('sl-button[slot="footer"]');
                closeButton.addEventListener('click', () => {
                    dialog.hide();
                });
            };

            initDialog();
        });

        function copyToClipboard(text) {
            let copyIcon = document.getElementById("copyIcon");
            let checkIcon = document.getElementById("checkIcon");

            let monitorHash = document.createElement("textarea");
            monitorHash.textContent = text;
            document.body.appendChild(monitorHash);
            monitorHash.select();
            document.execCommand("copy");
            document.body.removeChild(monitorHash);

            copyIcon.classList.add('hidden');
            checkIcon.classList.remove('hidden');

            setTimeout(function() {
                checkIcon.classList.add('hidden');
                copyIcon.classList.remove('hidden');
            }, 500);
        }
    </script>

    <style>
        .qr-dialog::part(base) {
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-dialog::part(panel) {
            position: relative;
            background: white;
            height: auto;
            max-height: none;
            margin: auto;
        }

        .qr-dialog::part(overlay) {
            visibility: hidden;
        }

        .qr-dialog::part(header) {
            background: white;
        }

        .qr-dialog::part(body) {
            padding: 0;
            background: white;
            height: auto;
            min-height: 0;
        }

        .qr-dialog::part(footer) {
            background: white;
        }
    </style>
</div>
