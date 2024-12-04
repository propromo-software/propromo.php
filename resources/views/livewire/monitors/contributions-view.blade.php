<div class="p-8">
    <div class="mb-4">
        <a href="/monitors/{{ $monitor->id }}" title="Zurück zum Monitor" class="flex items-center p-3 text-lg font-bold border-2 rounded-md text-secondary-grey font-sourceSansPro border-other-grey">
            <sl-icon class="p-2 text-4xl border-2 rounded-md cursor-pointer text-primary-blue border-other-grey" name="arrow-left-short" wire:ignore></sl-icon>
            <span class="ml-2">{{ strtoupper($monitor->type == 'USER' ? $monitor->login_name : $monitor->organization_name) }} / {{ strtoupper($monitor->title) }}</span>
        </a>
    </div>

    <div class="space-y-4">
        @forelse($contributions as $contribution)
            <div class="pb-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold">
                    <a href="{{ $contribution->commit_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                        {{ $contribution->message_headline }}
                    </a>
                </h3>
                <p class="text-sm text-gray-500">{{ $contribution->message_body }}</p>
                <div class="flex items-center mt-2 space-x-2">
                    @if(isset($contribution->authors) && is_array($contribution->authors) && count($contribution->authors) > 0)
                        @foreach($contribution->authors as $author)
                            <a href="{{ $author['avatarUrl'] }}" target="_blank" class="relative group">
                                <img src="{{ $author['avatarUrl'] }}" alt="{{ $author['name'] }}" class="w-8 h-8 border border-gray-300 rounded-full" />
                                <span class="absolute hidden px-2 py-1 mb-1 text-xs text-white whitespace-no-wrap transform -translate-x-1/2 bg-gray-700 rounded left-1/2 bottom-full group-hover:block">{{ $author['name'] }}</span>
                            </a>
                        @endforeach
                    @else
                        <span class="text-gray-500">Keine Autoren verfügbar</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-4 text-center text-gray-500">
                Keine Beiträge gefunden
            </div>
        @endforelse
    </div>
</div>
