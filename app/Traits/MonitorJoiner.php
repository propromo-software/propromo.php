<?php

namespace App\Traits;

use App\Models\Monitor;
use App\Models\User;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Auth;


trait MonitorJoiner
{
    /**
     * @throws Exception
     */
    public function join_monitor($monitor_input)
    {
        $monitor_hash = $monitor_input;
        if(Str::contains($monitor_input, 'join')){
            $monitor_hash = Str::after($monitor_hash,'join/');
        }
        $monitor = Monitor::whereMonitorHash($monitor_hash)->first();

        if (!is_null($monitor)) {
            $current_user_projects = User::find(Auth::user()->id)
                ->monitors()
                ->where("monitor_hash", "=", $monitor_hash)
                ->get();

            if ($current_user_projects->count() > 0) {
                throw new Exception("You have already joined the monitor!");
            } else {
                $monitor->users()->attach(Auth::user()->id);
                return $monitor;
            }
        } else {
            throw new Exception("No monitor with that ID found!");
        }
    }

}
