<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;

class ShowProjects extends Component
{
    public function render()
    {
        return view('livewire.show-projects',[
            'projects' => Project::all()
        ]);
    }
}
