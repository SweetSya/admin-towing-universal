<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\NotifyService;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthentication
{
    public function handle(Request $request, Closure $next): Response
    {
        // If already authenticated, proceed
        if (Auth::check()) {
            return $next($request);
        }
        
        // Check for device remembering
        $deviceId = $request->cookie('device_id');
        if ($deviceId) {
            $user = User::where('remember_device', $deviceId)->first();
            if ($user) {
                Auth::login($user, true);
                return $next($request);
            }
        }
        // Not authenticated and no valid device ID
        return redirect('/')->with(
            NotifyService::warning('Please log in to access this page.')
        );
    }
}