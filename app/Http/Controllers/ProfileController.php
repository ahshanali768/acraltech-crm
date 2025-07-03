<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;

require_once base_path('vendor/multiavatar/multiavatar-php/Multiavatar.php');

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture && \Storage::disk('public')->exists($user->profile_picture)) {
                \Storage::disk('public')->delete($user->profile_picture);
            }
            $image = $request->file('profile_picture');
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image)->fit(128, 128)->encode($image->getClientOriginalExtension());
            \Storage::disk('public')->put('profile_pictures/' . $filename, $img);
            $user->profile_picture = 'profile_pictures/' . $filename;
        } elseif ($request->filled('avatar_pack')) {
            $user->profile_picture = 'avatar_packs/' . $request->avatar_pack;
        } elseif ($request->has('use_random_avatar')) {
            // Delete old profile picture if exists BEFORE nulling the field
            if ($user->profile_picture && \Storage::disk('public')->exists($user->profile_picture)) {
                \Storage::disk('public')->delete($user->profile_picture);
            }
            $styles = ['multiavatar']; // Only use Multiavatar
            $user->profile_picture = null;
            $user->avatar_style = $styles[array_rand($styles)];
            $user->avatar_seed = uniqid('', true) . rand();
        }
        $user->avatar_style = $request->avatar_style ?? $user->avatar_style;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Serve a Multiavatar SVG for the given user seed.
     */
    public function multiavatar($seed)
    {
        $svg = (new \Multiavatar())->generate($seed, null, null);
        return response($svg, 200)->header('Content-Type', 'image/svg+xml');
    }
}
