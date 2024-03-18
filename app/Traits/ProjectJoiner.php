<?php

namespace App\Traits;

use App\Models\Project;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

trait ProjectJoiner
{
    /**
     * @throws Exception
     */
    public function joinProject($project_hash)
    {
        $project = Project::whereProjectHash($project_hash)->first();

        if (!is_null($project)) {

            $current_user_projects = User::find(Auth::user()->id)
                ->projects()
                ->where("project_hash", "=", $project_hash)
                ->get();

            if ($current_user_projects->count() > 0) {
                throw new Exception("You have already joined the monitor!");
            } else {
                $project->users()->attach(Auth::user()->id);
                return $project;
            }
        } else {
            throw new Exception("No monitor with that ID found!");
        }
    }

}
