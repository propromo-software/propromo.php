<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\ApiChanged;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChangeDetectionController extends Controller
{
    /**
     * Handle API change detection event.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function detected(Request $request): JsonResponse
    {
        try {
            // Dispatch event
            ApiChanged::dispatch();

            // Optional logging
            Log::info('API change event dispatched successfully.');

            return response()->json([
                'status' => 'success',
                'message' => 'API change event dispatched successfully.',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error dispatching API change event: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to dispatch API change event.',
            ], 500);
        }
    }
}
