<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MilestoneController extends Controller
{
    /**
     * Get milestones by repository ID.
     *
     * @param int $repository_id
     * @return JsonResponse
     */
    public function getByRepositoryId(int $repository_id): JsonResponse
    {
        try {
            // Check if milestones exist for the repository
            if (!Milestone::where('repository_id', $repository_id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No milestones found for this repository.'
                ], 404);
            }

            // Fetch milestones
            $milestones = Milestone::where('repository_id', $repository_id)->get();

            return response()->json([
                'success' => true,
                'milestones' => $milestones
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching milestones: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve milestones.'
            ], 500);
        }
    }
}
