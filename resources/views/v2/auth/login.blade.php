@extends('v2.auth.app')
@section('title') Login - Geezap @endsection
@section('content')
    <!-- Login Container -->
    <div class="bg-white dark:bg-[#12122b] rounded-2xl shadow-lg p-8 max-w-lg w-full space-y-8 border border-gray-200 dark:border-gray-800">
        <div class="text-center space-y-2">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white font-oxanium-bold">Welcome Back</h2>
            <p class="text-gray-600 dark:text-gray-400 font-ubuntu-light">Please login to your account</p>
        </div>

        @session('status')
        <div class="flex justify-center">
            <span class="text-red-500">{{$value}}</span>
        </div>
        @endsession
        <div class="flex justify-center mb-4">
            <a href="{{ route('home') }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-pink-500 transition-colors flex items-center gap-2 font-ubuntu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Home
            </a>
        </div>

        <!-- Social Login Options -->
        <x-social-login/>

        <!-- Divider -->
        <div class="flex items-center my-6">
            <hr class="flex-grow border-t border-gray-300 dark:border-gray-600">
            <span class="mx-4 text-gray-600 dark:text-gray-400 font-ubuntu-light">or continue with email</span>
            <hr class="flex-grow border-t border-gray-300 dark:border-gray-600">
        </div>

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-6 font-ubuntu">
            @csrf
            <div>
                <label for="LoginEmail" class="block text-gray-700 dark:text-gray-400 mb-1">Email Address</label>
                <input
                    type="email"
                    id="LoginEmail"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="name@example.com"
                    class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-[#1a1a3a] text-gray-900 dark:text-white placeholder-gray-500 border border-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none transition"
                >
                @error('email')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>
            <input type="hidden" name="intended_url" value="{{ url()->previous() }}">
            <div>
                <label for="LoginPassword" class="block text-gray-700 dark:text-gray-400 mb-1">Password</label>
                <div class="relative">
                    <input
                        type="password"
                        id="LoginPassword"
                        name="password"
                        placeholder="Enter your password"
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-[#1a1a3a] text-gray-900 dark:text-white placeholder-gray-500 border border-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none transition pr-12"
                    >
                    <button
                        type="button"
                        onclick="togglePassword('LoginPassword', this)"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"
                    >
                        <svg
                            class="eye-open w-5 h-5"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg
                            class="eye-closed w-5 h-5 hidden"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
                @error('password')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-between items-center">
                <label class="flex items-center text-gray-700 dark:text-gray-400">
                    <input
                        type="checkbox"
                        name="remember"
                        id="RememberMe"
                        class="text-blue-500 dark:text-pink-500 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 dark:focus:ring-pink-500 focus:ring-offset-white dark:focus:ring-offset-[#12122b]"
                    >
                    <span class="ml-2">Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-blue-600 dark:text-pink-500 hover:text-blue-700 dark:hover:text-pink-400 transition-colors">
                    Forgot Password?
                </a>
            </div>

            <button
                type="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white px-6 py-3 rounded-xl font-medium hover:opacity-90 transition-all"
            >
                Login
            </button>
        </form>

        <!-- Register Link -->
        <p class="text-center text-gray-600 dark:text-gray-400 mt-6 font-ubuntu-light">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-blue-600 dark:text-pink-500 hover:text-blue-700 dark:hover:text-pink-400 transition-colors font-ubuntu-medium">
                Sign up
            </a>
        </p>
    </div>
@endsection
@push('extra-js')
    <script>
        function togglePassword(inputId, button) {
            const passwordInput = document.getElementById(inputId);
            const eyeOpen = button.querySelector('.eye-open');
            const eyeClosed = button.querySelector('.eye-closed');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
@endpush
