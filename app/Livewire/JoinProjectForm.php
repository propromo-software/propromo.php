<?php

namespace App\Livewire;

use App\Models\Project;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Traits\ProjectCreator;

class JoinProjectForm extends Component
{

    use ProjectCreator;

    protected $rules = [
        'projectUrl' => 'required|min:10|max:2048'
    ];

    public $projectUrl;

    /**
     * @throws \Throwable
     */
    public function save()
    {
        $this->validate();

        if (Auth::check()) {

            $this->validate();

            $project = $this->createProject($this->projectUrl);

            $this->redirect('/projects/' . $project->id);

        } else {
            $this->redirect('/register');
        }
    }

    public function render()
    {
        return view('livewire.join-project-form');
    }
}
