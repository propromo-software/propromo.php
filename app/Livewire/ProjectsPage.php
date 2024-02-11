<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;


#[Layout('layouts.app')]
class ProjectsPage extends Component
{
    public function render()
    {
        return view('livewire.pages.projects-page');
    }
}
