@extends('v2.auth.app')
@section('title') Forgot Password - Geezap @endsection
@section('content')
    <!-- Forgot Password Container -->
    <div class="bg-[#12122b] rounded-2xl shadow-lg p-8 max-w-lg w-full space-y-8 border border-gray-800">
        <div class="text-center space-y-2">
            <h2 class="text-3xl font-bold text-white font-oxanium-bold">Reset Password</h2>
            <p class="text-gray-400 font-ubuntu-light">Enter your email to receive reset instructions</p>
        </div>

        @session('status')
        <div class="flex justify-center">
            <span class="text-green-500">{{$value}}</span>
        </div>
        @endsession

        <div class="flex justify-center mb-4">
            <a href="{{ route('login') }}" class="text-gray-400 hover:text-pink-500 transition-colors flex items-center gap-2 font-ubuntu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Login
            </a>
        </div>

        <!-- Reset Password Form -->
        <form action="{{ route('password.email') }}" method="POST" class="space-y-6 font-ubuntu">
            @csrf
            <div>
                <label for="email" class="block text-gray-400 mb-1">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="name@example.com"
                    class="w-full px-4 py-3 rounded-xl bg-[#1a1a3a] text-white placeholder-gray-500 border border-gray-600 focus:border-pink-500 focus:outline-none transition"
                >
                @error('email')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full bg-gradient-to-r from-pink-500 to-purple-600 text-white px-6 py-3 rounded-xl font-medium hover:opacity-90 transition-all"
            >
                Send Reset Link
            </button>
        </form>

        <!-- Login Link -->
        <p class="text-center text-gray-400 mt-6 font-ubuntu-light">
            Remember your password?
            <a href="{{ route('login') }}" class="text-pink-500 hover:text-pink-400 transition-colors font-ubuntu-medium">
                Login here
            </a>
        </p>
    </div>
@endsection
