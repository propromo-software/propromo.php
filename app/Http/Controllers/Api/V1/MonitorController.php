<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Monitor;
use App\Models\User;
use App\Traits\MonitorJoiner;
use App\Traits\MonitorJoinerApi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitorController extends Controller
{
    use MonitorJoiner, MonitorJoinerApi;

    /**
     * @throws Exception
     */
    public function join($monitor_hash)
    {
        if (Auth::check()) {
            try {
                $monitor = $this->join_monitor($monitor_hash);
                return redirect('/monitors/' . $monitor->id);
            } catch (Exception $e) {
                return redirect('/join');
            }
        } else {
            return redirect('/register');
        }
    }

    /**
     * @throws Exception
     */
    public function join_api(Request $request)
    {
        $user = User::where('email', '=', $request->email)->first();
        if ($user->exists()) {
            try {
                $monitor = $this->join_monitor_api($request->monitorHash, $user);
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully joined monitor!',
                    'monitor' => $monitor
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 401);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No user found!'
            ], 401);
        }
    }

    public function show($email)
    {
        $user = User::where("email", "=", $email)->first();
        if($user){
            $monitors = $user->monitors();
            return response()->json([
                'success' => true,
                'monitors' => $monitors->get()
            ], 200);
        }else{
            return response()->json([
                'success' => true,
                'monitors' => []
            ], 200);
        }
    }
}
