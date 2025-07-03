<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SecuritySetting;
use Carbon\Carbon;

class SessionSecurityMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $settings = SecuritySetting::first();
        
        if (auth()->check()) {
            $user = auth()->user();
            $session = $request->session();

            // Check session timeout
            $lastActivity = $session->get('last_activity');
            $timeout = Carbon::now()->subMinutes($settings->session_timeout_minutes);
            
            if ($lastActivity && Carbon::parse($lastActivity)->lt($timeout)) {
                auth()->logout();
                $session->flush();
                return redirect()->route('login')->with('error', 'Session expired. Please login again.');
            }
            
            // Update last activity
            $session->put('last_activity', Carbon::now());

            // IP binding check
            if ($settings->session_ip_binding) {
                $sessionIp = $session->get('auth_ip');
                $currentIp = $request->ip();
                
                if ($sessionIp && $sessionIp !== $currentIp) {
                    auth()->logout();
                    $session->flush();
                    return redirect()->route('login')->with('error', 'Session invalid. Please login again.');
                }
                
                if (!$sessionIp) {
                    $session->put('auth_ip', $currentIp);
                }
            }

            // Password expiry check
            if ($settings->password_expiry_days > 0) {
                $passwordUpdatedAt = $user->password_updated_at ?? $user->created_at;
                $expiryDate = Carbon::parse($passwordUpdatedAt)->addDays($settings->password_expiry_days);
                
                if (Carbon::now()->gt($expiryDate)) {
                    return redirect()->route('password.expired');
                }
            }
        }

        return $next($request);
    }
}
