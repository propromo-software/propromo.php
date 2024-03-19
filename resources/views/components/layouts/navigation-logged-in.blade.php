<div class="flex justify-between items-center gap-2 mt-5 mx-8 border-2 border-other-grey p-6 rounded-2xl">
    <a class="font-koulen text-primary-blue text-5xl" href="{{ url('/') }}">PROPROMO</a>

    <div class="flex gap-x-5">

        @php
            $monitor_hash = Session::has("monitor_hash") ? Session::get("monitor_hash") : 'no monitors available';
        @endphp

            <!-- Blade file (e.g., your-component.blade.php) -->
        <div class="flex gap-3 items-center">
            <sl-input wire:ignore id="monitor_hash" type="text" value="{{$monitor_hash}}" disabled></sl-input>
            <sl-icon wire:ignore onclick="copyToClipboard('{{ $monitor_hash }}')" id="copyIcon" name="copy" class="text-2xl text-primary-blue cursor-pointer" from="monitor_hash"></sl-icon>
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

        <div class="flex items-center gap-2">
            <sl-icon name="gear-wide-connected" class="text-3xl font-bold text-primary-blue"></sl-icon>
            <sl-icon name="person-circle" class="text-3xl font-bold text-primary-blue"></sl-icon>
        </div>
    </div>
</div>
