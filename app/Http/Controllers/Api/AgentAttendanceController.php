<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AgentAttendance;
use App\Models\AgentBreak;
use Carbon\Carbon;

class AgentAttendanceController extends Controller
{
    /**
     * Get attendance records filtered by month
     */
    public function getByMonth(Request $request)
    {
        $userId = Auth::id();
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        
        $attendanceRecords = AgentAttendance::where('user_id', $userId)
            ->where(function($query) use ($month, $year) {
                $query->whereMonth('login_time', $month)
                    ->whereYear('login_time', $year)
                    ->orWhere(function($q) use ($month, $year) {
                        $q->whereMonth('clock_in', $month)
                          ->whereYear('clock_in', $year);
                    });
            })
            ->orderByRaw('COALESCE(login_time, clock_in) DESC')
            ->get()
            ->map(function($record) {
                return [
                    'id' => $record->id,
                    'date' => $record->login_time ? $record->login_time->format('M j, Y') : 
                            ($record->clock_in ? Carbon::parse($record->clock_in)->format('M j, Y') : ''),
                    'day' => $record->login_time ? $record->login_time->format('l') : 
                            ($record->clock_in ? Carbon::parse($record->clock_in)->format('l') : ''),
                    'login_time' => $record->login_time ? $record->login_time->format('g:i A') : 
                                  ($record->clock_in ? Carbon::parse($record->clock_in)->format('g:i A') : ''),
                    'logout_time' => $record->logout_time ? $record->logout_time->format('g:i A') : 
                                   ($record->clock_out ? Carbon::parse($record->clock_out)->format('g:i A') : null),
                    'hours' => $this->calculateHours($record),
                    'status' => ucfirst($record->status ?? 'present'),
                    'address' => $record->address ?? $record->clock_in_address,
                ];
            });
            
        // Get month statistics
        $monthlyStats = [
            'total_days' => $attendanceRecords->count(),
            'total_hours' => $attendanceRecords->sum(function($record) {
                if (isset($record['hours']) && is_numeric($record['hours'])) {
                    return $record['hours'];
                }
                return 0;
            }),
            'average_hours' => $attendanceRecords->count() > 0 ? 
                $attendanceRecords->sum(function($record) {
                    if (isset($record['hours']) && is_numeric($record['hours'])) {
                        return $record['hours'];
                    }
                    return 0;
                }) / $attendanceRecords->count() : 0,
        ];
        
        return response()->json([
            'success' => true,
            'records' => $attendanceRecords,
            'stats' => $monthlyStats,
        ]);
    }
    
    /**
     * Calculate hours worked from a record
     */
    private function calculateHours($record)
    {
        if ($record->logout_time && $record->login_time) {
            return $record->logout_time->diffInHours($record->login_time);
        } else if ($record->clock_out && $record->clock_in) {
            return Carbon::parse($record->clock_out)->diffInHours(Carbon::parse($record->clock_in));
        } elseif ($record->login_time) {
            // If still logged in, calculate from login time until now
            return now()->diffInHours($record->login_time);
        } elseif ($record->clock_in) {
            // If still logged in (old columns), calculate from clock in time until now
            return now()->diffInHours(Carbon::parse($record->clock_in));
        }
        return 0;
    }
    
    /**
     * Get active break status
     */
    public function getBreakStatus()
    {
        $userId = Auth::id();
        
        $activeBreak = AgentBreak::where('user_id', $userId)
            ->whereDate('date', today())
            ->whereNull('end_time')
            ->first();
        
        return response()->json([
            'success' => true,
            'onBreak' => $activeBreak ? true : false,
            'break' => $activeBreak ? [
                'id' => $activeBreak->id,
                'start_time' => $activeBreak->start_time->format('g:i A'),
                'break_type' => ucfirst($activeBreak->break_type),
                'duration' => now()->diffInMinutes($activeBreak->start_time) . ' minutes',
            ] : null,
        ]);
    }
    
    /**
     * Get break history
     */
    public function getBreakHistory(Request $request)
    {
        $userId = Auth::id();
        $date = $request->input('date', today()->toDateString());
        
        $breaks = AgentBreak::where('user_id', $userId)
            ->whereDate('date', $date)
            ->orderBy('start_time', 'desc')
            ->get()
            ->map(function($break) {
                return [
                    'id' => $break->id,
                    'start_time' => $break->start_time->format('g:i A'),
                    'end_time' => $break->end_time ? $break->end_time->format('g:i A') : null,
                    'duration' => $break->formatted_duration,
                    'break_type' => ucfirst($break->break_type),
                    'reason' => $break->reason,
                    'is_active' => $break->is_active,
                ];
            });
        
        return response()->json([
            'success' => true,
            'date' => $date,
            'breaks' => $breaks,
            'total_break_minutes' => $breaks->sum(function($break) {
                if (isset($break['duration']) && is_numeric($break['duration'])) {
                    return $break['duration'];
                }
                return 0;
            }),
        ]);
    }
}
