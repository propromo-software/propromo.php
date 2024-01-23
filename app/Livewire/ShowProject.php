<?php
namespace App\Livewire;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Session;

class ShowProject extends Component
{
    public $project;

    public function mount(){
        $this->project = Session::get("project");
    }

    public function render()
    {
        return view('livewire.show-project',[
           "name" => $this->project->id
        ]);
    }
}
