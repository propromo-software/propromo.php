<?php

namespace App\Livewire;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Session;

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

            // https://github.com/orgs/propromo-software/projects/1/views/1
            $project = Project::create([
                "project_url" => $this->projectUrl,
                "project_hash" => $projectHash,
                "organisation_name" => Str::between($this->projectUrl, '/orgs/', '/projects/'),
                "project_identification" => intval(Str::between($this->projectUrl, '/projects/', '/views/')),
                "project_view" => intval(Str::after($this->projectUrl, '/views/')),
                "user_id" => Auth::user()->id
            ]);

            Session::put('project',$project);

            $this->redirect('/projects');

        }else{
            $this->redirect('/register');
        }
    }

    public function render()
    {
        return view('livewire.join-project-form');
    }
}
