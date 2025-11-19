<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmail;
use App\Mail\PasswordResetCode;
use App\Models\ForgotPassword;
use App\Models\User;
use App\Services\NotifyService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Login & Logout
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

            return response()->json([
                'message' => 'Login successful! Redirecting..',
                'redirect' => '/dashboard'
            ]);
        }

        return response()->json([
            'message' => 'The provided credentials do not match our records.'
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with(
            NotifyService::success('You have been logged out successfully.')
        );
    }

    // Forgot Password
    public function viewForgotPassword()
    {
        return view('authentication.pages.forgot-password');
    }
    protected function generateResetCode(): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';

        for ($i = 0; $i < 6; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $code;
    }

    public function getResetCode(Request $request)
    {
        $forgotPassword = ForgotPassword::create([
            'email' => $request->input('email'),
            'code' => $this->generateResetCode(),
            'expires_at' => now()->addMinutes(15)
        ]);

        try {
            SendEmail::dispatch(
                [$request->input('email')],
                new PasswordResetCode($forgotPassword->code)
            );
            return response()->json([
                'message' => 'A reset code has been sent to your email.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send reset code email. Please try again later.'
            ], 500);
        }
    }

    public function checkResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6'
        ]);
        
        $user = User::where('email', $request->input('email'))->first();
        if (!$user) {
            return response()->json([
                'message' => 'No user found with the provided email.'
            ], 404);
        }
        
        $forgotPassword = ForgotPassword::where('email', $request->input('email'))
            ->where('code', strtoupper($request->input('code')))
            ->where('expires_at', '>', now())
            ->first();
            
        if (!$forgotPassword) {
            return response()->json([
                'message' => 'Invalid or expired reset code.'
            ], 400);
        }
        
        return response()->json([
            'message' => 'Reset code verified successfully.'
        ]);
    }
    
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed'
        ]);
        
        $user = User::where('email', $request->input('email'))->first();
        if (!$user) {
            return response()->json([
                'message' => 'No user found with the provided email.'
            ], 404);
        }
        
        $forgotPassword = ForgotPassword::where('email', $request->input('email'))
            ->where('code', strtoupper($request->input('code')))
            ->where('expires_at', '>', now())
            ->first();
            
        if (!$forgotPassword) {
            return response()->json([
                'message' => 'Invalid or expired reset code.'
            ], 400);
        }
        
        // Update user password
        $user->update([
            'password' => bcrypt($request->input('password'))
        ]);
        
        // Delete used reset code
        $forgotPassword->delete();
        
        return response()->json([
            'message' => 'Password has been reset successfully.'
        ]);
    }
}
