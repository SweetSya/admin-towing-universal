<?php

use Illuminate\Support\Facades\Route;

// Login Page
Route::get('/', function () {
    return view('authentication.pages.login');
});
Route::get('/forgot-password', function () {
    return view('authentication.pages.forgot-password');
});
