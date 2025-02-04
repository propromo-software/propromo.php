<?php

namespace App\Services;

use App\Models\Monitor;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class MonitorJoinerService.
 */
class MonitorJoinerService
{
    /**
     * @throws Exception
     */
    public function join_monitor(string $monitorInput): Monitor
    {
        $monitorHash = Str::contains($monitorInput, 'join/')
            ? Str::after($monitorInput, 'join/')
            : $monitorInput;

        $monitor = Monitor::where('monitor_hash', $monitorHash)->first();

        if (!$monitor) {
            throw new Exception("Monitor not found with hash: {$monitorHash}");
        }

        $user = Auth::user();
        if (!$user) {
            throw new Exception("Unauthorized: No authenticated user.");
        }

        $alreadyJoined = $user->monitors()
            ->where('monitor_hash', $monitorHash)
            ->exists();

        if ($alreadyJoined) {
            throw new Exception("You have already joined this monitor!");
        }

        $monitor->users()->attach($user->id);

        return $monitor;
    }
}
