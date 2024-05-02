<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Monitor;

class RepositoryController extends Controller
{
    public function get_repository_by_monitor_hash($monitor_hash)
    {
        $monitor = Monitor::whereMonitorHash($monitor_hash)->first();
        if( $monitor ){
            $repositories = $monitor->repositories()->get();
            return response()->json([
                'success' => true,
                'repositories' => $repositories
            ], 200);
        }else{
            return response()->json([
                'success' => true,
                'repositories' => []
            ], 200);
        }
    }

    public function get_repository_by_monitor_id($id)
    {
        $monitor = Monitor::whereId($id)->first();
        if( $monitor ){
            $repositories = $monitor->repositories()->get();
            return response()->json([
                'success' => true,
                'repositories' => $repositories
            ], 200);
        }else{
            return response()->json([
                'success' => true,
                'repositories' => []
            ], 200);
        }
    }
}
