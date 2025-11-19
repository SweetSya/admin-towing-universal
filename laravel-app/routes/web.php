<?php

use Illuminate\Support\Facades\Route;
use App\Services\NotifyService;

Route::get('/login', 'LoginController@view')->name('login');

// Example of using NotifyService in a route
Route::get('/dashboard', function () {
    $notifyService = new NotifyService();
    return redirect('/home')->with($notifyService->notify('success', 'Welcome to your dashboard!'));
});