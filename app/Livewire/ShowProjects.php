<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShowProjects extends Component
{
    public function mount(){

    }

    public function render()
    {
        return view('livewire.show-projects',[
            'projects' => User::find(Auth::user()->id)->projects()->get()
        ]);
    }
}
