@props([
    'location' => 'top',
    'route' => null,
    'params' => [],
    'breadcrumbs' => null
])

@php
    if (!$breadcrumbs) {
        $routeMap = [
            'home.index' => ['name' => 'home'],
            'monitors.index' => ['name' => 'monitors'],
            'monitors.show' => ['name' => 'monitor', 'params' => ['monitor']],
            'monitors.custom.index' => ['name' => 'monitors'],
            'monitors.custom.show' => ['name' => 'monitor', 'params' => ['monitor']],
            'milestone.show' => ['name' => 'milestone', 'params' => ['monitor', 'milestone']],
            'pdf.index' => ['name' => 'pdf', 'params' => ['monitor']],
            'settings.index' => ['name' => 'home'],
            'settings.monitors.index' => ['name' => 'home'],
            'repositories.list' => ['name' => 'monitors']
        ];

        $breadcrumbConfig = $route && isset($routeMap[$route]) ? $routeMap[$route] : null;
        $breadcrumbName = $breadcrumbConfig ? $breadcrumbConfig['name'] : $route;
        $breadcrumbParams = [];
        
        if ($breadcrumbConfig && isset($breadcrumbConfig['params'])) {
            foreach ($breadcrumbConfig['params'] as $param) {
                if (isset($params[$param])) {
                    // If the parameter is already a model instance, use it directly
                    if (is_object($params[$param])) {
                        $breadcrumbParams[] = $params[$param];
                    } else {
                        // If it's not a model instance, try to find it
                        $modelClass = match($param) {
                            'monitor' => \App\Models\Monitor::class,
                            'milestone' => \App\Models\Milestone::class,
                            default => null
                        };
                        if ($modelClass) {
                            $model = $modelClass::find($params[$param]);
                            if ($model) {
                                $breadcrumbParams[] = $model;
                            }
                        }
                    }
                }
            }
        }

        $breadcrumbs = $breadcrumbName && !empty($breadcrumbParams) ? Breadcrumbs::generate($breadcrumbName, ...$breadcrumbParams) : collect([]);
    }
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
