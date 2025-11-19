<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware(['device.remembering'])->group(function () {
    // Login & Authentication
    Route::get('/', [LoginController::class, 'view'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/logout', [LoginController::class, 'logout']);

    // Forgot Password
    Route::get('/forgot-password', [LoginController::class, 'viewForgotPassword'])->name('forgot.password');
    Route::post('/forgot-password/get-reset-code', [LoginController::class, 'getResetCode']);
    Route::post('/forgot-password/check-reset-code', [LoginController::class, 'checkResetCode']);
    Route::post('/forgot-password/reset', [LoginController::class, 'resetPassword']);

    // Protected Routes
    Route::middleware(['check.auth'])->group(function () {
        // Dashboard Page
        Route::get('/dashboard', function () {
            return 'Dashboard';
        });
    });
});
