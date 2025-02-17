<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class="relative min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200">
        <!-- QR Dialog -->
        <sl-dialog id="global-qr-dialog" class="qr-dialog" label="Monitor QR Code">
            <div id="qr-container">
                <sl-qr-code id="global-qr-code"
                    background="white"
                    foreground="black"
                    radius="0"
                    size="300">
                </sl-qr-code>
            </div>
            <sl-button slot="footer" class="w-full">Close</sl-button>
        </sl-dialog>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const dialog = document.querySelector('#global-qr-dialog');
                const qrCode = document.querySelector('#global-qr-code');
                
                function updateQRSize() {
                    const body = dialog.shadowRoot.querySelector('[part="body"]');
                    const size = Math.min(body.clientWidth - 64, body.clientHeight - 64);
                    qrCode.size = Math.floor(size);
                }

                document.addEventListener('show-qr-dialog', (e) => {
                    qrCode.setAttribute('value', e.detail.url);
                    dialog.show();
                    requestAnimationFrame(() => {
                        updateQRSize();
                    });
                });

                window.addEventListener('resize', () => {
                    if (dialog.open) {
                        updateQRSize();
                    }
                });

                const closeButton = dialog.querySelector('sl-button[slot="footer"]');
                closeButton.addEventListener('click', () => {
                    dialog.hide();
                });
            });
        </script>

        <style>
            #qr-container {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            #qr-container sl-qr-code {
                max-width: 100%;
                max-height: 100%;
            }

            .qr-dialog::part(base) {
                --border-radius: 0.375rem;
                position: fixed;
                inset: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .qr-dialog::part(panel) {
                background: white;
                border-radius: 0.375rem;
                width: min(90vw, 500px);
                height: min(90vh, 600px);
                display: flex;
                flex-direction: column;
            }

            .qr-dialog::part(header) {
                padding: 0.5rem;
                border-bottom: 1px solid #eee;
            }

            .qr-dialog::part(body) {
                flex: 1;
                padding: 1rem;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .qr-dialog::part(footer) {
                padding: 0.5rem;
                border-top: 1px solid #eee;
            }

            .qr-dialog::part(overlay) {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                visibility: visible;
            }
        </style>

        <header class="sticky top-0 z-50">
            @if(request()->path() !== '/' && request()->path() !== 'create-monitor' && request()->path() !== 'create-open-source-monitor' && request()->path() !== 'register' && request()->path() !== 'login' && request()->path() !== 'join')
                @include('components.layouts.navigation-app')
            @else
                @include('components.layouts.navigation-preview')
            @endif
        </header>

        <main class="min-h-screen px-8 pb-4">
            @if(request()->path() !== '/' && 
                request()->path() !== 'create-monitor' && 
                request()->path() !== 'create-open-source-monitor' && 
                request()->path() !== 'register' && 
                request()->path() !== 'login' && 
                request()->path() !== 'join' &&
                request()->path() !== 'settings/profile' &&
                request()->route()->getName() !== 'monitors.show')
                <div class="container px-8 py-4 mx-auto">
                    <x-breadcrumbs 
                        route="{{ request()->route()->getName() }}" 
                        location="top"
                        :params="request()->route()->parameters()" 
                    />
                </div>
            @endif

            @yield('content')
            {{ $slot }}

            <livewire:base.api-changed-toast></livewire:base.api-changed-toast>
        </main>

        @if(request()->path() === '/' || 
            request()->path() === 'create-monitor' || 
            request()->path() === 'create-open-source-monitor' || 
            request()->path() === 'register' || 
            request()->path() === 'login' || 
            request()->path() === 'join')
            <x-footer :route="request()->route()->getName()" />
        @endif
    </body>
</html>
