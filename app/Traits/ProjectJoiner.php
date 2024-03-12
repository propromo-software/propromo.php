<?php
namespace App\Traits;
use App\Models\Project;
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

        if(!is_null($project)){
            $project->users()->attach(Auth::user()->id);
            return $project;
        }
        throw new Exception("Jo host hoid ka soiches Projekt! Find di damit ob, OASCHLOCH!");
    }

}
