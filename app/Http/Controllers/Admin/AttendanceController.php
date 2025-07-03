<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentAttendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = AgentAttendance::with(['user'])
            ->select('agent_attendance.*')
            ->join('users', 'agent_attendance.user_id', '=', 'users.id')
            ->orderBy('agent_attendance.login_time', 'desc');

        // Handle date range filter
        $dateFilter = $request->input('date', 'week');
        switch ($dateFilter) {
            case 'today':
                $query->whereDate('agent_attendance.login_time', today());
                break;
            case 'yesterday':
                $query->whereDate('agent_attendance.login_time', today()->subDay());
                break;
            case 'week':
                $query->whereBetween('agent_attendance.login_time', [today()->startOfWeek(), today()->endOfWeek()]);
                break;
            case 'month':
                $query->whereBetween('agent_attendance.login_time', [today()->startOfMonth(), today()->endOfMonth()]);
                break;
            case 'custom':
                if ($request->filled('date_from')) {
                    $query->whereDate('agent_attendance.login_time', '>=', $request->input('date_from'));
                }
                if ($request->filled('date_to')) {
                    $query->whereDate('agent_attendance.login_time', '<=', $request->input('date_to'));
                }
                break;
        }

        // Handle status filter
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('agent_attendance.status', $request->input('status'));
        }

        // Handle agent/user filter
        if ($request->filled('user') && $request->input('user') !== 'all') {
            $query->where('agent_attendance.user_id', $request->input('user'));
        }

        $attendance = $query->paginate(15);
        
        // Load breaks manually for each attendance record
        if ($attendance->count() > 0) {
            $userIds = $attendance->pluck('user_id')->toArray();
            $attendanceDates = $attendance->map(function($record) {
                if ($record->login_time) {
                    return $record->login_time->format('Y-m-d');
                }
                return null;
            })->filter()->toArray();
            
            // Get all breaks for these users on these dates
            $breaks = \App\Models\AgentBreak::whereIn('user_id', $userIds)
                ->whereIn('date', $attendanceDates)
                ->get()
                ->groupBy('user_id');
                
            // Attach breaks to each attendance record
            foreach ($attendance as $record) {
                if ($record->login_time && isset($breaks[$record->user_id])) {
                    $recordDate = $record->login_time->format('Y-m-d');
                    $recordBreaks = $breaks[$record->user_id]->filter(function($break) use ($recordDate) {
                        return $break->date && $break->date->format('Y-m-d') === $recordDate;
                    });
                    $record->setRelation('breaks', $recordBreaks);
                }
            }
        }
        
        $agents = User::where('role', 'agent')->get();

        // Calculate total hours and average hours using login_time and logout_time
        $stats = [
            'total_hours' => $this->formatTotalHours(
                AgentAttendance::whereNotNull('login_time')
                    ->whereNotNull('logout_time')
                    ->get()
                    ->sum(function($record) {
                        return $record->login_time && $record->logout_time ? 
                            $record->logout_time->diffInSeconds($record->login_time) / 3600 : 0;
                    })
            ),
            'avg_hours_per_day' => $this->formatTotalHours(
                AgentAttendance::whereNotNull('login_time')
                    ->whereNotNull('logout_time')
                    ->get()
                    ->average(function($record) {
                        return $record->login_time && $record->logout_time ? 
                            $record->logout_time->diffInSeconds($record->login_time) / 3600 : 0;
                    })
            ),
            'total_breaks' => DB::table('agent_breaks')->count(),
            'avg_break_duration' => DB::table('agent_breaks')->avg('duration_minutes')
        ];

        // Prepare filter data for the view
        $filters = [
            'date' => $request->input('date', 'week'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'status' => $request->input('status', 'all'),
            'user' => $request->input('user', 'all'),
        ];

        return view('admin.attendance', compact('attendance', 'agents', 'stats', 'filters'));
    }

    public function show(Request $request, $id)
    {
        $attendance = AgentAttendance::with(['user'])->findOrFail($id);
        
        // Load breaks manually
        if ($attendance->login_time) {
            $recordDate = $attendance->login_time->format('Y-m-d');
            $breaks = \App\Models\AgentBreak::where('user_id', $attendance->user_id)
                ->whereDate('date', $recordDate)
                ->get();
            $attendance->setRelation('breaks', $breaks);
        }
        
        return view('admin.attendance-show', compact('attendance'));
    }
    
    public function export(Request $request)
    {
        $query = AgentAttendance::with(['user'])
            ->select('agent_attendance.*', 'users.name as user_name', 'users.username')
            ->join('users', 'agent_attendance.user_id', '=', 'users.id')
            ->orderBy('agent_attendance.login_time', 'desc');
        
        // Handle date range filter
        $dateFilter = $request->input('date', 'week');
        switch ($dateFilter) {
            case 'today':
                $query->whereDate('agent_attendance.login_time', today());
                break;
            case 'yesterday':
                $query->whereDate('agent_attendance.login_time', today()->subDay());
                break;
            case 'week':
                $query->whereBetween('agent_attendance.login_time', [today()->startOfWeek(), today()->endOfWeek()]);
                break;
            case 'month':
                $query->whereBetween('agent_attendance.login_time', [today()->startOfMonth(), today()->endOfMonth()]);
                break;
            case 'custom':
                if ($request->filled('date_from')) {
                    $query->whereDate('agent_attendance.login_time', '>=', $request->input('date_from'));
                }
                if ($request->filled('date_to')) {
                    $query->whereDate('agent_attendance.login_time', '<=', $request->input('date_to'));
                }
                break;
        }

        // Handle status filter
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('agent_attendance.status', $request->input('status'));
        }

        // Handle agent/user filter
        if ($request->filled('user') && $request->input('user') !== 'all') {
            $query->where('agent_attendance.user_id', $request->input('user'));
        }
        
        $attendance = $query->get();
        
        // Load breaks manually for each attendance record
        if ($attendance->count() > 0) {
            $userIds = $attendance->pluck('user_id')->toArray();
            $attendanceDates = $attendance->map(function($record) {
                if ($record->login_time) {
                    return $record->login_time->format('Y-m-d');
                }
                return null;
            })->filter()->toArray();
            
            // Get all breaks for these users on these dates
            $breaks = \App\Models\AgentBreak::whereIn('user_id', $userIds)
                ->whereIn('date', $attendanceDates)
                ->get()
                ->groupBy('user_id');
                
            // Attach breaks to each attendance record
            foreach ($attendance as $record) {
                if ($record->login_time && isset($breaks[$record->user_id])) {
                    $recordDate = $record->login_time->format('Y-m-d');
                    $recordBreaks = $breaks[$record->user_id]->filter(function($break) use ($recordDate) {
                        return $break->date && $break->date->format('Y-m-d') === $recordDate;
                    });
                    $record->setRelation('breaks', $recordBreaks);
                }
            }
        }
        
        // Generate CSV
        $filename = 'attendance_export_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];
        
        $callback = function() use ($attendance) {
            $file = fopen('php://output', 'w');
            
            // Add BOM to fix Excel UTF-8 encoding issues
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            // CSV headers
            fputcsv($file, [
                'Agent Name', 
                'Username',
                'Date',
                'Login Time',
                'Logout Time',
                'Status',
                'Hours Worked',
                'Break Count',
                'Total Break Time (min)'
            ]);
            
            foreach ($attendance as $record) {
                $breakCount = $record->breaks ? $record->breaks->count() : 0;
                $totalBreakTime = $record->breaks ? $record->breaks->sum('duration_minutes') : 0;
                
                try {
                    fputcsv($file, [
                        $record->user ? $record->user->name : 'N/A',
                        $record->user ? $record->user->username : 'N/A',
                        $record->login_time ? date('Y-m-d', strtotime($record->login_time)) : 'N/A',
                        $record->login_time ? date('h:i A', strtotime($record->login_time)) : 'N/A',
                        $record->logout_time ? date('h:i A', strtotime($record->logout_time)) : 'Still Active',
                        ucfirst($record->status),
                        $record->login_time && $record->logout_time ? 
                            $this->formatTotalHours(
                                $record->logout_time->diffInSeconds($record->login_time) / 3600
                            ) : 'N/A',
                        $breakCount,
                        $totalBreakTime
                    ]);
                } catch (\Exception $e) {
                    // Skip problematic records
                    continue;
                }
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Debug method for export to check if it's being called
     */
    public function debugExport(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Export route is working',
            'params' => $request->all()
        ]);
    }
    
    /**
     * Format hours as HH:MM
     */
    private function formatTotalHours($hours)
    {
        if ($hours === null) return '0:00';
        
        $wholeHours = floor($hours);
        $minutes = floor(($hours - $wholeHours) * 60);
        
        return $wholeHours . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT);
    }
}
