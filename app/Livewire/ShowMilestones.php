<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ShowMilestones extends Component
{
    public $milestones;

    public function mount($project)
    {
        $url = 'https://propromo-rest-de8dfcad6586.herokuapp.com/github/url/orgs/' . $project->organisation_name .'/projects/' . $project->project_identification . '/views/'. $project->project_view;
        $response = Http::get($url);
        if ($response->successful()) {
            $this->milestones = $response->json()['data']['organization']['projectV2']['repositories']['nodes'][0]['milestones']['nodes'];
        } else {
            $this->milestones = [];
        }
    }

    public function render()
    {
        return view('livewire.show-milestones',
            [
                "milestones" => $this->milestones
            ]
        );
    }
}
