<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">

    <title>Propromo</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>

<div>
    @if (Route::has('login'))
        <div>
            @auth
                <a href="{{ url('/dashboard') }}">Dashboard</a>
                <br>
                <a href="{{ url('/') }}">Home</a>

            @else
                <a href="{{ route('login') }}">Log in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        </div>
    @endif
</div>

<h1 class="mb-8 text-center text-8xl font-koulen">Projects</h1>

<h2 class="font-sourceSansPro text-2xl">Hallo</h2>

<div class="flex flex-wrap justify-center">
    <livewire:show-projects />
</div>

</body>
</html>
