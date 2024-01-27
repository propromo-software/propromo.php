<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\HomePage;
use App\Livewire\ShowProject;
use Illuminate\Support\Facades\Route;
use \App\Models\Project;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', HomePage::class);

Route::get('/projects', function () {
    return view('projects');
})->middleware(['auth', 'verified'])->name('projects');


Route::get('/projects/{project}', ShowProject::class);


Route::get('/projects/{project}/milestones/{milestone}', ShowProject::class);



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
