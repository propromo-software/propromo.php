<div class="rounded-lg">
    <h2 class="text-primary-blue text-xl font-bold mb-4 border-b pb-2">DEPLOYMENTS:</h2>

    @error('deployments')
    <div class="mb-4 text-sm text-red-500">{{ $message }}</div>
    @enderror

    <div class="space-y-4">
        @forelse($deployments as $deployment)
            <div class="flex justify-between items-center bg-gray-50 px-4 py-3 border-2 border-other-grey rounded-2xl">
                <div class="flex items-center space-x-4">
                    <span class="text-gray-400 text-xs uppercase tracking-wide font-semibold">
                        {{ $deployment->description ?? 'FIRST DEPLOYMENT' }}
                    </span>
                    <span class="text-gray-500 text-sm">
                        {{ \Carbon\Carbon::parse($deployment->created_at)->format('d.m.Y') }}
                    </span>
                </div>
                <div>
                    @if($deployment->environment_url)
                        <a href="{{ $deployment->environment_url }}" target="_blank"
                           class="bg-primary-blue text-white px-4 py-2 text-sm rounded-md hover:bg-blue-800 transition duration-200">
                            Visit
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-4 text-center text-gray-500">
                Keine Deployments gefunden
            </div>
        @endforelse
    </div>
</div>
