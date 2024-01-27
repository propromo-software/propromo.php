<?php

namespace App\Livewire;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShowProjects extends Component
{
    public function render()
    {
        return view('livewire.show-projects',[
            'projects' => Project::where('user_id', Auth::user()->id)->get()
        ]);
    }
}
