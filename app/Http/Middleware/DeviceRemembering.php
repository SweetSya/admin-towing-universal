<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class DeviceRemembering
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $deviceId = $request->cookie('device_id');
        
        if (!$deviceId) {
            // Generate new device ID if it doesn't exist
            $deviceId = $this->generateDeviceId();
        }
        
        // Always refresh the cookie on every visit (extends expiry)
        Cookie::queue('device_id', $deviceId, 60 * 24 * 60); // 60 days
        
        return $next($request);
    }
    
    /**
     * Generate a unique device ID
     */
    private function generateDeviceId(): string
    {
        return bin2hex(random_bytes(16)) . time();
    }
}