<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailVerification;
use App\Mail\EmailVerificationOTP;
use App\Mail\AdminNewUserNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create user but don't activate yet
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'agent', // Default role for new registrations
            'status' => 'inactive', // Set to inactive until approved
            'account_status' => 'pending', // Set new consolidated status
            'email_verified' => false,
            'approval_status' => 'pending',
        ]);

        // Generate and send OTP
        $verification = EmailVerification::generateOTP($user->email);
        Mail::to($user->email)->send(new EmailVerificationOTP($verification->otp, $user->name));

        // Store email in session for verification page
        session(['registration_email' => $user->email]);

        return redirect()->route('email.verify')->with('success', 'Registration successful! Please check your email for the verification code.');
    }

    /**
     * Display email verification view
     */
    public function showEmailVerification(): View
    {
        $email = session('registration_email');
        
        if (!$email) {
            return redirect()->route('register');
        }
        
        // Check if this user is already verified - if so, redirect appropriately
        $user = User::where('email', $email)->first();
        if ($user && $user->email_verified) {
            if ($user->approval_status === 'approved') {
                // User is verified and approved, redirect to login
                session()->forget('registration_email');
                return redirect()->route('login')->with('success', 'Your account is already verified. Please log in.');
            } elseif ($user->approval_status === 'pending') {
                // User is verified but pending approval
                return redirect()->route('auth.pending-approval');
            } elseif ($user->approval_status === 'rejected') {
                return redirect()->route('login')->withErrors(['email' => 'Your account registration has been rejected.']);
            }
        }
        
        return view('auth.verify-email');
    }

    /**
     * Handle email verification
     */
    public function verifyEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $email = session('registration_email');
        if (!$email) {
            return redirect()->route('register')->with('error', 'Session expired. Please register again.');
        }

        if (EmailVerification::verifyOTP($email, $request->otp)) {
            // Update user email verification status
            $user = User::where('email', $email)->first();
            $user->update([
                'email_verified' => true,
                'email_verified_at' => now(),
            ]);

            // Send notification to admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new AdminNewUserNotification($user));
            }

            // Clear session
            session()->forget('registration_email');

            return redirect()->route('auth.pending-approval')->with('success', 'Email verified successfully! Your account is now pending admin approval.');
        }

        return back()->withErrors(['otp' => 'Invalid or expired verification code.']);
    }

    /**
     * Resend email verification OTP
     */
    public function resendOTP(Request $request): RedirectResponse
    {
        $email = session('registration_email');
        if (!$email) {
            return redirect()->route('register')->with('error', 'Session expired. Please register again.');
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('register')->with('error', 'User not found. Please register again.');
        }

        // Generate new OTP
        $verification = EmailVerification::generateOTP($user->email);
        Mail::to($user->email)->send(new EmailVerificationOTP($verification->otp, $user->name));

        return back()->with('success', 'Verification code resent successfully!');
    }

    /**
     * Show pending approval page
     */
    public function showPendingApproval(): View
    {
        return view('auth.pending-approval');
    }
}
