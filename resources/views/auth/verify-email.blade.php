@extends('layouts.auth-fullwidth')
@section('title', 'Verify Your Email - Acraltech Solutions')
@section('panel-title', 'Check Your Email!')
@section('panel-description', 'We\'ve sent a verification code to your email address. Enter the code below to verify your account and complete the registration process.')
@section('content')
<div class="flex justify-center">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="mx-auto w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mb-4">
                <span class="material-icons text-white text-2xl">email</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2 font-display">Verify Your Email</h1>
            <p class="text-gray-600 dark:text-gray-400">We've sent a 6-digit code to <strong>{{ session('registration_email') }}</strong></p>
        </div>

    <!-- Verification Form -->
    <form method="POST" action="{{ route('email.verify.submit') }}" class="space-y-6" x-data="verificationForm()" @submit="handleSubmit">
        @csrf

        <!-- OTP Input -->
        <div class="space-y-2">
            <label for="otp" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                Verification Code
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="material-icons text-gray-400 text-lg">security</span>
                </div>
                <input id="otp" 
                       class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-0 focus:border-purple-500 dark:focus:border-purple-400 transition-all duration-300 text-center text-2xl font-mono tracking-widest @error('otp') border-red-300 dark:border-red-500 @enderror" 
                       type="text" 
                       name="otp" 
                       value="{{ old('otp') }}" 
                       placeholder="000000"
                       required 
                       autofocus 
                       maxlength="6"
                       pattern="[0-9]{6}"
                       autocomplete="one-time-code">
            </div>
            @error('otp')
                <p class="text-red-500 text-sm flex items-center gap-1">
                    <span class="material-icons text-xs">error</span>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Timer -->
        <div class="text-center">
            <div x-show="timeLeft > 0" class="text-sm text-gray-500 dark:text-gray-400">
                Code expires in <span x-text="formatTime(timeLeft)" class="font-mono font-semibold text-purple-600 dark:text-purple-400"></span>
            </div>
            <div x-show="timeLeft === 0" class="text-sm text-red-500">
                Verification code has expired
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                :disabled="timeLeft === 0"
                class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 disabled:from-gray-400 disabled:to-gray-500 disabled:cursor-not-allowed text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg flex items-center justify-center gap-2">
            <span x-show="!isLoading">Verify Email</span>
            <span x-show="isLoading" class="flex items-center gap-2">
                <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                Verifying...
            </span>
        </button>

        <!-- Resend Code -->
        <div class="text-center">
            <form method="POST" action="{{ route('email.resend') }}" class="inline" x-data="{ resending: false }" @submit="resending = true">
                @csrf
                <button type="submit" 
                        :disabled="timeLeft > 0 || resending"
                        class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-semibold transition-colors disabled:text-gray-400 disabled:cursor-not-allowed">
                    <span x-show="!resending">Resend Code</span>
                    <span x-show="resending">Sending...</span>
                </button>
            </form>
        </div>

        <!-- Back to Registration -->
        <div class="text-center">
            <a href="{{ route('register') }}" 
               class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                ← Back to Registration
            </a>
        </div>
    </form>

    @push('scripts')
    <script>
        function verificationForm() {
            return {
                isLoading: false,
                timeLeft: 600, // 10 minutes in seconds
                
                init() {
                    this.startTimer();
                },
                
                handleSubmit(event) {
                    if (this.timeLeft === 0) {
                        event.preventDefault();
                        return;
                    }
                    this.isLoading = true;
                },
                
                startTimer() {
                    const timer = setInterval(() => {
                        if (this.timeLeft > 0) {
                            this.timeLeft--;
                        } else {
                            clearInterval(timer);
                        }
                    }, 1000);
                },
                
                formatTime(seconds) {
                    const minutes = Math.floor(seconds / 60);
                    const remainingSeconds = seconds % 60;
                    return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
                }
            }
        }
    </script>
    @endpush
    </div>
</div>
@endsection
