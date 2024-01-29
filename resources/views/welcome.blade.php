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
                <a href="{{ url('/projects') }}">Projects</a>

            @else
            <a href="{{ route('login') }}">Log in</a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}">Register</a>
            @endif
            @endauth
        </div>
        @endif

        <livewire:join-project-form />
    </div>
</body>
</html>
