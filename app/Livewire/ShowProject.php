<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ShowProject extends Component
{

    public $project;

    public function mount(Project $project){
        $this->project = $project;
    }

    public function render()
    {
        return view('livewire.show-project');
    }
}
