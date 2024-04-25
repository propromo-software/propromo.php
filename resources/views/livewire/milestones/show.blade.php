<?php

use Livewire\Volt\Component;
use \App\Models\Milestone;


new class extends Component {

    public Milestone $milestone;

    public $scope;
    protected $queryString = ['scope'];

    public function mount(Milestone $milestone)
    {
        $this->milestone = $milestone;
    }


}; ?>

<div>
</div>
