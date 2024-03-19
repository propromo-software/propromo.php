<?php

use Livewire\Volt\Component;
use \Illuminate\Support\Str;

new class extends Component {

    public $markdown_content;
    public $project_url;

    public function mount($monitor)
    {
        $this->markdown_content = is_null($monitor->readme) ? null : Str::markdown($monitor->readme);
        $this->project_url = $monitor->project_url;
    }

}; ?>
<div>
    <div class="border-other-grey border-2 rounded-2xl p-6">
        <sl-icon name="info-circle" class="text-6xl font-bold text-primary-blue"></sl-icon>
        <div class="prose mt-4">
            @php
                $markdown_content_html = is_null($markdown_content) ? "<h2 class='text-2xl font-koulen text-secondary-grey'>No content available!</h2>" : $markdown_content;
            @endphp
            {!! $markdown_content_html !!}
        </div>
        <div class="w-min mt-12">
            <sl-button>
                <a href="{{ $project_url }}" target="_blank">View Source</a>
            </sl-button>
        </div>
    </div>
</div>
