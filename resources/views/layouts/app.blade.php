<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Propromo') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="shortcut icon" href="/favicon.png" type="image/png">


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body>


        <div>

            <!-- Page Heading -->
            @if (isset($header))
                <header>
                    <div>
                        {{ $header }}
                    </div>
                </header>
            @endif

            <div>
                @if (Route::has('login'))
                    <div>
                        @auth
                            <a href="{{ url('/dashboard') }}">Dashboard</a>
                            <br>
                            <a href="{{ url('/projects') }}">Projects</a>
                        @else
                            <div class="flex justify-end gap-2 mt-5 mx-8">
                                <sl-button>
                                    <a href="{{ route('login') }}">LOG IN</a>
                                </sl-button>

                                @if (Route::has('register'))
                                    <sl-button>
                                        <a href="{{ route('register') }}">REGISTER</a>
                                    </sl-button>
                                @endif
                            </div>

                        @endauth
                    </div>
                @endif

            <main>
                {{ $slot }}
            </main>

        </div>
    </body>
</html>
