<?php
use Livewire\Volt\Component;

new class extends Component {
    public $monitor_hash;
}
?>

<div class="p-8">
    <div class="flex justify-between items-center p-6 rounded-2xl border-2 backdrop-blur-sm bg-base-200/50 border-other-grey">
        <a class="text-5xl font-koulen text-primary-blue text-uppercase" href="{{ url('/') }}">Propromo</a>

        <div class="flex gap-x-5 items-center">
            @if(count(request()->segments()) == 2 && request()->segment(1) == 'monitors')
                <livewire:base.copy-monitor-id>
            @endif

            <nav class="flex gap-4 items-center">
                <a href="{{ route('monitors.index') }}" class="flex gap-1 items-center transition-colors duration-200 text-primary-blue/70 hover:text-primary-blue">
                    <sl-icon name="display" class="text-xl"></sl-icon>
                    <span>Monitors</span>
                </a>

                <div class="w-px h-6 bg-primary-blue/20"></div>

                <a href="{{ route('settings.index') }}" class="flex gap-1 items-center transition-colors duration-200 text-primary-blue/70 hover:text-primary-blue">
                    <sl-icon name="gear-wide-connected" class="text-xl"></sl-icon>
                    <span>Settings</span>
                </a>

                <a href="{{ url('/logout') }}" class="flex gap-1 items-center transition-colors duration-200 text-primary-blue/70 hover:text-primary-blue">
                    <sl-icon name="box-arrow-right" class="text-xl"></sl-icon>
                    <span>Logout</span>
                </a>
            </nav>
        </div>
    </div>
</div>
