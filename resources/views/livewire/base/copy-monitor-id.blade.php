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

<div class="flex gap-4 items-center">
    <label for="monitor_hash"></label><input id="monitor_hash"
                                             type="text"
                                             value="{{$monitor_hash}}"
                                             disabled
                                             class="px-4 py-2 opacity-75 rounded-md cursor-pointer"/>

    <x-bi-copy onclick="copyToClipboard('{{url('/')}}/monitors/join/{{ $monitor_hash }}')"
               id="copyIcon"
               name="copy"
               class="w-7 h-7 text-primary-blue cursor-pointer"
               from="monitor_hash"/>

    <x-bi-check id="checkIcon"
                class="w-7 h-7 text-primary-blue cursor-pointer hidden"/>

    <script>
        function copyToClipboard(text) {
            let copyIcon = document.getElementById("copyIcon");
            let checkIcon = document.getElementById("checkIcon");

            let monitorHash = document.createElement("textarea");
            monitorHash.textContent = text;
            document.body.appendChild(monitorHash);
            monitorHash.select();
            document.execCommand("copy");
            document.body.removeChild(monitorHash);

            // Hide the copy icon and show the check icon
            copyIcon.classList.add('hidden');
            checkIcon.classList.remove('hidden');

            // Revert back to the original icon after a delay
            setTimeout(function() {
                checkIcon.classList.add('hidden');
                copyIcon.classList.remove('hidden');
            }, 500);
        }
    </script>
</div>
