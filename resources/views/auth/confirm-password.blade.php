@extends('layouts.auth-fullwidth')
@section('title', 'Confirm Password - Acraltech Solutions')
@section('content')
    <div class="w-full max-w-md bg-white/80 rounded-2xl shadow-xl p-8 glass fade-in mt-8">
        <h1 class="text-2xl font-display font-bold text-gradient-primary mb-6 text-center">Confirm your password</h1>
        <div class="mb-4 text-sm text-gray-600 text-center">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>
        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
            @csrf
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input id="password" 
                       class="w-full px-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-0 focus:border-blue-500 focus:bg-white transition-all duration-300 text-base @error('password') border-red-300 bg-red-50 @enderror" 
                       type="password" 
                       name="password" 
                       required autocomplete="current-password" />
                @if($errors->has('password'))
                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('password') }}</p>
                @endif
            </div>
            <div class="flex justify-end mt-4">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-300 shadow-lg">
                    {{ __('Confirm') }}
                </button>
            </div>
        </form>
    </div>
@endsection
