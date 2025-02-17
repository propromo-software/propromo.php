@auth
    <div class="p-4 sm:p-8">
        <div class="flex items-center justify-between py-4 pl-4 pr-2 border-2 xxs:pr-4 sm:px-9 sm:py-6 rounded-2xl backdrop-blur-sm bg-base-200/50 border-other-grey">
            <a class="-mb-1 text-3xl sm:text-4xl font-koulen text-primary-blue text-uppercase" href="{{ url('/') }}">Propromo</a>

            <nav class="flex items-center gap-2 sm:gap-4">
                <div class="sm:hidden">
                    <sl-icon-button name="display" label="Monitors" href="{{ route('monitors.index') }}" class="text-primary-blue" style="font-size: 2rem;"></sl-icon-button>
                </div>
                <a href="{{ route('monitors.index') }}" class="items-center hidden gap-1 transition-colors duration-200 sm:flex text-primary-blue/70 hover:text-primary-blue">
                    <sl-icon name="display" class="text-xl"></sl-icon>
                    <span class="hidden sm:inline">Monitors</span>
                </a>

                <div class="hidden w-px h-6 xxs:block bg-primary-blue/20"></div>

                <div class="hidden xxs:block sm:hidden">
                    <sl-icon-button name="box-arrow-right" label="Logout" href="{{ url('/logout') }}" class="text-primary-blue" style="font-size: 2rem;"></sl-icon-button>
                </div>
                <a href="{{ url('/logout') }}" class="items-center hidden gap-1 transition-colors duration-200 sm:flex text-primary-blue/70 hover:text-primary-blue">
                    <sl-icon name="box-arrow-right" class="text-xl"></sl-icon>
                    <span class="hidden sm:inline">Logout</span>
                </a>
            </nav>
        </div>
    </div>
@else
    <div class="p-4 sm:p-8">
        <div class="flex items-center justify-between py-4 pl-4 pr-2 border-2 xxs:pr-4 sm:px-9 sm:py-6 rounded-2xl backdrop-blur-sm bg-base-200/50 border-other-grey">
            <a class="-mb-1 text-3xl sm:text-4xl font-koulen text-primary-blue text-uppercase" href="{{ url('/') }}">Propromo</a>

            <nav class="flex items-center gap-2 sm:gap-4">
                <div class="sm:hidden">
                    <sl-icon-button name="box-arrow-in-right" label="Login" href="{{ url('login') }}" class="text-primary-blue" style="font-size: 2rem;"></sl-icon-button>
                </div>
                <a href="{{ url('login') }}" class="hidden sm:flex [&_sl-button::part(base)]:flex [&_sl-button::part(base)]:items-center [&_sl-button::part(base)]:gap-1">
                    <sl-button variant="default">
                        <sl-icon slot="prefix" name="box-arrow-in-right" class="text-lg align-middle"></sl-icon>
                        <span class="align-middle">Login</span>
                    </sl-button>
                </a>

                <div class="hidden xxs:block sm:hidden">
                    <sl-icon-button name="person-plus" label="Register" href="{{ url('register') }}" class="text-primary-blue" style="font-size: 2rem;"></sl-icon-button>
                </div>
                <a href="{{ url('register') }}" class="hidden sm:flex [&_sl-button::part(base)]:flex [&_sl-button::part(base)]:items-center [&_sl-button::part(base)]:gap-1">
                    <sl-button variant="default">
                        <sl-icon slot="prefix" name="person-plus" class="text-lg align-middle"></sl-icon>
                        <span class="align-middle">Register</span>
                    </sl-button>
                </a>
            </nav>
        </div>
    </div>
@endauth
