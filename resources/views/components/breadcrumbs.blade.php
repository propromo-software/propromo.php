@props([
    'location' => 'top',
    'route' => null
])

@php
    $routeMap = [
        'home.index' => 'home',
        'monitors.index' => 'monitors',
        'monitors.show' => 'monitor',
        'monitors.custom.index' => 'monitors',
        'monitors.custom.show' => 'monitor',
        'milestone.show' => 'milestone',
        'pdf.index' => 'pdf',
        'settings.index' => 'home',
        'settings.monitors.index' => 'home',
        'repositories.list' => 'monitors'
    ];

    $breadcrumbName = $route && isset($routeMap[$route]) ? $routeMap[$route] : $route;
    $breadcrumbs = $breadcrumbName ? Breadcrumbs::generate($breadcrumbName) : collect([]);
@endphp

@unless ($breadcrumbs->isEmpty())
    @if($location === 'top')
        <div class="flex flex-col gap-1">
            <nav class="flex gap-2 items-center">
                @foreach ($breadcrumbs as $breadcrumb)
                    @if (!is_null($breadcrumb->url) && !$loop->last)
                        <a 
                            href="{{ $breadcrumb->url }}" 
                            class="transition-colors duration-200 text-primary-blue/70 hover:text-primary-blue font-sourceSansPro"
                        >
                            {{ $breadcrumb->title }}
                        </a>
                    @else
                        <span class="font-semibold font-sourceSansPro text-primary-blue">
                            {{ $breadcrumb->title }}
                        </span>
                    @endif

                    @unless($loop->last)
                        <sl-icon name="chevron-right" class="text-sm text-primary-blue/40"></sl-icon>
                    @endunless
                @endforeach
            </nav>
        </div>
    @else
        <nav class="flex items-center space-x-2 text-sm font-sourceSansPro text-primary-blue/70">
            @foreach ($breadcrumbs as $breadcrumb)
                @if (!is_null($breadcrumb->url) && !$loop->last)
                    <a 
                        href="{{ $breadcrumb->url }}" 
                        class="transition-colors duration-200 hover:text-primary-blue"
                    >
                        {{ $breadcrumb->title }}
                    </a>
                @else
                    <span class="text-primary-blue">
                        {{ $breadcrumb->title }}
                    </span>
                @endif

                @unless($loop->last)
                    <sl-icon name="chevron-right" class="text-sm opacity-70"></sl-icon>
                @endunless
            @endforeach
        </nav>
    @endif
@endunless
