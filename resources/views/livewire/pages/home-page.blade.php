<div>
    <div>
    @auth
        <div class="flex justify-end gap-2 mt-5 mx-8">
            <sl-button>
                <a href="{{ url('/projects') }}">PROJECTS</a>
            </sl-button>
            <sl-button>
                <a href="{{ url('/logout') }}">LOG OUT</a>
            </sl-button>
        </div>
    @else
        <div class="flex justify-end gap-2 mt-5 mx-8">
            <sl-button>
                <a href="{{ route('login') }}">LOG IN</a>
            </sl-button>

            <sl-button>
                <a href="{{ route('register') }}">REGISTER</a>
            </sl-button>
        </div>
    @endauth
</div>

<div class="flex justify-center">
    <div class="columns-1 w-fit">
        <div class="flex justify-center gap-14 items-center mb-10">
            <div class="w-80">
                <img src="{{asset('/assets/logo/Propromo-Logo-circle.svg')}}" alt="propromo-logo">
            </div>
            <div>
                <h1 class="flex-initial font-koulen text-7xl text-primary-blue">PROPROMO</h1>
                <h2 class="flex-initial font-koulen text-4xl text-secondary-grey">PROJECT PROGRESS MONITORING</h2>
                <p class="flex-initial font-koulen text-2xl text-other-grey mt-5">WORKS WITH:</p>
                <div>
                    <a href="https://github.com/" target="_blank">
                        <sl-icon name="github" class="text-4xl mt-0.5"></sl-icon>
                    </a>
                    <!-- <sl-icon name="github"></sl-icon> -->
                </div>
            </div>
        </div>

        <livewire:join-project-form class="mt-20"/>


        <h1 class="font-koulen text-4xl text-secondary-grey text-center mt-20">PROJECT PREVIEW</h1>

        <iframe class="mt-2" width="100%" height="315"
                src="https://youtu.be/SXmJH72-O5g?autoplay=1">
        </iframe>
    </div>
</div>
</div>
