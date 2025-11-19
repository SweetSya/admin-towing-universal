<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware(['device.remembering'])->group(function () {
    // Login & Authentication
    Route::get('/', [LoginController::class, 'view'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout']);
    // Forgot Password
    Route::get('/forgot-password', function () {
        return view('authentication.pages.forgot-password');
    });

    // Protected Routes
    Route::middleware(['check.auth'])->group(function () {
        // Dashboard Page
        Route::get('/dashboard', function () {
            return 'Dashboard';
        });
    });
});
