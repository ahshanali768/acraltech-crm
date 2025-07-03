@php
    $userRole = strtolower(auth()->user()->role ?? 'user');
    $layoutName = in_array($userRole, ['admin', 'agent', 'publisher']) 
        ? "layouts.{$userRole}" 
        : 'layouts.app';
@endphp

@extends($layoutName)

@section('title', 'Profile Settings')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Profile Settings</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Manage your account settings and preferences</p>
        </div>
    </div>

    <!-- Profile Picture Section -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
        <div class="p-8">
            <div class="flex flex-col items-center justify-center mb-8">
                <div class="relative">
                    <img src="
                        @if(auth()->user()->profile_picture)
                            {{ asset('storage/' . auth()->user()->profile_picture) . '?v=' . time() }}
                        @elseif(auth()->user()->avatar_style === 'multiavatar' && auth()->user()->avatar_seed)
                            {{ url('/multiavatar/' . auth()->user()->avatar_seed . '.svg') . '?v=' . time() }}
                        @elseif(auth()->user()->avatar_style && auth()->user()->avatar_seed)
                            https://api.dicebear.com/7.x/{{ auth()->user()->avatar_style }}/svg?seed={{ auth()->user()->avatar_seed }}&r={{ uniqid() }}
                        @else
                            https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D8ABC&color=fff
                        @endif
                    " alt="Profile Picture" class="w-32 h-32 rounded-full object-cover border-4 border-blue-500 shadow-2xl">
                    <div class="absolute -bottom-2 -right-2 bg-blue-500 text-white p-2 rounded-full shadow-lg">
                        <i class="fas fa-camera text-sm"></i>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->name }}</h2>
                    <p class="text-gray-600 dark:text-gray-400">{{ auth()->user()->email }}</p>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            <i class="fas fa-user-tag mr-1"></i>{{ ucfirst(auth()->user()->role ?? 'User') }}
                        </span>
                    </div>
                </div>
                
                <div class="mt-6">
                    <button type="button" onclick="document.getElementById('chooseProfileModal').showModal()" 
                            class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-camera mr-2"></i>Change Profile Picture
                    </button>
                </div>
            </div>

            <!-- Profile Picture Modal -->
            <dialog id="chooseProfileModal" class="rounded-2xl p-0 w-full max-w-md backdrop:bg-black backdrop:bg-opacity-50">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" 
                      class="bg-white dark:bg-gray-800 rounded-2xl p-8 space-y-6">
                    @csrf
                    @method('patch')
                    <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                    <input type="hidden" name="email" value="{{ strtolower(auth()->user()->email) }}">
                    
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Update Profile Picture</h3>
                        <p class="text-gray-600 dark:text-gray-400">Choose how you'd like to update your profile picture</p>
                    </div>
                    
                    <div class="space-y-4">
                        <label class="flex items-center justify-center w-full px-6 py-4 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-xl font-semibold cursor-pointer transition-all duration-300 transform hover:scale-105">
                            <input type="file" name="profile_picture" accept="image/*" class="hidden" onchange="this.form.submit()">
                            <i class="fas fa-upload mr-3"></i>Upload New Photo
                        </label>
                        
                        <button type="submit" name="use_random_avatar" value="1" 
                                class="w-full px-6 py-4 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-dice mr-3"></i>Generate Random Avatar
                        </button>
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                        <button type="button" onclick="document.getElementById('chooseProfileModal').close()" 
                                class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            </dialog>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
        <div class="p-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-user text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Profile Information</h2>
                    <p class="text-gray-600 dark:text-gray-400">Update your account's profile information and email address</p>
                </div>
            </div>
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <!-- Update Password -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
        <div class="p-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-lock text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Update Password</h2>
                    <p class="text-gray-600 dark:text-gray-400">Ensure your account is using a long, random password to stay secure</p>
                </div>
            </div>
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <!-- Delete Account -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-red-200 dark:border-red-800">
        <div class="p-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-trash-alt text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-red-600 dark:text-red-400">Delete Account</h2>
                    <p class="text-gray-600 dark:text-gray-400">Permanently delete your account and all associated data</p>
                </div>
            </div>
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>

<style>
dialog::backdrop {
    background-color: rgba(0, 0, 0, 0.5);
}

dialog {
    border: none;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}
</style>
@endsection
