<?php

namespace App\Http\Controllers;

use App\Models\AgentAttendance;
use App\Models\AgentBreak;
use App\Models\AgentActivity;
use App\Models\AllowedLocation;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function clockIn(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric'
        ]);
        
        $userId = Auth::id();
        $today = today();
        
        // Check if already clocked in today
        $attendance = AgentAttendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();
            
        if ($attendance && $attendance->clock_in_time) {
            return response()->json([
                'success' => false,
                'message' => 'Already clocked in today'
            ]);
        }
        
        // Validate location against allowed locations
        $locationCheck = AllowedLocation::isLocationAllowed(
            $request->latitude,
            $request->longitude
        );
        
        if (!$locationCheck['allowed']) {
            $closestLocation = $locationCheck['closest_location'];
            $distance = round($locationCheck['closest_distance']);
            
            return response()->json([
                'success' => false,
                'message' => $closestLocation 
                    ? "Clock-in location too far from nearest allowed location ({$closestLocation->name}). You are {$distance}m away (max allowed: {$closestLocation->radius_meters}m)"
                    : "No allowed locations configured. Please contact your administrator.",
                'distance_meters' => $distance,
                'closest_location' => $closestLocation ? $closestLocation->name : null
            ], 422);
        }
        
        $allowedLocation = $locationCheck['location'];
        $distance = round($locationCheck['distance']);
        
        // Check shift timing
        $currentTime = now();
        $shiftConfig = config('attendance.night_shift');
        $gracePeriod = (int) $shiftConfig['grace_period_minutes'];
        
        $shiftStartTime = Carbon::createFromFormat('H:i', $shiftConfig['start']);
        $allowedStartTime = $shiftStartTime->copy()->subMinutes($gracePeriod);
        $lateStartTime = $shiftStartTime->copy()->addMinutes($gracePeriod);
        
        $isEarlyClockIn = $currentTime->lt($allowedStartTime);
        $isLateClockIn = $currentTime->gt($lateStartTime);
        
        $locationNotes = [];
        if ($isEarlyClockIn) {
            $locationNotes[] = "Early clock-in at {$currentTime->format('H:i')} (shift starts at {$shiftConfig['start']})";
        } elseif ($isLateClockIn) {
            $locationNotes[] = "Late clock-in at {$currentTime->format('H:i')} (grace period ends at {$lateStartTime->format('H:i')})";
        }
        
        // Get address and IP
        $address = LocationService::reverseGeocode($request->latitude, $request->longitude);
        $ipAddress = LocationService::getUserIP();
        
        // Create or update attendance record
        $attendanceData = [
            'clock_in_time' => $currentTime->format('H:i:s'),
            'clock_in_latitude' => $request->latitude,
            'clock_in_longitude' => $request->longitude,
            'clock_in_address' => $address,
            'clock_in_ip_address' => $ipAddress,
            'clock_in_distance_meters' => $distance,
            'location_verified' => true,
            'location_notes' => implode('; ', array_merge($locationNotes, ["Clocked in at {$allowedLocation->name}"])),
            'is_active' => true
        ];
        
        if ($attendance) {
            $attendance->update($attendanceData);
        } else {
            $attendanceData['user_id'] = $userId;
            $attendanceData['date'] = $today;
            $attendance = AgentAttendance::create($attendanceData);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Clocked in successfully' . ($isLateClockIn ? ' (Late)' : '') . " at {$allowedLocation->name}",
            'clock_in_time' => $attendance->clock_in_time,
            'distance_meters' => $distance,
            'location_name' => $allowedLocation->name,
            'is_late' => $isLateClockIn,
            'is_early' => $isEarlyClockIn
        ]);
    }
    
    public function clockOut(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);
        
        $userId = Auth::id();
        $today = today();
        
        $attendance = AgentAttendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();
            
        if (!$attendance || !$attendance->clock_in_time) {
            return response()->json([
                'success' => false,
                'message' => 'No clock-in record found for today'
            ]);
        }
        
        if ($attendance->clock_out_time) {
            return response()->json([
                'success' => false,
                'message' => 'Already clocked out today'
            ]);
        }
        
        // Validate location for clock-out against allowed locations
        $locationCheck = AllowedLocation::isLocationAllowed(
            $request->latitude,
            $request->longitude
        );
        
        $distance = 0;
        $locationName = 'Unknown';
        
        if ($locationCheck['allowed']) {
            $distance = round($locationCheck['distance']);
            $locationName = $locationCheck['location']->name;
        } else if ($locationCheck['closest_location']) {
            $distance = round($locationCheck['closest_distance']);
            $locationName = $locationCheck['closest_location']->name . ' (outside radius)';
        }
        
        // Get address and IP
        $address = LocationService::reverseGeocode($request->latitude, $request->longitude);
        $ipAddress = LocationService::getUserIP();
        
        $attendance->update([
            'clock_out_time' => now()->format('H:i:s'),
            'clock_out_latitude' => $request->latitude,
            'clock_out_longitude' => $request->longitude,
            'clock_out_address' => $address,
            'clock_out_ip_address' => $ipAddress,
            'clock_out_distance_meters' => $distance
        ]);
        
        return response()->json([
            'success' => true,
            'message' => "Clocked out successfully at {$locationName}",
            'clock_out_time' => $attendance->clock_out_time,
            'distance_meters' => $distance,
            'location_name' => $locationName,
            'location_verified' => $locationCheck['allowed']
        ]);
    }
    
    public function startBreak(Request $request)
    {
        $request->validate([
            'break_type' => 'required|in:regular,lunch,emergency',
            'reason' => 'nullable|string|max:255'
        ]);
        
        $userId = Auth::id();
        $today = today();
        
        // Check if there's an active break
        $activeBreak = AgentBreak::where('user_id', $userId)
            ->where('date', $today)
            ->whereNull('end_time')
            ->first();
            
        if ($activeBreak) {
            return response()->json([
                'success' => false,
                'message' => 'You are already on a break'
            ]);
        }
        
        $break = AgentBreak::create([
            'user_id' => $userId,
            'date' => $today,
            'start_time' => now()->format('H:i:s'),
            'break_type' => $request->break_type,
            'reason' => $request->reason
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Break started',
            'break_id' => $break->id,
            'start_time' => $break->start_time
        ]);
    }
    
    public function endBreak(Request $request)
    {
        $userId = Auth::id();
        $today = today();
        
        $break = AgentBreak::where('user_id', $userId)
            ->where('date', $today)
            ->whereNull('end_time')
            ->first();
            
        if (!$break) {
            return response()->json([
                'success' => false,
                'message' => 'No active break found'
            ]);
        }
        
        $break->update([
            'end_time' => now()->format('H:i:s')
        ]);
        
        $break->calculateDuration();
        
        // Update total break time in attendance
        $attendance = AgentAttendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();
            
        if ($attendance) {
            $totalBreakMinutes = AgentBreak::where('user_id', $userId)
                ->where('date', $today)
                ->whereNotNull('duration_minutes')
                ->sum('duration_minutes');
                
            $attendance->update(['total_break_minutes' => $totalBreakMinutes]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Break ended',
            'duration_minutes' => $break->duration_minutes
        ]);
    }
    
    public function getAttendanceStatus()
    {
        $userId = Auth::id();
        $today = today();
        
        $attendance = AgentAttendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();
            
        $activeBreak = AgentBreak::where('user_id', $userId)
            ->where('date', $today)
            ->whereNull('end_time')
            ->first();
            
        $activity = AgentActivity::todayForUser($userId);
        
        return response()->json([
            'attendance' => $attendance,
            'active_break' => $activeBreak,
            'activity' => $activity,
            'total_break_time' => $attendance ? $attendance->total_break_minutes : 0
        ]);
    }
    
    public function updateActivity(Request $request)
    {
        $request->validate([
            'type' => 'required|in:call,transfer,approved,rejected',
            'amount' => 'integer|min:1'
        ]);
        
        $userId = Auth::id();
        $activity = AgentActivity::todayForUser($userId);
        $amount = $request->get('amount', 1);
        
        switch ($request->type) {
            case 'call':
                $activity->calls_made += $amount;
                break;
            case 'transfer':
                $activity->leads_transferred += $amount;
                break;
            case 'approved':
                $activity->leads_approved += $amount;
                // Could add commission calculation here
                break;
            case 'rejected':
                $activity->leads_rejected += $amount;
                break;
        }
        
        $activity->save();
        
        return response()->json([
            'success' => true,
            'activity' => $activity
        ]);
    }
    
    public function getOfficeLocation()
    {
        $config = config('attendance');
        $allowedLocations = AllowedLocation::getActiveLocations();
        
        return response()->json([
            'locations' => $allowedLocations->map(function ($location) {
                return [
                    'id' => $location->id,
                    'name' => $location->name,
                    'address' => $location->address,
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude,
                    'radius_meters' => $location->radius_meters,
                    'location_type' => $location->location_type
                ];
            }),
            'shift' => $config['night_shift'],
            'geolocation_config' => $config['geolocation']
        ]);
    }
    
    public function validateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);
        
        $locationCheck = AllowedLocation::isLocationAllowed(
            $request->latitude,
            $request->longitude
        );
        
        if ($locationCheck['allowed']) {
            return response()->json([
                'within_radius' => true,
                'allowed' => true,
                'distance_meters' => round($locationCheck['distance']),
                'location_name' => $locationCheck['location']->name,
                'max_allowed_meters' => $locationCheck['location']->radius_meters
            ]);
        } else {
            $closestLocation = $locationCheck['closest_location'];
            return response()->json([
                'within_radius' => false,
                'allowed' => false,
                'distance_meters' => $closestLocation ? round($locationCheck['closest_distance']) : 0,
                'location_name' => $closestLocation ? $closestLocation->name : 'No locations configured',
                'max_allowed_meters' => $closestLocation ? $closestLocation->radius_meters : 0
            ]);
        }
    }
    
    public function getAttendanceHistory(Request $request)
    {
        $userId = Auth::id();
        $query = AgentAttendance::where('user_id', $userId)
            ->orderBy('date', 'desc');

        // Apply date filters based on period
        switch ($request->get('period')) {
            case 'week':
                $query->where('date', '>=', now()->startOfWeek());
                break;
            case 'month':
                $query->where('date', '>=', now()->startOfMonth());
                break;
            case 'last_month':
                $query->whereBetween('date', [
                    now()->subMonth()->startOfMonth(),
                    now()->subMonth()->endOfMonth()
                ]);
                break;
            default:
                // Custom date range
                if ($request->has('start_date') && $request->has('end_date')) {
                    $query->whereBetween('date', [
                        $request->get('start_date'),
                        $request->get('end_date')
                    ]);
                } else {
                    // Default to current month if no specific period
                    $query->where('date', '>=', now()->startOfMonth());
                }
                break;
        }

        $records = $query->limit(50)->get()->map(function ($record) {
            try {
                // Calculate working hours
                $workingHours = '0:00';
                $breakTime = 0;
                
                // Ensure date is a Carbon instance
                $recordDate = $record->date instanceof Carbon ? $record->date : Carbon::parse($record->date);
                
                // Get breaks for this user and date
                $breaks = AgentBreak::where('user_id', $record->user_id)
                    ->whereDate('date', $recordDate->format('Y-m-d'))
                    ->get();
                
                if ($record->clock_in_time && $record->clock_out_time) {
                    try {
                        // Create Carbon instances for the same base date
                        $baseDate = $recordDate->format('Y-m-d');
                        $clockIn = Carbon::createFromFormat('Y-m-d H:i:s', $baseDate . ' ' . $record->clock_in_time);
                        $clockOut = Carbon::createFromFormat('Y-m-d H:i:s', $baseDate . ' ' . $record->clock_out_time);
                        
                        // Handle overnight shifts
                        if ($clockOut->lt($clockIn)) {
                            $clockOut->addDay();
                        }
                        
                        $totalMinutes = $clockIn->diffInMinutes($clockOut);
                        
                        // Subtract break time
                        $breakTime = $breaks->sum('duration_minutes');
                        $workingMinutes = max(0, $totalMinutes - $breakTime);
                        
                        $hours = intval(floor($workingMinutes / 60));
                        $minutes = intval($workingMinutes % 60);
                        $workingHours = sprintf('%d:%02d', $hours, $minutes);
                    } catch (\Exception $e) {
                        \Log::warning('Error parsing time for attendance record', [
                            'record_id' => $record->id,
                            'clock_in_time' => $record->clock_in_time,
                            'clock_out_time' => $record->clock_out_time,
                            'error' => $e->getMessage()
                        ]);
                    }
                }

                // Check if late (assuming shift start time from config)
                $isLate = false;
                if ($record->clock_in_time) {
                    try {
                        $baseDate = $recordDate->format('Y-m-d');
                        $clockIn = Carbon::createFromFormat('Y-m-d H:i:s', $baseDate . ' ' . $record->clock_in_time);
                        $shiftStart = Carbon::createFromFormat('Y-m-d H:i', $baseDate . ' ' . config('attendance.night_shift.start', '21:00'));
                        $gracePeriod = (int) config('attendance.night_shift.grace_period_minutes', 15);
                        
                        $lateStart = $shiftStart->copy()->addMinutes($gracePeriod);
                        
                        $isLate = $clockIn->gt($lateStart);
                    } catch (\Exception $e) {
                        \Log::warning('Error checking late status for attendance record', [
                            'record_id' => $record->id,
                            'clock_in_time' => $record->clock_in_time,
                            'error' => $e->getMessage()
                        ]);
                    }
                }

                return [
                    'date' => $recordDate->format('Y-m-d'),
                    'clock_in_time' => $record->clock_in_time,
                    'clock_out_time' => $record->clock_out_time,
                    'clock_in_address' => $record->clock_in_address,
                    'clock_out_address' => $record->clock_out_address,
                    'location_notes' => $record->location_notes,
                    'working_hours' => $workingHours,
                    'break_time' => $breakTime,
                    'is_late' => $isLate,
                    'breaks' => $breaks->map(function($break) {
                        return [
                            'id' => $break->id,
                            'break_type' => $break->break_type,
                            'start_time' => $break->start_time,
                            'end_time' => $break->end_time,
                            'duration_minutes' => $break->duration_minutes
                        ];
                    })
                ];
            } catch (\Exception $e) {
                \Log::error('Error processing attendance record', [
                    'record_id' => $record->id,
                    'user_id' => $record->user_id,
                    'error' => $e->getMessage()
                ]);
                
                // Return minimal data for problematic records
                return [
                    'date' => $record->date,
                    'clock_in_time' => $record->clock_in_time,
                    'clock_out_time' => $record->clock_out_time,
                    'clock_in_address' => $record->clock_in_address,
                    'clock_out_address' => $record->clock_out_address,
                    'location_notes' => $record->location_notes,
                    'working_hours' => '0:00',
                    'break_time' => 0,
                    'is_late' => false,
                    'breaks' => []
                ];
            }
        });

        // Calculate summary
        $totalDays = $records->count();
        $presentDays = $records->where('clock_in_time', '!=', null)->count();
        $totalWorkingMinutes = $records->sum(function($record) {
            if ($record['working_hours'] === '0:00') return 0;
            [$hours, $minutes] = explode(':', $record['working_hours']);
            return ($hours * 60) + $minutes;
        });
        
        $totalHours = floor($totalWorkingMinutes / 60);
        $totalMins = $totalWorkingMinutes % 60;
        $totalHoursFormatted = sprintf('%d:%02d', $totalHours, $totalMins);

        return response()->json([
            'success' => true,
            'records' => $records,
            'summary' => [
                'total_days' => $totalDays,
                'present_days' => $presentDays,
                'absent_days' => $totalDays - $presentDays,
                'total_hours' => $totalHoursFormatted
            ]
        ]);
    }

    public function exportAttendanceHistory(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();
        
        $query = AgentAttendance::where('user_id', $userId)
            ->orderBy('date', 'desc');

        // Apply same date filters as history
        switch ($request->get('period')) {
            case 'week':
                $query->where('date', '>=', now()->startOfWeek());
                $periodName = 'Week';
                break;
            case 'month':
                $query->where('date', '>=', now()->startOfMonth());
                $periodName = 'Month';
                break;
            case 'last_month':
                $query->whereBetween('date', [
                    now()->subMonth()->startOfMonth(),
                    now()->subMonth()->endOfMonth()
                ]);
                $periodName = 'Last Month';
                break;
            default:
                if ($request->has('start_date') && $request->has('end_date')) {
                    $query->whereBetween('date', [
                        $request->get('start_date'),
                        $request->get('end_date')
                    ]);
                    $periodName = 'Custom Range';
                } else {
                    $query->where('date', '>=', now()->startOfMonth());
                    $periodName = 'Month';
                }
                break;
        }

        $records = $query->get();

        // Generate CSV
        $filename = "attendance_{$user->name}_{$periodName}_" . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($records) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Date',
                'Clock In',
                'Clock Out', 
                'Working Hours',
                'Break Time (mins)',
                'Status',
                'Clock In Location',
                'Clock Out Location',
                'Notes'
            ]);

            // CSV Data
            foreach ($records as $record) {
                $workingHours = '0:00';
                $breakTime = 0;
                $status = 'Absent';
                
                if ($record->clock_in_time) {
                    $status = $record->clock_out_time ? 'Present' : 'Incomplete';
                         if ($record->clock_in_time && $record->clock_out_time) {
                    $clockIn = Carbon::createFromFormat('H:i:s', $record->clock_in_time);
                    $clockOut = Carbon::createFromFormat('H:i:s', $record->clock_out_time);
                    
                    if ($clockOut->lt($clockIn)) {
                        $clockOut->addDay();
                    }
                    
                    $totalMinutes = $clockOut->diffInMinutes($clockIn);
                    $breakTime = AgentBreak::where('user_id', $record->user_id)
                        ->where('date', $record->date)
                        ->sum('duration_minutes');
                    $workingMinutes = $totalMinutes - $breakTime;
                    
                    $hours = floor($workingMinutes / 60);
                    $minutes = $workingMinutes % 60;
                    $workingHours = sprintf('%d:%02d', $hours, $minutes);
                }
                }

                fputcsv($file, [
                    $record->date->format('Y-m-d'),
                    $record->clock_in_time ?: '--:--',
                    $record->clock_out_time ?: '--:--',
                    $workingHours,
                    $breakTime,
                    $status,
                    $record->clock_in_address ?: '',
                    $record->clock_out_address ?: '',
                    $record->location_notes ?: ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    
    // =======================
    // ADMIN ATTENDANCE MANAGEMENT
    // =======================
    
    /**
     * Admin attendance management index page
     */
    public function adminIndex(Request $request)
    {
        $query = AgentAttendance::with(['user'])
            ->orderBy('date', 'desc')
            ->orderBy('clock_in_time', 'desc');
        
        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }
        
        $attendances = $query->paginate(20);
        
        // Get all agent users for filter dropdown
        $agents = \App\Models\User::where('role', 'agent')
            ->orderBy('name')
            ->get(['id', 'name']);
        
        return view('admin.attendance.index', compact('attendances', 'agents'));
    }
    
    /**
     * Show attendance history for a specific user
     */
    public function adminUserHistory(Request $request, \App\Models\User $user)
    {
        $query = AgentAttendance::where('user_id', $user->id)
            ->with('breaks')
            ->orderBy('date', 'desc');
        
        // Apply date filters if provided
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }
        
        $attendances = $query->paginate(15);
        
        return view('admin.attendance.user-history', compact('user', 'attendances'));
    }
    
    /**
     * Show edit form for attendance record
     */
    public function adminEdit(AgentAttendance $attendance)
    {
        $attendance->load(['user', 'breaks']);
        
        return view('admin.attendance.edit', compact('attendance'));
    }
    
    /**
     * Update attendance record
     */
    public function adminUpdate(Request $request, AgentAttendance $attendance)
    {
        $request->validate([
            'date' => 'required|date',
            'clock_in_time' => 'nullable|date_format:H:i',
            'clock_out_time' => 'nullable|date_format:H:i',
            'clock_in_address' => 'nullable|string|max:500',
            'clock_out_address' => 'nullable|string|max:500',
            'location_notes' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000'
        ]);
        
        // Convert time format from H:i to H:i:s
        $clockInTime = $request->clock_in_time ? $request->clock_in_time . ':00' : null;
        $clockOutTime = $request->clock_out_time ? $request->clock_out_time . ':00' : null;
        
        $attendance->update([
            'date' => $request->date,
            'clock_in_time' => $clockInTime,
            'clock_out_time' => $clockOutTime,
            'clock_in_address' => $request->clock_in_address,
            'clock_out_address' => $request->clock_out_address,
            'location_notes' => $request->location_notes,
            'notes' => $request->notes
        ]);
        
        return redirect()
            ->route('admin.attendance.index')
            ->with('success', 'Attendance record updated successfully');
    }
    
    /**
     * Delete attendance record
     */
    public function adminDelete(AgentAttendance $attendance)
    {
        // Delete associated breaks first
        AgentBreak::where('user_id', $attendance->user_id)
            ->whereDate('date', $attendance->date)
            ->delete();
        
        $attendance->delete();
        
        return redirect()
            ->route('admin.attendance.index')
            ->with('success', 'Attendance record deleted successfully');
    }
    
    /**
     * Export attendance data
     */
    public function adminExport(Request $request)
    {
        try {
            $query = AgentAttendance::with(['user', 'breaks']);
            
            // Apply filters if provided
            if ($request->has('start_date') && $request->start_date) {
                $query->where('date', '>=', $request->start_date);
            }
            
            if ($request->has('end_date') && $request->end_date) {
                $query->where('date', '<=', $request->end_date);
            }
            
            if ($request->has('user_id') && $request->user_id) {
                $query->where('user_id', $request->user_id);
            }
            
            $attendanceRecords = $query->orderBy('date', 'desc')->get();
            
            $filename = 'attendance_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($attendanceRecords) {
                $file = fopen('php://output', 'w');
                
                // CSV headers
                fputcsv($file, [
                    'Date',
                    'Agent Name',
                    'Clock In',
                    'Clock Out',
                    'Total Hours',
                    'Break Duration (mins)',
                    'Location',
                    'Status',
                    'Notes'
                ]);
                
                foreach ($attendanceRecords as $record) {
                    $totalHours = '';
                    if ($record->clock_in_time && $record->clock_out_time) {
                        $clockIn = Carbon::parse($record->date . ' ' . $record->clock_in_time);
                        $clockOut = Carbon::parse($record->date . ' ' . $record->clock_out_time);
                        $totalHours = $clockIn->diffInHours($clockOut, true);
                    }
                    
                    $breakDuration = $record->breaks->sum(function($break) {
                        if ($break->start_time && $break->end_time) {
                            $start = Carbon::parse($break->start_time);
                            $end = Carbon::parse($break->end_time);
                            return $start->diffInMinutes($end);
                        }
                        return 0;
                    });
                    
                    fputcsv($file, [
                        $record->date,
                        $record->user->name ?? '',
                        $record->clock_in_time ?? '',
                        $record->clock_out_time ?? '',
                        $totalHours,
                        $breakDuration,
                        $record->location ?? '',
                        ucfirst($record->status ?? ''),
                        $record->notes ?? ''
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to export data: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Show attendance settings page - redirects to unified settings
     */
    public function adminSettings()
    {
        return redirect()->to(route('admin.settings') . '#attendance');
    }

    /**
     * Update attendance settings
     */
    public function adminUpdateSettings(Request $request)
    {
        $request->validate([
            'office_latitude' => 'required|numeric|between:-90,90',
            'office_longitude' => 'required|numeric|between:-180,180',
            'office_address' => 'required|string|max:1000',
            'max_distance_meters' => 'required|integer|min:50|max:10000',
            'night_shift.start' => 'required|string|regex:/^\d{2}:\d{2}$/',
            'night_shift.end' => 'required|string|regex:/^\d{2}:\d{2}$/',
            'night_shift.grace_period_minutes' => 'required|integer|min:0|max:60',
            'night_shift.auto_logout_minutes' => 'required|integer|min:5|max:120',
            'breaks.max_regular_breaks' => 'required|integer|min:0|max:10',
            'breaks.max_lunch_breaks' => 'required|integer|min:0|max:3',
            'breaks.max_emergency_breaks' => 'required|integer|min:0|max:5',
            'breaks.max_regular_duration_minutes' => 'required|integer|min:1|max:30',
            'breaks.max_lunch_duration_minutes' => 'required|integer|min:15|max:120',
            'breaks.max_emergency_duration_minutes' => 'required|integer|min:1|max:60',
            'location_verification.require_gps' => 'boolean',
            'location_verification.require_ip_check' => 'boolean',
            'location_verification.allow_manual_override' => 'boolean',
            'location_verification.store_location_history' => 'boolean',
            'geolocation.timeout_seconds' => 'required|integer|min:5|max:60',
            'geolocation.maximum_age_seconds' => 'required|integer|min:30|max:300',
        ]);

        try {
            // Update configuration file
            $configPath = config_path('attendance.php');
            $configData = [
                'office_latitude' => (float) $request->input('office_latitude'),
                'office_longitude' => (float) $request->input('office_longitude'),
                'office_address' => $request->input('office_address'),
                'max_distance_meters' => (int) $request->input('max_distance_meters'),
                'allowed_ip_ranges' => config('attendance.allowed_ip_ranges', []),
                'night_shift' => [
                    'start' => $request->input('night_shift.start'),
                    'end' => $request->input('night_shift.end'),
                    'grace_period_minutes' => (int) $request->input('night_shift.grace_period_minutes'),
                    'auto_logout_minutes' => (int) $request->input('night_shift.auto_logout_minutes'),
                ],
                'breaks' => [
                    'max_regular_breaks' => (int) $request->input('breaks.max_regular_breaks'),
                    'max_lunch_breaks' => (int) $request->input('breaks.max_lunch_breaks'),
                    'max_emergency_breaks' => (int) $request->input('breaks.max_emergency_breaks'),
                    'max_regular_duration_minutes' => (int) $request->input('breaks.max_regular_duration_minutes'),
                    'max_lunch_duration_minutes' => (int) $request->input('breaks.max_lunch_duration_minutes'),
                    'max_emergency_duration_minutes' => (int) $request->input('breaks.max_emergency_duration_minutes'),
                ],
                'location_verification' => [
                    'require_gps' => $request->boolean('location_verification.require_gps'),
                    'require_ip_check' => $request->boolean('location_verification.require_ip_check'),
                    'allow_manual_override' => $request->boolean('location_verification.allow_manual_override'),
                    'store_location_history' => $request->boolean('location_verification.store_location_history'),
                ],
                'geolocation' => [
                    'timeout_seconds' => (int) $request->input('geolocation.timeout_seconds'),
                    'high_accuracy' => config('attendance.geolocation.high_accuracy', true),
                    'maximum_age_seconds' => (int) $request->input('geolocation.maximum_age_seconds'),
                ],
            ];

            $configContent = "<?php\n\nreturn " . var_export($configData, true) . ";\n";
            file_put_contents($configPath, $configContent);

            // Clear config cache
            if (function_exists('opcache_reset')) {
                opcache_reset();
            }
            
            // Handle AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Attendance settings updated successfully.'
                ]);
            }
            
            return redirect()->to(route('admin.settings') . '#attendance')
                ->with('success', 'Attendance settings updated successfully.');

        } catch (\Exception $e) {
            // Handle AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update settings: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update settings: ' . $e->getMessage()]);
        }
    }
}
