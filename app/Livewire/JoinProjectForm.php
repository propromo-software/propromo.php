<?php

namespace App\Livewire;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Rule;
use Livewire\Component;

class JoinProjectForm extends Component
{

    #[Rule('required|min:10|max:2048')]
    public $projectUrl;

    public function save(){
        if(Auth::check()) {
            $this->validate();

            $projectHash = Hash::make($this->projectUrl, [
                'memory' => 516,
                'time' => 2,
                'threads' => 2,
            ]);

            Project::create([
                "project_url" => $this->projectUrl,
                "project_hash" => $projectHash,
                "user_id" => Auth::user()->id
            ]);
        }else{
            $this->redirect('/register');
        }
    }

    public function render()
    {
        return view('livewire.join-project-form');
    }
}
