<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Propromo') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <link rel="shortcut icon" href="/favicon.png" type="image/png">


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>


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

        @if(!request()->is('/'))
            @include('layouts.navigation')
        @endif

            <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>
