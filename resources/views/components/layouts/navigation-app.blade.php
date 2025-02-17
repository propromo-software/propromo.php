<?php
use Livewire\Volt\Component;

new class extends Component {
    public $monitor_hash;
}
?>

<div class="p-4 sm:p-8">
    <div class="flex items-center justify-between py-4 pl-4 pr-2 border-2 xxs:pr-4 sm:px-8 sm:py-6 rounded-2xl backdrop-blur-sm bg-base-200/50 border-other-grey">
        <a class="-mb-1 text-3xl sm:text-4xl font-koulen text-primary-blue text-uppercase" href="{{ url('/') }}">Propromo</a>

        <div class="flex items-center gap-x-5">
            @if(count(request()->segments()) == 2 && request()->segment(1) == 'monitors')
                <livewire:base.copy-monitor-id>
            @endif

            <nav class="flex items-center gap-2 md:gap-4">
                <div class="md:hidden">
                    <sl-icon-button name="display" label="Monitors" href="{{ route('monitors.index') }}" class="text-primary-blue" style="font-size: 2rem;"></sl-icon-button>
                </div>
                <a href="{{ route('monitors.index') }}" class="items-center hidden gap-1 transition-colors duration-200 md:flex text-primary-blue/70 hover:text-primary-blue">
                    <sl-icon name="display" class="text-xl"></sl-icon>
                    <span>Monitors</span>
                </a>

                <div class="hidden w-px h-6 xxs:block bg-primary-blue/20"></div>

                <div class="hidden xxs:block md:hidden">
                    <sl-icon-button name="gear-wide-connected" label="Settings" href="{{ route('settings.index') }}" class="text-primary-blue" style="font-size: 2rem;"></sl-icon-button>
                </div>
                <a href="{{ route('settings.index') }}" class="items-center hidden gap-1 transition-colors duration-200 md:flex text-primary-blue/70 hover:text-primary-blue">
                    <sl-icon name="gear-wide-connected" class="text-xl"></sl-icon>
                    <span class="hidden md:inline">Settings</span>
                </a>

                <div class="hidden xxs:block md:hidden">
                    <sl-icon-button name="box-arrow-right" label="Logout" href="{{ url('/logout') }}" class="text-primary-blue" style="font-size: 2rem;"></sl-icon-button>
                </div>
                <a href="{{ url('/logout') }}" class="items-center hidden gap-1 transition-colors duration-200 md:flex text-primary-blue/70 hover:text-primary-blue">
                    <sl-icon name="box-arrow-right" class="text-xl"></sl-icon>
                    <span class="hidden md:inline">Logout</span>
                </a>
            </nav>
        </div>
    </div>
</div>
