<?php

namespace App\Livewire;

use Exception;
use Illuminate\Support\Facades\Auth;

;

use Livewire\Component;
use App\Traits\ProjectCreator;

class JoinProjectForm extends Component
{

    use ProjectCreator;

    public $createProjectError;

    protected $rules = [
        'projectUrl' => 'required|min:10|max:2048'
    ];

    public $projectUrl;

    /**
     * @throws \Throwable
     */
    public function save() {
        $this->validate();

        if (Auth::check()) {

            $this->validate();
            try {
                $project = $this->createProject($this->projectUrl);
                $this->redirect('/projects/' . $project->id);
            } catch (Exception $e) {
                $this->createProjectError = $e->getMessage();
            }
        } else {
            $this->redirect('/register');
        }
    }

    public function render()
    {
        return view('livewire.join-project-form');
    }
}
