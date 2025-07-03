@extends('layouts.auth-fullwidth')
@section('title', 'Forgot Password - Acraltech Solutions')
@section('content')
<div class="flex justify-center">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="mx-auto w-16 h-16 bg-gradient-to-r from-amber-500 to-orange-600 rounded-full flex items-center justify-center mb-4">
                <span class="material-icons text-white text-2xl">lock_reset</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2 font-display">Forgot Password?</h1>
            <p class="text-gray-600 dark:text-gray-400">No problem. Enter your email and we'll send you a password reset link.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-6" :status="session('status')" />

        <!-- Forgot Password Form -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf
            
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
                           value="{{ old('email') }}" 
                           placeholder="Enter your email address"
                           required 
                           autofocus />
                </div>
                @error('email')
                    <div class="flex items-center mt-2">
                        <span class="material-icons text-red-500 text-sm mr-2">error</span>
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg flex items-center justify-center gap-2">
                <span class="material-icons">send</span>
                Send Password Reset Link
            </button>

            <!-- Back to Login -->
            <div class="text-center">
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center gap-2 text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-medium transition-colors">
                    <span class="material-icons text-sm">arrow_back</span>
                    Back to Login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
