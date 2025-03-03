<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            // Add Livewire hook to preserve Web Component attributes
            Livewire.hook('morph.updating', ({ el, component, toEl }) => {
                // Check if element is a custom element
                if (!el.tagName.includes('-')) {
                    return;
                }

                // Store the original attributes
                let oldAttributes = Array.from(el.attributes)
                    .reduce((attrs, attr) => {
                        attrs[attr.name] = attr.value;
                        return attrs;
                    }, {});

                // Restore all attributes that might have been removed by Livewire
                let currentAttributes = Array.from(toEl.attributes).map(attr => attr.name);
                Object.entries(oldAttributes).forEach(([name, value]) => {
                    if (!name.startsWith('!') && !currentAttributes.includes(name)) {
                        toEl.setAttribute(name, value);
                    }
                });

                // Remove attributes starting with '!' from the toEl
                Array.from(toEl.attributes).forEach(attr => {
                    if (attr.name.startsWith('!')) {
                        toEl.removeAttribute(attr.name.substring(1)); // Remove the corresponding actual attribute
                        toEl.removeAttribute(attr.name); // Remove the attribute with the '!' prefix
                    }
                });
            });
        </script>

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

        <!-- Global Error Alert -->
        <div class="fixed right-4 bottom-4 z-50">
            <sl-alert id="global-error-alert-component" variant="danger" duration="5000" countdown="rtl" closable>
                <sl-icon slot="icon" name="exclamation-octagon"></sl-icon>
                <strong class="alert-component-head"></strong><br>
                <span class="alert-component-content"></span>
            </sl-alert>
        </div>

        <!-- Global Success Alert -->
        <div class="fixed right-4 bottom-4 z-50">
            <sl-alert id="global-success-alert-component" variant="success" duration="5000" countdown="rtl" closable>
                <sl-icon slot="icon" name="check-circle"></sl-icon>
                <strong class="alert-component-head"></strong><br>
                <span class="alert-component-content"></span>
            </sl-alert>
        </div>

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

                const errorComponent = document.querySelector('#global-error-alert-component');
                const successComponent = document.querySelector('#global-success-alert-component');

                function getAlertContent(data) {
                    let head = 'Error';
                    let message = 'An error occurred';

                    if (data.detail) {
                        head = data.detail.head || head;
                        message = data.detail.message || message;
                    } else if (typeof data === 'object') {
                        // Try other possible structures
                        if (data.head) head = data.head;
                        if (data.message) message = data.message;
                        // Check if it's an array
                        if (Array.isArray(data) && data.length > 0) {
                            if (data[0].detail) {
                                head = data[0].detail.head || head;
                                message = data[0].detail.message || message;
                            } else {
                                if (data[0].head) head = data[0].head;
                                if (data[0].message) message = data[0].message;
                            }
                        }
                    }

                    return {
                        head,
                        message
                    }
                }

                Livewire.on('show-error-alert', (data) => {
                    console.log('Error alert event received:', data);

                    const componentHead = errorComponent.querySelector('.alert-component-head');
                    const componentContent = errorComponent.querySelector('.alert-component-content');

                    const { head, message } = getAlertContent(data);
                    componentHead.textContent = head;
                    componentContent.textContent = message;
                    errorComponent.show();
                });

                Livewire.on('show-success-alert', (data) => {
                    console.log('Success alert event received:', data);

                    const componentHead = successComponent.querySelector('.alert-component-head');
                    const componentContent = successComponent.querySelector('.alert-component-content');

                    const { head, message } = getAlertContent(data);
                    componentHead.textContent = head;
                    componentContent.textContent = message;
                    successComponent.show();
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

            sl-alert::part(base) {
                width: auto;
                min-width: max-content;
                overflow-wrap: break-word;
            }

            sl-alert::part(message) {
                overflow-wrap: break-word;
            }
        </style>

        <header class="sticky top-0 z-50">
            @if(request()->path() !== '/' && request()->path() !== 'create-monitor' && request()->path() !== 'create-open-source-monitor' && request()->path() !== 'register' && request()->path() !== 'login' && request()->path() !== 'join')
                @include('components.layouts.navigation-app')
            @else
                @include('components.layouts.navigation-preview')
            @endif
        </header>

        <main class="px-8 pb-4 min-h-screen">
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
