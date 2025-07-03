<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $user = \App\Models\User::where('email', $credentials['email'])->orWhere('username', $credentials['email'])->first();
        
        // Check account status first (new consolidated status)
        if ($user) {
            $accountStatus = $user->account_status ?? ($user->approval_status === 'pending' ? 'pending' : ($user->approval_status === 'approved' && $user->status === 'active' ? 'active' : 'revoked'));
            
            if ($accountStatus === 'revoked') {
                return back()->withErrors(['email' => 'Your account is currently deactivated. Please contact the administrator to reactivate your account.']);
            }
            
            if ($accountStatus === 'pending') {
                return back()->withErrors(['email' => 'Your account is pending admin approval. You will receive an email once approved.']);
            }
        }
        
        // Legacy status check for backward compatibility
        if ($user && $user->status === 'revoked') {
            return back()->withErrors(['email' => 'Your account is currently deactivated. Please contact the administrator to reactivate your account.']);
        }
        
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']]) ||
            Auth::attempt(['username' => $credentials['email'], 'password' => $credentials['password']])) {
            
            $user = Auth::user();
            
            // For existing users created before the new verification system,
            // auto-verify them if they don't have proper approval status
            if (!$user->email_verified && (is_null($user->approval_status) || empty($user->approval_status))) {
                $user->update([
                    'email_verified' => true,
                    'approval_status' => 'approved',
                    'account_status' => 'active',
                    'approved_at' => now(),
                    'approved_by' => null
                ]);
                $user->refresh(); // Refresh the model to get updated data
            }
            
            // Check email verification for new users only
            if (!$user->email_verified && $user->approval_status === 'pending') {
                Auth::logout();
                session(['registration_email' => $user->email]);
                return redirect()->route('email.verify')->withErrors(['email' => 'Please verify your email address before logging in.']);
            }
            
            // Check approval status (legacy check)
            if ($user->approval_status === 'pending') {
                Auth::logout();
                return redirect()->route('auth.pending-approval')->withErrors(['email' => 'Your account is pending admin approval. You will receive an email once approved.']);
            }
            
            if ($user->approval_status === 'rejected') {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is currently deactivated. Please contact the administrator to reactivate your account.']);
            }
            
            $request->session()->regenerate();
            
            if ($user->hasRole('admin')) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->hasRole('agent')) {
                return redirect()->intended(route('agent.dashboard'));
            } elseif ($user->hasRole('publisher')) {
                return redirect()->intended(route('publisher.dashboard'));
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account does not have a valid role.']);
            }
        }
        
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
