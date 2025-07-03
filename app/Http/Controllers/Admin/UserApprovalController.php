<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\AccountStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserApprovalController extends Controller
{
    /**
     * Quick approve user (from email link)
     */
    public function approve(User $user)
    {
        if ($user->approval_status !== 'pending') {
            return redirect()->route('admin.manage_users')->with('error', 'User is not pending approval.');
        }

        $user->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'status' => 'active',
        ]);

        // Send approval notification to user
        Mail::to($user->email)->send(new AccountStatusNotification($user, 'approved', Auth::user()));

        return redirect()->route('admin.manage_users')->with('success', "User {$user->name} has been approved successfully!");
    }

    /**
     * Quick reject user (from email link)
     */
    public function reject(User $user)
    {
        if ($user->approval_status !== 'pending') {
            return redirect()->route('admin.manage_users')->with('error', 'User is not pending approval.');
        }

        $user->update([
            'approval_status' => 'rejected',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'status' => 'inactive',
            'approval_notes' => 'Application rejected by administrator.',
        ]);

        // Send rejection notification to user
        Mail::to($user->email)->send(new AccountStatusNotification($user, 'rejected', Auth::user(), 'Application rejected by administrator.'));

        return redirect()->route('admin.manage_users')->with('success', "User {$user->name} has been rejected.");
    }

    /**
     * Process approval with custom role and notes
     */
    public function processApproval(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,agent,publisher',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($user->approval_status !== 'pending') {
            return response()->json(['error' => 'User is not pending approval.'], 400);
        }

        $user->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'status' => 'active',
            'role' => $request->role,
            'approval_notes' => $request->notes,
        ]);

        // Send approval notification to user
        Mail::to($user->email)->send(new AccountStatusNotification($user, 'approved', Auth::user(), $request->notes));

        return response()->json([
            'success' => true,
            'message' => "User {$user->name} has been approved successfully with role: {$request->role}!"
        ]);
    }

    /**
     * Process rejection with notes
     */
    public function processRejection(Request $request, User $user)
    {
        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        if ($user->approval_status !== 'pending') {
            return response()->json(['error' => 'User is not pending approval.'], 400);
        }

        $user->update([
            'approval_status' => 'rejected',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'status' => 'inactive',
            'approval_notes' => $request->notes,
        ]);

        // Send rejection notification to user
        Mail::to($user->email)->send(new AccountStatusNotification($user, 'rejected', Auth::user(), $request->notes));

        return response()->json([
            'success' => true,
            'message' => "User {$user->name} has been rejected."
        ]);
    }
}
