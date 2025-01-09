<div>
    @error('vulnerabilities')
        <div class="mb-4 text-sm text-red-500">{{ $message }}</div>
    @enderror

    <div class="flex items-center gap-3 mb-4">
        <h2 class="text-lg font-semibold">Security Vulnerabilities</h2>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center justify-center px-2 h-6 text-xs font-medium rounded-full 
                @if($totalVulnerabilities > 0)
                    bg-additional-red/10 text-additional-red
                @else
                    bg-additional-green/10 text-additional-green
                @endif">
                {{ count($vulnerabilities) }}/{{ $totalVulnerabilities }}
            </span>
            @if($totalVulnerabilities > count($vulnerabilities))
                <span class="text-xs text-secondary-grey">(Limited Preview)</span>
            @endif
        </div>
    </div>

    <div class="space-y-4">
        @forelse($vulnerabilities as $vulnerability)
            <div class="p-4 border rounded-md border-border-color">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium gap-1
                            @if($vulnerability->fixed_at !== null) bg-additional-green/10 text-additional-green
                            @else bg-additional-red/10 text-additional-red
                            @endif">
                            @if($vulnerability->fixed_at !== null)
                                <sl-icon name="shield-check" class="text-sm"></sl-icon>
                                Fixed
                            @else
                                <sl-icon name="shield-exclamation" class="text-sm"></sl-icon>
                                Vulnerable
                            @endif
                        </span>
                        <span class="text-sm text-secondary-grey">
                            {{ $vulnerability->repository_name }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-secondary-grey/10 text-secondary-grey">
                            {{ $vulnerability->ecosystem }}
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-secondary-grey/10 text-secondary-grey">
                            {{ $vulnerability->classification }}
                        </span>
                    </div>
                </div>

                <div class="mb-2">
                    <h3 class="text-sm font-medium text-primary-blue">
                        {{ $vulnerability->package_name }}: {{ $vulnerability->summary }}
                    </h3>
                    <p class="mt-1 text-sm text-secondary-grey">
                        {{ $vulnerability->description }}
                    </p>
                </div>

                <div class="flex flex-wrap gap-4 text-xs text-secondary-grey">
                    <div class="flex items-center gap-1">
                        <sl-icon name="code" class="text-sm"></sl-icon>
                        Version: {{ $vulnerability->vulnerable_version_range }}
                    </div>
                    @if($vulnerability->first_patched_version)
                        <div class="flex items-center gap-1">
                            <sl-icon name="arrow-up-circle" class="text-sm"></sl-icon>
                            Patch: {{ $vulnerability->first_patched_version }}
                        </div>
                    @endif
                    <div class="flex items-center gap-1">
                        <sl-icon name="calendar2-event" class="text-sm"></sl-icon>
                        Published: {{ $vulnerability->published_at ? $vulnerability->published_at->diffForHumans() : 'Unknown' }}
                    </div>
                    <div class="flex items-center gap-1">
                        <sl-icon name="box" class="text-sm"></sl-icon>
                        Scope: {{ $vulnerability->dependency_scope }}
                    </div>
                </div>
            </div>
        @empty
            <div class="flex items-center justify-center gap-2 py-4 text-center text-secondary-grey">
                <sl-icon name="shield-check" class="text-lg"></sl-icon>
                No Vulnerabilities Found
            </div>
        @endforelse
    </div>
</div>
