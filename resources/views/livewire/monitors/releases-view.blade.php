<div>
    @error('releases')
        <div class="mb-4 text-sm text-red-500">{{ $message }}</div>
    @enderror

    <div class="mb-4">
        <div class="flex gap-3 items-center mb-4">
            <h2 class="text-lg font-semibold">Releases</h2>

            <div class="flex gap-2 items-center">
                <span class="inline-flex justify-center items-center px-2 h-6 text-xs font-medium rounded-full bg-primary-blue/10 text-primary-blue">
                    {{ $selectedRepository ? $filteredCount : $totalReleases }}/{{ $totalReleases }}
                </span>
                @if($totalReleases > count($releases))
                    <span class="text-xs text-secondary-grey">(Limited Preview)</span>
                @endif
            </div>
        </div>
    
        <div class="mb-4 space-y-4">
            <!-- Search -->
            <div class="flex gap-4">
                <sl-input
                    wire:ignore
                    id="release-search"
                    placeholder="Search releases..."
                    class="w-full"
                    clearable
                >
                    <sl-icon wire:ignore name="search" slot="prefix"></sl-icon>
                </sl-input>
            </div>

            <!-- Filters -->
            <div class="flex gap-4">
                <sl-select
                    wire:ignore
                    id="repository-select"
                    class="w-1/2"
                    placeholder="Select Repository"
                >
                    <sl-option wire:ignore value="">All Repositories</sl-option>
                    @foreach($repositories as $id => $name)
                        <sl-option wire:ignore value="{{ $id }}">{{ $name }}</sl-option>
                    @endforeach
                </sl-select>

                <sl-select
                    wire:ignore
                    id="filter-type-select"
                    value="all"
                    class="w-1/2"
                >
                    <sl-option wire:ignore value="all">All Releases</sl-option>
                    <sl-option wire:ignore value="latest">Latest Releases</sl-option>
                    <sl-option wire:ignore value="prerelease">Pre-releases</sl-option>
                    <sl-option wire:ignore value="draft">Draft Releases</sl-option>
                </sl-select>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="p-4 rounded-md border border-border-color">
            <div class="text-sm text-secondary-grey">Total Releases</div>
            <div class="text-2xl font-semibold">{{ $totalReleases }}</div>
        </div>
        <div class="p-4 rounded-md border border-border-color">
            <div class="text-sm text-secondary-grey">Pre-releases</div>
            <div class="text-2xl font-semibold">{{ $preReleaseCount }}</div>
        </div>
        <div class="p-4 rounded-md border border-border-color">
            <div class="text-sm text-secondary-grey">Total Changes</div>
            <div class="text-2xl font-semibold">{{ $totalChanges }}</div>
        </div>
        <div class="p-4 rounded-md border border-border-color">
            <div class="text-sm text-secondary-grey">Files Changed</div>
            <div class="text-2xl font-semibold">{{ $totalFilesChanged }}</div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Repository select
            const repositorySelect = document.getElementById('repository-select');
            repositorySelect.addEventListener('sl-change', (event) => {
                @this.set('selectedRepository', event.target.value);
                @this.loadReleases();
            });

            // Filter type select
            const filterTypeSelect = document.getElementById('filter-type-select');
            filterTypeSelect.addEventListener('sl-change', (event) => {
                @this.set('filterType', event.target.value);
                @this.loadReleases();
            });

            // Search input
            const searchInput = document.getElementById('release-search');
            let searchTimeout;
            
            searchInput.addEventListener('sl-input', (event) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    @this.set('search', event.target.value);
                    @this.loadReleases();
                }, 300);
            });

            searchInput.addEventListener('sl-clear', () => {
                @this.set('search', '');
                @this.loadReleases();
            });
        });
    </script>

    <div class="space-y-4 max-h-[40rem] overflow-auto">
        <div wire:loading wire:target="loadReleases" class="space-y-2">
            @for ($i = 0; $i < 3; $i++)
                <div class="p-4 rounded-md border border-border-color">
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center space-x-2">
                            <sl-skeleton wire:ignore effect="sheen" class="w-16 h-5 rounded-full"></sl-skeleton>
                            <sl-skeleton wire:ignore effect="sheen" class="w-24 h-4"></sl-skeleton>
                        </div>
                        <sl-skeleton wire:ignore effect="sheen" class="w-16 h-4"></sl-skeleton>
                    </div>
                    
                    <div class="mb-2">
                        <sl-skeleton wire:ignore effect="sheen" class="w-72 h-4"></sl-skeleton>
                        <sl-skeleton wire:ignore effect="sheen" class="mt-1 w-full h-8"></sl-skeleton>
                        
                        <div class="flex flex-wrap gap-4 mt-2">
                            <div class="flex gap-1 items-center">
                                <sl-skeleton wire:ignore effect="sheen" class="w-4 h-4"></sl-skeleton>
                                <sl-skeleton wire:ignore effect="sheen" class="w-12 h-4"></sl-skeleton>
                            </div>
                            <div class="flex gap-1 items-center">
                                <sl-skeleton wire:ignore effect="sheen" class="w-4 h-4"></sl-skeleton>
                                <sl-skeleton wire:ignore effect="sheen" class="w-12 h-4"></sl-skeleton>
                            </div>
                            <div class="flex gap-1 items-center">
                                <sl-skeleton wire:ignore effect="sheen" class="w-4 h-4"></sl-skeleton>
                                <sl-skeleton wire:ignore effect="sheen" class="w-12 h-4"></sl-skeleton>
                            </div>
                            <div class="flex gap-1 items-center">
                                <sl-skeleton wire:ignore effect="sheen" class="w-4 h-4"></sl-skeleton>
                                <sl-skeleton wire:ignore effect="sheen" class="w-12 h-4"></sl-skeleton>
                            </div>
                            <div class="flex gap-1 items-center">
                                <sl-skeleton wire:ignore effect="sheen" class="w-4 h-4 rounded-full"></sl-skeleton>
                                <sl-skeleton wire:ignore effect="sheen" class="w-12 h-4"></sl-skeleton>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <div wire:loading.remove wire:target="loadReleases" class="space-y-2">
            @forelse($this->releases as $release)
                <div class="p-4 rounded-md border border-border-color">
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium gap-1
                                @if($release->is_latest) bg-primary-blue/10 text-primary-blue
                                @elseif($release->is_prerelease) bg-secondary-grey/10 text-secondary-grey
                                @else bg-additional-green/10 text-additional-green
                                @endif">
                                <sl-icon wire:ignore name="tag" class="text-sm"></sl-icon>
                                @if($release->is_latest)
                                    Latest
                                @elseif($release->is_prerelease)
                                    Pre-release
                                @else
                                    Release
                                @endif
                            </span>
                            <span class="text-sm text-secondary-grey">
                                {{ $release->tag->name }}
                            </span>
                        </div>
                        <div class="text-sm text-secondary-grey">
                            {{ $release->created_at->diffForHumans() }}
                        </div>
                    </div>

                    <div class="mb-2">
                        <a href="{{ $release->url }}" target="_blank" class="text-sm font-medium text-primary-blue hover:underline">
                            {{ $release->name }}
                        </a>
                        @if($release->description)
                            <p class="mt-1 text-sm text-secondary-grey">
                                {{ $release->description }}
                            </p>
                        @endif
                        @if($release->tag)
                            <div class="flex flex-wrap gap-4 mt-2 text-xs text-secondary-grey">
                                <div class="flex gap-1 items-center">
                                    <sl-icon wire:ignore name="link-45deg" class="text-sm"></sl-icon>
                                    {{ $release->repository->name }}
                                </div>
                                <div class="flex gap-1 items-center">
                                    <sl-icon wire:ignore name="plus-circle" class="text-sm"></sl-icon>
                                    Added: {{ $release->tag->additions }}
                                </div>
                                <div class="flex gap-1 items-center">
                                    <sl-icon wire:ignore name="dash-circle" class="text-sm"></sl-icon>
                                    Removed: {{ $release->tag->deletions }}
                                </div>
                                <div class="flex gap-1 items-center">
                                    <sl-icon wire:ignore name="file-earmark-diff" class="text-sm"></sl-icon>
                                    Files: {{ $release->tag->changed_files }}
                                </div>
                                @if($release->tag->author)
                                    <div class="flex gap-1 items-center">
                                        <img src="{{ $release->tag->author->avatar_url }}" 
                                             alt="{{ $release->tag->author->name }}" 
                                             class="w-4 h-4 rounded-full">
                                        {{ $release->tag->author->name }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="flex gap-2 justify-center items-center py-4 text-center text-secondary-grey">
                    <sl-icon wire:ignore name="tag" class="text-lg"></sl-icon>
                    No Releases Found
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $this->releases->links() }}
    </div>
</div>
