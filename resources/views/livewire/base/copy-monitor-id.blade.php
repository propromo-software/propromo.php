<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;


new class extends Component {

    public $monitor_hash;

    public function mount($monitor_hash=null)
    {
        $this->monitor_hash = $monitor_hash;

    }

    #[On('monitor-hash-changed')]
    public function updateMonitorHash($monitor_hash = null)
    {
        $this->monitor_hash = $monitor_hash;
        $this->mount($monitor_hash);
    }

}; ?>

<div class="flex gap-3 items-center">
    <sl-input wire:ignore id="monitor_hash"
              type="text" value="{{$monitor_hash}}"
              disabled></sl-input>
    <sl-icon wire:ignore onclick="copyToClipboard('http://propromo.test/monitors/join/{{ $monitor_hash }}')"
             id="copyIcon"
             name="copy"
             class="text-2xl text-primary-blue cursor-pointer"
             from="monitor_hash"></sl-icon>
    <script>
        function copyToClipboard(text) {
            let copyIcon = document.getElementById("copyIcon");
            let originalIconName = copyIcon.getAttribute("name");

            let monitorHash = document.createElement("textarea");
            monitorHash.textContent = text;
            document.body.appendChild(monitorHash);
            monitorHash.select();
            document.execCommand("copy");
            document.body.removeChild(monitorHash);

            copyIcon.setAttribute("name", "check-lg");

            setTimeout(function() {
                copyIcon.setAttribute("name", originalIconName);
            }, 500);
        }
    </script>
</div>
