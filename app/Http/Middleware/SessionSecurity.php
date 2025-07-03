<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SecuritySetting;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionSecurity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $settings = SecuritySetting::getSettings();
        $user = Auth::user();
        $now = Carbon::now();

        // Check session timeout
        if ($settings->session_timeout_minutes > 0) {
            $lastActivity = $request->session()->get('last_activity');
            
            if ($lastActivity && Carbon::createFromTimestamp($lastActivity)->addMinutes($settings->session_timeout_minutes)->isPast()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')->with('message', 'Your session has expired. Please log in again.');
            }
            
            $request->session()->put('last_activity', $now->timestamp);
        }

        // Check password expiry
        if ($settings->password_expiry_days > 0 && $user->password_changed_at) {
            $expiryDate = Carbon::parse($user->password_changed_at)->addDays($settings->password_expiry_days);
            
            if ($now->isAfter($expiryDate)) {
                return redirect()->route('password.expired');
            }
            
            // Show warning 7 days before expiry
            if ($now->diffInDays($expiryDate) <= 7) {
                $request->session()->flash('warning', 'Your password will expire in ' . $now->diffInDays($expiryDate) . ' days.');
            }
        }

        // Force password reset if required
        if ($settings->password_reset_required && !$user->password_reset_completed) {
            return redirect()->route('password.reset.required');
        }

        return $next($request);
    }
}
