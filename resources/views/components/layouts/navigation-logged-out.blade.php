@auth
    <div class="p-8">
        <div class="flex justify-between items-center p-6 rounded-2xl border-2 backdrop-blur-sm bg-base-200/50 border-other-grey">
            <a class="text-5xl font-koulen text-primary-blue text-uppercase" href="{{ url('/') }}">Propromo</a>

            <nav class="flex gap-4 items-center">
                <a href="{{ route('monitors.index') }}" class="flex gap-1 items-center transition-colors duration-200 text-primary-blue/70 hover:text-primary-blue">
                    <sl-icon name="display" class="text-xl"></sl-icon>
                    <span>Monitors</span>
                </a>

                <div class="w-px h-6 bg-primary-blue/20"></div>

                <a href="{{ url('/logout') }}" class="flex gap-1 items-center transition-colors duration-200 text-primary-blue/70 hover:text-primary-blue">
                    <sl-icon name="box-arrow-right" class="text-xl"></sl-icon>
                    <span>Logout</span>
                </a>
            </nav>
        </div>
    </div>
@else
    <div class="p-8">
        <div class="flex justify-between items-center p-6 backdrop-blur-sm bg-base-200/50">
            <a class="text-3xl font-koulen text-primary-blue text-uppercase" href="{{ url('/') }}">Propromo</a>

            <nav class="flex gap-4 items-center">
                <a href="{{ url('login') }}" class="[&_sl-button::part(base)]:flex [&_sl-button::part(base)]:items-center [&_sl-button::part(base)]:gap-1">
                    <sl-button variant="default">
                        <sl-icon slot="prefix" name="box-arrow-in-right" class="text-lg align-middle"></sl-icon>
                        <span class="align-middle">Login</span>
                    </sl-button>
                </a>

                <a href="{{ url('register') }}" class="[&_sl-button::part(base)]:flex [&_sl-button::part(base)]:items-center [&_sl-button::part(base)]:gap-1">
                    <sl-button variant="default">
                        <sl-icon slot="prefix" name="person-plus" class="text-lg align-middle"></sl-icon>
                        <span class="align-middle">Register</span>
                    </sl-button>
                </a>
            </nav>
        </div>
    </div>
@endauth
