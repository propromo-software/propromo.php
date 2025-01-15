@props(['variant' => 'info'])

@php
    $variantClasses = [
        'info' => 'bg-primary-blue/10 border-primary-blue text-primary-blue',
        'error' => 'bg-additional-red/10 border-additional-red text-additional-red',
        'warning' => 'bg-additional-orange/10 border-additional-orange text-additional-orange'
    ];

    $variantIcons = [
        'info' => 'info-circle',
        'error' => 'exclamation-circle',
        'warning' => 'exclamation-triangle'
    ];
@endphp

<div {{ $attributes->merge(['class' => 'w-full border rounded-lg p-4 my-2 flex items-center gap-3 ' . $variantClasses[$variant]]) }}>
    <sl-icon class="text-xl" name="{{ $variantIcons[$variant] }}"></sl-icon>
    <span class="font-sourceSansPro">{{ $slot }}</span>
</div>
