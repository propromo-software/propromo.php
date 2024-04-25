<?php
namespace App\Traits;
use App\Models\Monitor;

use Exception;


trait MonitorJoinerApi
{
    /**
     * @throws Exception
     */
    public function join_monitor_api($monitor_hash, $user)
    {
        $monitor = Monitor::whereMonitorHash($monitor_hash)->first();
        if (!is_null($monitor)) {
            $current_user_projects = $user
                ->monitors()
                ->where("monitor_hash", "=", $monitor_hash)
                ->get();
            if ($current_user_projects->count() > 0) {
                throw new Exception("You have already joined the monitor!");
            } else {
                $monitor->users()->attach($user->id);
                return $monitor;
            }
        } else {
            throw new Exception("No monitor found!");
        }
    }
}
