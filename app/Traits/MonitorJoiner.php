<?php

namespace App\Traits;

use App\Models\Monitor;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Exception;

trait MonitorJoiner
{
    /**
     * Join a monitor by its hash or join link.
     *
     * @param string $monitorInput The monitor hash or join link.
     * @return Monitor
     * @throws Exception If the monitor is not found or already joined.
     */
    public function joinMonitor(string $monitorInput): Monitor
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
