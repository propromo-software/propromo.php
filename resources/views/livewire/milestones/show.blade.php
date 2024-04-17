<?php

use Livewire\Volt\Component;
use \App\Models\Milestone;


new class extends Component {
    public Milestone $milestone;

    public function mount(Milestone $milestone)
    {
        $this->milestone = $milestone;
    }


}; ?>

<div>

</div>
