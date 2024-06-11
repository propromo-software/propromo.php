<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200">

@if(request()->path() !== '/' && request()->path() !== 'create-monitor' && request()->path() !== 'create-open-source-monitor' && request()->path() !== 'register' && request()->path() !== 'login' && request()->path() !== 'join')
    @include('components.layouts.navigation-logged-in')
@else
    @include('components.layouts.navigation-logged-out')
@endif

<main>
    {{ $slot }}
</main>


<livewire:base.api-changed-toast></livewire:base.api-changed-toast>

</body>
</html>
