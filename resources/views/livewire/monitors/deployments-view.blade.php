<div>
    @error('deployments')
        <div class="mb-4 text-sm text-red-500">{{ $message }}</div>
    @enderror

    <div class="space-y-4">
        @forelse($deployments as $deployment)
            <div class="pb-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($deployment->state === 'SUCCESS') bg-green-100 text-green-800
                            @elseif($deployment->state === 'INACTIVE') bg-gray-100 text-gray-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ $deployment->state }}
                        </span>
                        <span class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($deployment->created_at)->diffForHumans() }}
                        </span>
                    </div>
                    <div class="flex space-x-2">
                        @if($deployment->log_url)
                            <a href="{{ $deployment->log_url }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800">
                                Logs
                            </a>
                        @endif
                        @if($deployment->environment_url)
                            <a href="{{ $deployment->environment_url }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800">
                                Deployment
                            </a>
                        @endif
                    </div>
                </div>
                @if($deployment->description)
                    <p class="mt-2 text-sm text-gray-600">
                        {{ $deployment->description }}
                    </p>
                @endif
            </div>
        @empty
            <div class="py-4 text-center text-gray-500">
                Keine Deployments gefunden
            </div>
        @endforelse
    </div>
</div>
