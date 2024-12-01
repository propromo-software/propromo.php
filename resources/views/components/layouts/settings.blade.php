<!-- resources/views/settings.blade.php -->
@extends('components.layouts.app')

@section('content')
    <main class="border-other-grey border-2 rounded-2xl mt-5 mx-8 px-5 grid grid-cols-5 h-[calc(85vh-2rem)]">
        <div class="col-span-1 p-4">
            <livewire:settings.navigation />
        </div>
        <div class="col-span-4 p-5 overflow-y-auto">
            <livewire:settings.content />
        </div>
    </main>
@endsection
