<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DailyPassword extends Model
{
    protected $fillable = ['password_code', 'password_date', 'generated_at'];
    
    protected $casts = [
        'password_date' => 'date',
        'generated_at' => 'datetime',
    ];
    
    /**
     * Get today's password, generate if doesn't exist
     */
    public static function getTodaysPassword()
    {
        $today = Carbon::today();
        
        $password = self::where('password_date', $today)->first();
        
        if (!$password) {
            $password = self::generateTodaysPassword();
        }
        
        return $password->password_code;
    }
    
    /**
     * Generate a new 4-digit password for today
     */
    public static function generateTodaysPassword()
    {
        $today = Carbon::today();
        
        // Delete any existing password for today
        self::where('password_date', $today)->delete();
        
        // Generate new 4-digit code
        $code = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        
        return self::create([
            'password_code' => $code,
            'password_date' => $today,
            'generated_at' => Carbon::now(),
        ]);
    }
}
