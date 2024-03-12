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
            <a href="{{ url('login') }}">LOG IN</a>
        </sl-button>

        <sl-button>
            <a href="{{ url('register') }}">REGISTER</a>
        </sl-button>
    </div>
@endauth
