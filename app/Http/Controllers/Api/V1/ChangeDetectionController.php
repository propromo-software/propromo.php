<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\ApiChanged;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChangeDetectionController extends Controller
{

    public function detected(Request $request)
    {
        ApiChanged::dispatch();
        return response()->json(['api-changed' => 'Successfully dispatched event']);
    }
}
