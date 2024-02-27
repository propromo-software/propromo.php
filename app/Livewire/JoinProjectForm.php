<?php

namespace App\Livewire;

use App\Models\Project;
use Couchbase\PrependOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Session;

class JoinProjectForm extends Component
{

    protected $rules = [
        'projectUrl' => 'required|min:10|max:2048',
    ];

    public $projectUrl;

    public function save()
    {

        $this->validate();

        if (Auth::check()) {
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
                "project_view" => intval(Str::after($this->projectUrl, '/views/'))
            ]);

            $url = 'https://propromo-rest.duckdns.org/v1/github/orgs/' . $project->organisation_name . '/projects/' . $project->project_identification . '/infos';
            $response = Http::get($url);

            if ($response->successful()) {
                $project_data = $response->json()['data']['organization']['projectV2'];

                $project->url = $project_data['url'];
                $project->short_description = $project_data['shortDescription'];
                $project->title = $project_data['title'];
                $project->public = $project_data['public'];
                $project->readme = $project_data['readme'];
            }


            $project->save();
            $project->users()->attach(Auth::user()->id);

            Session::put('project', $project);

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
