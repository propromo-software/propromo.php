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
    <div class="flex items-center gap-4">
        @if($monitor_hash)
            <label for="monitor_hash" class="sr-only">Monitor Hash</label>
            <input id="monitor_hash"
                   type="text"
                   value="{{$monitor_hash}}"
                   disabled
                   class="hidden px-4 py-2 text-sm bg-transparent border-none lg:block text-primary-blue/70"/>

            <x-bi-copy onclick="copyToClipboard('{{url('/')}}/monitors/join/{{ $monitor_hash }}')"
                       id="copyIcon"
                       name="copy"
                       class="text-xl cursor-pointer text-primary-blue/70 hover:text-primary-blue"
                       from="monitor_hash"/>

            <x-bi-check id="checkIcon"
                        class="hidden text-xl cursor-pointer text-primary-blue/70"/>

            <!-- QR Code Button -->
            <sl-icon-button wire:ignore name="qr-code" id="qr-button" label="QR Code" class="text-primary-blue" onclick="showQRDialog('{{url('/')}}/monitors/join/{{ $monitor_hash }}')"></sl-icon-button>
        @else
            <sl-spinner class="text-xl text-primary-blue/70"></sl-spinner>
        @endif

        <div class="w-px h-6 bg-primary-blue/20"></div>
    </div>

    <script>
        function showQRDialog(url) {
            document.dispatchEvent(new CustomEvent('show-qr-dialog', {
                detail: { url }
            }));
        }

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
</div>
