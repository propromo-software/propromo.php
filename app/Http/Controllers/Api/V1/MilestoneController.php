<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Milestone;

class MilestoneController extends Controller
{
    public function get_by_repository_id($repository_id)
    {
        $milestones = Milestone::whereRepositoryId($repository_id);
        return response()->json([
            'success' => true,
            'milestones' => $milestones->get()
        ], 200);

    }
}
