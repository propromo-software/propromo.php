<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Volt::route('/', '/home/index');

Volt::route('/login', 'auth.login');

Route::get('/logout', function(){
    Auth::logout();
    return Redirect::to('login');
});

Volt::route('/projects', 'projects.index');
Volt::route('/create-monitor', 'auth.create-monitor');
Volt::route('/join', 'auth.join-monitor');
Volt::route('/register', 'auth.register');


Volt::route('/repos', 'repositories.list');
