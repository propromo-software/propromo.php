<?php

namespace App\Livewire;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class JoinProjectForm extends Component
{
    public $projectUrl;

    public function save(){
        if(Auth::check()) {
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
