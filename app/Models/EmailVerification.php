<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'otp',
        'expires_at',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    /**
     * Generate a new OTP for email verification
     */
    public static function generateOTP($email)
    {
        // Delete any existing OTPs for this email
        self::where('email', $email)->delete();

        // Generate a 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return self::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10), // OTP expires in 10 minutes
        ]);
    }

    /**
     * Verify OTP for email
     */
    public static function verifyOTP($email, $otp)
    {
        $verification = self::where('email', $email)
            ->where('otp', $otp)
            ->where('expires_at', '>', now())
            ->where('is_verified', false)
            ->first();

        if ($verification) {
            $verification->update([
                'is_verified' => true,
                'verified_at' => now(),
            ]);
            return true;
        }

        return false;
    }

    /**
     * Check if email is verified
     */
    public static function isEmailVerified($email)
    {
        return self::where('email', $email)
            ->where('is_verified', true)
            ->exists();
    }
}
