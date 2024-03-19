@auth
    <div class="flex justify-between mt-5 mx-8 items-center">
        <div>
            <a class="font-koulen text-primary-blue text-3xl" href="{{ url('/') }}">PROPROMO</a>
        </div>

        <div class="flex gap-2">
            <sl-button>
                <a href="{{ url('/monitors') }}">MONITORS</a>
            </sl-button>
            <sl-button>
                <a href="{{ url('/logout') }}">LOG OUT</a>
            </sl-button>
        </div>
    </div>
@else
    <div class="flex justify-between mt-5 mx-8 items-center">
        <div>
            <a class="font-koulen text-primary-blue text-3xl" href="{{ url('/') }}">PROPROMO</a>
        </div>

        <div class="flex gap-2">
            <sl-button>
                <a href="{{ url('login') }}">LOG IN</a>
            </sl-button>

            <sl-button>
                <a href="{{ url('register') }}">REGISTER</a>
            </sl-button>
        </div>
    </div>
@endauth
