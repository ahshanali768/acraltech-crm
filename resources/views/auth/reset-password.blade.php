@extends('layouts.auth-fullwidth')
@section('title', 'Reset Password - Acraltech Solutions')
@section('content')
<div class="flex justify-center">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="mx-auto w-16 h-16 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center mb-4">
                <span class="material-icons text-white text-2xl">lock_reset</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2 font-display">Reset Password</h1>
            <p class="text-gray-600 dark:text-gray-400">Enter your new password below</p>
        </div>

        <!-- Reset Password Form -->
        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            
            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Email Address
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-icons text-gray-400 text-lg">email</span>
                    </div>
                    <input id="email" 
                           class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-0 focus:border-purple-500 dark:focus:border-purple-400 transition-all duration-300 @error('email') border-red-300 dark:border-red-500 @enderror" 
                           type="email" 
                           name="email" 
                           value="{{ old('email', $request->email) }}" 
                           required 
                           autofocus 
                           autocomplete="username" />
                </div>
                @error('email')
                    <div class="flex items-center mt-2">
                        <span class="material-icons text-red-500 text-sm mr-2">error</span>
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">New Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-icons text-gray-400 text-lg">lock</span>
                    </div>
                    <input id="password" 
                           class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-0 focus:border-purple-500 dark:focus:border-purple-400 transition-all duration-300 @error('password') border-red-300 dark:border-red-500 @enderror" 
                           type="password" 
                           name="password" 
                           placeholder="Enter your new password"
                           required 
                           autocomplete="new-password" />
                </div>
                @error('password')
                    <div class="flex items-center mt-2">
                        <span class="material-icons text-red-500 text-sm mr-2">error</span>
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Confirm New Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-icons text-gray-400 text-lg">lock</span>
                    </div>
                    <input id="password_confirmation" 
                           class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-0 focus:border-purple-500 dark:focus:border-purple-400 transition-all duration-300 @error('password_confirmation') border-red-300 dark:border-red-500 @enderror"
                           type="password"
                           name="password_confirmation" 
                           placeholder="Confirm your new password"
                           required 
                           autocomplete="new-password" />
                </div>
                @error('password_confirmation')
                    <div class="flex items-center mt-2">
                        <span class="material-icons text-red-500 text-sm mr-2">error</span>
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-semibold py-4 px-6 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-all duration-300 transform hover:scale-[1.02] shadow-lg hover:shadow-xl">
                <span class="flex items-center justify-center">
                    <span class="material-icons mr-2">lock_reset</span>
                    Reset Password
                </span>
            </button>
        </form>
    </div>
@endsection
