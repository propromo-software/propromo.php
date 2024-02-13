<?php

namespace App\Livewire;

use App\Models\Assignee;
use App\Models\Label;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ShowMilestones extends Component
{
    public $milestones;

    public function mount($project)
    {
        if (!Cache::has("milestones_$project->project_hash")) {

            $url = 'https://propromo-rest-de8dfcad6586.herokuapp.com/github/orgs/' . $project->organisation_name . '/projects/' . $project->project_identification . '/views/' . $project->project_view;
            $response = Http::get($url);

            if ($response->successful()) {

                $milestones = $response->json()['data']['organization']['projectV2']['repositories']['nodes'][0]['milestones']['nodes'];

                // delete existing milestone & tasks
                $project->milestones()->delete();

                // save milestone-data into db
                foreach ($milestones as $milestoneData) {

                    $milestone = new Milestone();

                    $milestone->progress = $milestoneData['progressPercentage'];
                    $milestone->description = $milestoneData['description'];
                    $milestone->title = $milestoneData['title'];
                    $milestone->url = $milestoneData['url'];
                    $milestone->state = $milestoneData['state'];
                    $milestone->closed_issues_count = $milestoneData['open_issues']['totalCount'];
                    $milestone->open_issues_count = $milestoneData['closed_issues']['totalCount'];

                    // save the milestone to the db
                    $project->milestones()->save($milestone);

                    // save the single tasks
                    $open_issues = $milestoneData['open_issues']['nodes'];
                    $closed_issues = $milestoneData['closed_issues']['nodes'];

                    $issues = array_merge($open_issues, $closed_issues);

                    // delete the tasks
                    $milestone->tasks()->delete();

                    // collect every issue -> tasks
                    foreach ($issues as $issue) {
                        $task = new Task();

                        $task->created_at = date('Y-m-d H:i:s', strtotime($issue['createdAt']));
                        $task->updated_at = !empty($issue['updatedAt']) ? date('Y-m-d H:i:s', strtotime($issue['updatedAt'])) : null;
                        $task->last_edited_at = !empty($issue['lastEditedAt']) ? date('Y-m-d H:i:s', strtotime($issue['lastEditedAt'])) : null;
                        $task->closed_at = !empty($issue['closedAt']) ? date('Y-m-d H:i:s', strtotime($issue['closedAt'])) : null;
                        $task->is_active = empty($issue['closedAt']);
                        $task->body_url = $issue['bodyUrl'];
                        $task->url = $issue['url'];
                        $task->body = $issue['body'];
                        $task->title = $issue['title'];

                        $milestone->tasks()->save($task);

                        // remove all associated assignees
                        $task->assignees()->delete();

                        $assignees = $issue['assignees']['nodes'];

                        foreach ($assignees as $assigneeData) {
                            $assignee = new Assignee();

                            $assignee->avatar_url = $assigneeData['avatarUrl'];
                            $assignee->email = $assigneeData['email'];
                            $assignee->login = $assigneeData['login'];
                            $assignee->name = $assigneeData['name'];
                            $assignee->pronouns = $assigneeData['pronouns'];
                            $assignee->url = $assigneeData['url'];
                            $assignee->website_url = $assigneeData['websiteUrl'];

                            $task->assignees()->save($assignee);

                        }

                        $task->labels()->delete();

                        // parse labels out of url
                        $labels = $issue['labels']['nodes'];

                        foreach ($labels as $labelData){
                            $label = new Label();

                            $label->url = $labelData['url'];
                            $label->name = $labelData['name'];
                            $label->color = $labelData['color'];
                            $label->created_at = $labelData['createdAt'];
                            $label->updated_at = $labelData['updatedAt'];
                            $label->description = $labelData['description'];

                            $task->labels()->save($label);
                        }
                    }
                }
                $this->milestones = $project->milestones()->get()->toArray();

                Cache::store("file")->put("milestones_$project->project_hash", $this->milestones, 600);

            } else {
                $this->milestones = [];
            }
        } else {
            $this->milestones = Cache::get("milestones_$project->project_hash");
        }
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <sl-spinner></sl-spinner>
        </div>
        HTML;
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
