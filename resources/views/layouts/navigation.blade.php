<div class="flex justify-between items-center gap-2 mt-5 mx-8 border-2 border-other-grey p-6 rounded-2xl">
    <a class="font-koulen text-primary-blue text-5xl" href="{{ url('/') }}">PROPROMO</a>

    <div class="flex gap-x-5">

        <div class="flex justify-between gap-28 items-center border-2 p-3 border-other-grey rounded-md">
            <div>
                {{
                    Session::has("project") ? Session::get("project")->organisation_name : 'no projects available'
                }}
            </div>
            <sl-icon name="copy" class="text-2xl text-primary-blue">

            </sl-icon>
        </div>

        <div class="flex items-center gap-1">
            <sl-icon name="gear" class="text-4xl font-bold text-primary-blue"></sl-icon>
            <a href="{{ url('/profile') }}">
                <sl-icon name="person-circle" class="text-4xl font-bold text-primary-blue"></sl-icon>
            </a>
        </div>
    </div>

</div>
