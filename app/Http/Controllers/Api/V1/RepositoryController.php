<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Monitor;
use Illuminate\Http\JsonResponse;

class RepositoryController extends Controller
{
    /**
     * Get repositories by monitor hash.
     *
     * @param string $monitor_hash
     * @return JsonResponse
     */
    public function getRepositoryByMonitorHash(string $monitor_hash): JsonResponse
    {
        return $this->getRepositoriesByMonitor('monitor_hash', $monitor_hash);
    }

    /**
     * Get repositories by monitor ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getRepositoryByMonitorId(int $id): JsonResponse
    {
        return $this->getRepositoriesByMonitor('id', $id);
    }

    /**
     * Fetch repositories by monitor attribute.
     *
     * @param string $attribute
     * @param mixed $value
     * @return JsonResponse
     */
    private function getRepositoriesByMonitor(string $attribute, mixed $value): JsonResponse
    {
        $monitor = Monitor::where($attribute, $value)->first();

        if (!$monitor) {
            return response()->json([
                'success' => false,
                'message' => 'Monitor not found.',
                'repositories' => []
            ], 404);
        }

        return response()->json([
            'success' => true,
            'repositories' => $monitor->repositories()->get()
        ], 200);
    }
}
