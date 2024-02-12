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

    @include('layouts.navigation')


    <div class="flex flex-wrap justify-center">
        <livewire:show-projects/>
    </div>

</body>
</html>
