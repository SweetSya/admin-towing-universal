<?php

namespace App\Http\Controllers;

use App\Services\NotifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function view()
    {
        if (Auth::check()) {
            return redirect('/dashboard')->with(
                NotifyService::info('You are already logged in.')
            );
        }
        return view('authentication.pages.login');
    }

    public function login(Request $request)
    {
        // Validate request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->boolean('remember');

        // Attempt login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Store device ID in user record if "remember me" is checked
            if ($remember && $request->cookie('device_id')) {
                $user = Auth::user();
                $user->remember_device = $request->cookie('device_id');
                $user->save();
            }
            
            return redirect()->intended('/dashboard')->with(
                NotifyService::success('Welcome back! You have been logged in successfully.')
            );
        }

        return back()->withInput($request->only('email'))->with(
            NotifyService::error('Invalid credentials. Please check your email and password.')
        );
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with(
            NotifyService::success('You have been logged out successfully.')
        );
    }
}