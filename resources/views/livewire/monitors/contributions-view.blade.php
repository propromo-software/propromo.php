<div>
    @error('contributions')
        <div class="mb-4 text-sm text-red-500">{{ $message }}</div>
    @enderror

    <div class="space-y-4">
        @forelse($contributions as $contribution)
            <div class="pb-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold">{{ $contribution->message_headline }}</h3>
                <p class="text-sm text-gray-500">{{ $contribution->message_body }}</p>
                <a href="{{ $contribution->commit_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">Details</a>
            </div>
        @empty
            <div class="py-4 text-center text-gray-500">
                Keine Beiträge gefunden
            </div>
        @endforelse
    </div>
</div>
