<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $pendingUsers = User::where('approval_status', 'pending')->get();
        return view('admin.manage_users', compact('users', 'pendingUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,agent',
            'password' => 'required|string|min:6',
        ]);
        // Double-check for duplicate username/email
        if (User::where('username', $request->username)->orWhere('email', $request->email)->exists()) {
            return redirect()->back()->withErrors(['email' => 'A user with this username or email already exists.'])->withInput();
        }
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'status' => 'active',
            'email_verified' => true,
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);
        return redirect()->back()->with('success', 'User added successfully!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'sometimes|required|in:admin,agent',
            'status' => 'sometimes|required|in:active,revoked',
            'password' => 'nullable|string|min:6',
        ]);
        $data = $request->only(['name', 'username', 'email', 'phone', 'role', 'status']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        return redirect()->back()->with('success', 'User updated successfully!');
    }    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('success', 'You cannot delete your own account.');
        }
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully!');
    }
}
