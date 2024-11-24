@extends('v2.auth.app')
@section('content')
    <div class="bg-[#12122b] rounded-2xl shadow-lg p-8 max-w-lg w-full space-y-6 border border-gray-800">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-white font-oxanium-bold">Create an Account</h2>
            <p class="text-gray-400 font-ubuntu-light">Join Geezap and start your job search journey.</p>
        </div>

        <div class="flex justify-center mb-4">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-pink-500 transition-colors flex items-center gap-2 font-ubuntu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Home
            </a>
        </div>

        <!-- Social Login Options -->
        <x-social-login/>

        <!-- Divider -->
        <div class="flex items-center">
            <hr class="flex-grow border-t border-gray-600">
            <span class="px-4 text-gray-400 text-sm">or</span>
            <hr class="flex-grow border-t border-gray-600">
        </div>

        <!-- Register Form -->
        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="RegisterName" class="block text-gray-400 text-sm mb-1">Full Name</label>
                <input
                    type="text"
                    id="RegisterName"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter your full name"
                    class="w-full px-4 py-2.5 rounded-xl bg-[#1a1a3a] text-white placeholder-gray-500 border border-gray-600 focus:border-pink-500 focus:outline-none transition @error('name') border-red-500 @enderror"
                >
                @error('name')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="LoginEmail" class="block text-gray-400 text-sm mb-1">Email</label>
                <input
                    type="email"
                    id="LoginEmail"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                    class="w-full px-4 py-2.5 rounded-xl bg-[#1a1a3a] text-white placeholder-gray-500 border border-gray-600 focus:border-pink-500 focus:outline-none transition @error('email') border-red-500 @enderror"
                >
                @error('email')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password Field with Toggle -->
            <div x-data="{ show: false }">
                <label for="LoginPassword" class="block text-gray-400 text-sm mb-1">Password</label>
                <div class="relative">
                    <input
                        :type="show ? 'text' : 'password'"
                        id="LoginPassword"
                        name="password"
                        placeholder="Create a password"
                        class="w-full px-4 py-2.5 rounded-xl bg-[#1a1a3a] text-white placeholder-gray-500 border border-gray-600 focus:border-pink-500 focus:outline-none transition @error('password') border-red-500 @enderror"
                    >
                    <button
                        type="button"
                        @click="show = !show"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-300"
                    >
                        <i :class="show ? 'las la-eye-slash' : 'las la-eye'"></i>
                    </button>
                </div>
                @error('password')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password Field with Toggle -->
            <div x-data="{ show: false }">
                <label for="ConfirmPassword" class="block text-gray-400 text-sm mb-1">Confirm Password</label>
                <div class="relative">
                    <input
                        :type="show ? 'text' : 'password'"
                        id="ConfirmPassword"
                        name="password_confirmation"
                        placeholder="Confirm your password"
                        class="w-full px-4 py-2.5 rounded-xl bg-[#1a1a3a] text-white placeholder-gray-500 border border-gray-600 focus:border-pink-500 focus:outline-none transition"
                    >
                    <button
                        type="button"
                        @click="show = !show"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-300"
                    >
                        <i :class="show ? 'las la-eye-slash' : 'las la-eye'"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center text-sm">
                <input
                    type="checkbox"
                    id="Accept:T&C"
                    name="check"
                    class="w-4 h-4 text-pink-500 border-gray-600 rounded focus:ring-pink-500 focus:ring-offset-[#12122b] @error('check') border-red-500 @enderror"
                >
                <label for="Accept:T&C" class="ml-2 text-gray-400">
                    I agree to the <a href="#" class="text-pink-500 hover:text-pink-400 transition-colors">Terms and Conditions</a>
                </label>
            </div>
            @error('check')
            <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror

            <button
                type="submit"
                class="w-full bg-gradient-to-r from-pink-500 to-purple-600 text-white px-6 py-2.5 rounded-xl font-medium hover:opacity-90 transition-all"
            >
                Create Account
            </button>
        </form>

        <p class="text-center text-gray-400 text-sm">
            Already have an account?
            <a href="{{ route('login') }}" class="text-pink-500 hover:text-pink-400 transition-colors">
                Login
            </a>
        </p>
    </div>
@endsection

