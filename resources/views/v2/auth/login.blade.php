@extends('v2.auth.app')
@section('content')

        <!-- Login Container -->
        <div class="bg-[#12122b] rounded-2xl shadow-lg p-8 max-w-lg w-full space-y-8 border border-gray-800">
            <div class="text-center space-y-2">
                <h2 class="text-3xl font-bold text-white font-oxanium-bold">Welcome Back</h2>
                <p class="text-gray-400 font-ubuntu-light">Please login to your account</p>
            </div>

            <!-- Social Login Options -->
           <x-social-login/>

            <!-- Divider -->
            <div class="flex items-center my-6">
                <hr class="flex-grow border-t border-gray-600">
                <span class="mx-4 text-gray-400 font-ubuntu-light">or continue with email</span>
                <hr class="flex-grow border-t border-gray-600">
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-6 font-ubuntu">
                @csrf
                <div>
                    <label for="LoginEmail" class="block text-gray-400 mb-1">Email Address</label>
                    <input
                        type="email"
                        id="LoginEmail"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="name@example.com"
                        class="w-full px-4 py-3 rounded-xl bg-[#1a1a3a] text-white placeholder-gray-500 border border-gray-600 focus:border-pink-500 focus:outline-none transition"
                    >
                    @error('email')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="LoginPassword" class="block text-gray-400 mb-1">Password</label>
                    <input
                        type="password"
                        id="LoginPassword"
                        name="password"
                        placeholder="Enter your password"
                        class="w-full px-4 py-3 rounded-xl bg-[#1a1a3a] text-white placeholder-gray-500 border border-gray-600 focus:border-pink-500 focus:outline-none transition"
                    >
                    @error('password')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-between items-center">
                    <label class="flex items-center text-gray-400">
                        <input
                            type="checkbox"
                            name="remember"
                            id="RememberMe"
                            class="text-pink-500 border-gray-600 rounded focus:ring-pink-500 focus:ring-offset-[#12122b]"
                        >
                        <span class="ml-2">Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-pink-500 hover:text-pink-400 transition-colors">
                        Forgot Password?
                    </a>
                </div>

                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-pink-500 to-purple-600 text-white px-6 py-3 rounded-xl font-medium hover:opacity-90 transition-all"
                >
                    Login
                </button>
            </form>

            <!-- Register Link -->
            <p class="text-center text-gray-400 mt-6 font-ubuntu-light">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-pink-500 hover:text-pink-400 transition-colors font-ubuntu-medium">
                    Sign up
                </a>
            </p>
        </div>

@endsection
