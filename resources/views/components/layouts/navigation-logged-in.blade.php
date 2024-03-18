<div class="flex justify-between items-center gap-2 mt-5 mx-8 border-2 border-other-grey p-6 rounded-2xl">
    <a class="font-koulen text-primary-blue text-5xl" href="{{ url('/') }}">PROPROMO</a>

    <div class="flex gap-x-5">

        @php
            $project_hash = Session::has("project_hash") ? Session::get("project_hash") : 'no projects available';
        @endphp

            <!-- Blade file (e.g., your-component.blade.php) -->
        <div class="flex gap-3 items-center">
            <sl-input wire:ignore id="project_hash" type="text" value="{{$project_hash}}" disabled></sl-input>
            <sl-icon wire:ignore onclick="copyToClipboard('{{ $project_hash }}')" id="copyIcon" name="copy" class="text-2xl text-primary-blue cursor-pointer" from="project_hash"></sl-icon>
            <script>
                function copyToClipboard(text) {
                    let copyIcon = document.getElementById("copyIcon");
                    let originalIconName = copyIcon.getAttribute("name");

                    let projectHash = document.createElement("textarea");
                    projectHash.textContent = text;
                    document.body.appendChild(projectHash);
                    projectHash.select();
                    document.execCommand("copy");
                    document.body.removeChild(projectHash);

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
