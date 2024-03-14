<div class="flex justify-between items-center gap-2 mt-5 mx-8 border-2 border-other-grey p-6 rounded-2xl">
    <a class="font-koulen text-primary-blue text-5xl" href="{{ url('/') }}">PROPROMO</a>

    <div class="flex gap-x-5">

        @php
            $project_hash = Session::has("project_hash") ? Session::get("project_hash") : 'no projects available';
        @endphp

        <div class="flex gap-2 items-center">
            <sl-input wire:ignore id="project_hash" type="text" value="{{$project_hash}}" disabled></sl-input>
            <sl-copy-button wire:ignore class="text-2xl text-primary-blue" from="project_hash"></sl-copy-button>
        </div>

        <div class="flex items-center gap-2">
            <sl-icon name="gear-wide-connected" class="text-3xl font-bold text-primary-blue"></sl-icon>
            <sl-icon name="person-circle" class="text-3xl font-bold text-primary-blue"></sl-icon>
        </div>
    </div>
</div>
