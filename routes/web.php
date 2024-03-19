<?php

use App\Http\Controllers\Auth\GithubController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', '/home/index')->name('home.index');

Volt::route('/login', 'auth.login');

Route::get('/logout', function(){
    Auth::logout();
    return Redirect::to('login');
});

Route::get('/auth/github', [GithubController::class,'redirect'])->name('github.login');
Route::get('/auth/github/callback', [GithubController::class,'callback']);

Volt::route('/monitors', 'monitors.index');
Volt::route('/monitors/{monitor}', 'monitors.show');

Volt::route('/create-monitor', 'auth.create-monitor');
Volt::route('/join', 'auth.join-monitor');
Volt::route('/register', 'auth.register');

Volt::route('/repos', 'repositories.list');


