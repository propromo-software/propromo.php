<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Monitor;
use App\Models\User;
use App\Traits\MonitorJoiner;
use App\Traits\MonitorJoinerApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MonitorController extends Controller
{
    use MonitorJoiner, MonitorJoinerApi;

    /**
     * Join a monitor via web route.
     *
     * @param string $monitor_hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function join(string $monitor_hash)
    {
        if (!Auth::check()) {
            return redirect('/register');
        }

        try {
            $monitor = $this->join_monitor($monitor_hash);
            return redirect('/monitors/' . $monitor->id);
        } catch (\Exception $e) {
            Log::error("Monitor join failed: " . $e->getMessage());
            return redirect('/join')->with('error', 'Failed to join monitor.');
        }
    }

    /**
     * Join a monitor via API.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function join_api(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'monitorHash' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No user found!'
            ], 404);
        }

        try {
            $monitor = $this->join_monitor_api($request->monitorHash, $user);
            return response()->json([
                'success' => true,
                'message' => 'Successfully joined monitor!',
                'monitor' => $monitor
            ], 200);
        } catch (\Exception $e) {
            Log::error("API Monitor join failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to join monitor.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show all monitors associated with a user.
     *
     * @param string $email
     * @return JsonResponse
     */
    public function show(string $email): JsonResponse
    {
        $user = User::where("email", $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
                'monitors' => []
            ], 404);
        }

        return response()->json([
            'success' => true,
            'monitors' => $user->monitors()->get()
        ], 200);
    }
}
