<?php

use App\Http\Controllers\Api\V1\MonitorController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ChangeDetectionController;
use \App\Http\Controllers\Api\V1\RepositoryController;
use \App\Http\Controllers\Api\V1\MilestoneController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// api/v1/
Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function (){
    Route::apiResource('users', UserController::class);
    Route::post('users/login', [UserController::class,'login']);
    Route::post('/on-api-change', [ChangeDetectionController::class, 'detected']);
    Route::post('/join-monitor', [MonitorController::class, 'join_api']);
    Route::get('/monitors/{email}', [MonitorController::class, 'show']);
    Route::put('/users/update/email', [UserController::class, 'updateEmail']);
    Route::put('/users/update/password', [UserController::class, 'updatePassword']);
    Route::get('/repositories/{id}', [RepositoryController::class, 'get_repository_by_monitor_id']);
    Route::get('/milestones/{repository_id}', [MilestoneController::class, 'get_by_repository_id']);
    Route::get('/repositories/{monitor_hash}', [RepositoryController::class, 'get_repository_by_monitor_hash']);
});

