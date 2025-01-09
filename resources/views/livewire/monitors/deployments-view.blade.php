<div>
    @error('deployments')
    <div class="mb-4 text-sm text-red-500">{{ $message }}</div>
    @enderror

    <h2 class="mb-4 text-lg font-semibold">Deployments</h2>

    <div class="space-y-4">
        @forelse($deployments as $deployment)
            <div class="p-2 border rounded-md border-border-color">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium gap-1
                            @if($deployment->state === 'SUCCESS') bg-additional-green/10 text-additional-green
                            @elseif($deployment->state === 'INACTIVE') bg-secondary-grey/10 text-secondary-grey
                            @elseif($deployment->state === 'FAILURE') bg-additional-red/10 text-additional-red
                            @endif">
                            @if($deployment->state === 'SUCCESS')
                                <sl-icon name="check-circle" class="text-sm"></sl-icon>
                            @elseif($deployment->state === 'INACTIVE')
                                <sl-icon name="dash-circle" class="text-sm"></sl-icon>
                            @elseif($deployment->state === 'FAILURE')
                                <sl-icon name="x-circle" class="text-sm"></sl-icon>
                            @endif
                            {{ $deployment->state }}
                        </span>
                        <span class="text-sm text-secondary-grey">
                            {{ \Carbon\Carbon::parse($deployment->created_at)->diffForHumans() }}
                        </span>
                    </div>
                    <div class="flex space-x-4">
                        @if($deployment->log_url && $deployment->log_url !== '#')
                            <a href="{{ $deployment->log_url }}" target="_blank" 
                               class="flex items-center gap-1 text-sm text-primary-blue hover:text-primary-blue/80">
                                <sl-icon name="journal-text" class="text-sm"></sl-icon>
                                Logs
                            </a>
                        @else
                            <span class="flex items-center gap-1 text-sm text-secondary-grey">
                                <sl-icon name="journal-x" class="text-sm"></sl-icon>
                                No Logs
                            </span>
                        @endif
                        @if($deployment->environment_url && $deployment->environment_url !== '#')
                            <a href="{{ $deployment->environment_url }}" target="_blank" 
                               class="flex items-center gap-1 text-sm text-primary-blue hover:text-primary-blue/80">
                                <sl-icon name="box-arrow-up-right" class="text-sm"></sl-icon>
                                Deployment
                            </a>
                        @else
                            <span class="flex items-center gap-1 text-sm text-secondary-grey">
                                <sl-icon name="dash-circle-fill" class="text-sm"></sl-icon>
                                No Deployment
                            </span>
                        @endif
                    </div>
                </div>
                @if($deployment->description)
                    <p class="mt-2 text-sm text-secondary-grey">
                        {{ $deployment->description }}
                    </p>
                @endif
            </div>
        @empty
            <div class="flex items-center justify-center gap-2 py-4 text-center text-secondary-grey">
                <sl-icon name="cloud-slash" class="text-lg"></sl-icon>
                No Deployments Found
            </div>
        @endforelse
    </div>
</div>
