<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class ShowMilestones extends Component
{
    public $milestones;

    public function mount($project)
    {
        if(!Cache::has("milestones_$project->project_hash")){

            $url = 'https://propromo-rest-de8dfcad6586.herokuapp.com/github/url/orgs/' . $project->organisation_name .'/projects/' . $project->project_identification . '/views/'. $project->project_view;
            $response = Http::get($url);

            if ($response->successful()) {

                $this->milestones = $response->json()['data']['organization']['projectV2']['repositories']['nodes'][0]['milestones']['nodes'];

                Cache::store('redis')->put("milestones_$project->project_hash", $this->milestones , 600);

            } else {
                $this->milestones = [];
            }
        } else {
            $this->milestones = Cache::get("milestones_$project->project_hash");
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
