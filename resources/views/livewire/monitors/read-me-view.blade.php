<?php

use Livewire\Volt\Component;

new class extends Component {
    public $markdown_content;

    public function mount($markdown)
    {

    }

}; ?>
<div>
    {{ $markdown_content }}
</div>
