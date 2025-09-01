@extends('v2.layouts.app')
@section('content')
    <section class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-[#12122b] relative py-20">
        <div class="absolute inset-0 opacity-10" style="background-image: url('https://placehold.co/1920x800/2a2a4a/FFFFFF'); background-size: cover;"></div>

        <div class="max-w-4xl mx-auto px-6 relative">
            <div class="text-center space-y-12">
                <div class="relative">
                    <svg class="w-96 h-48 mx-auto" viewBox="0 0 400 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 20L50 20L50 80M50 50L20 50L80 50" stroke="url(#gradient)" stroke-width="4"/>
                        <circle cx="150" cy="50" r="30" stroke="url(#gradient)" stroke-width="4"/>
                        <path d="M250 20L250 80" stroke="url(#gradient)" stroke-width="4"/>
                        <path d="M330 50C330 38.954 338.954 30 350 30C361.046 30 370 38.954 370 50C370 61.046 361.046 70 350 70C338.954 70 330 61.046 330 50Z"
                              stroke="url(#gradient)" stroke-width="4"/>
                        <path d="M350 45L350 55M340 50L360 50" stroke="url(#gradient)" stroke-width="4"/>
                        <defs>
                            <linearGradient id="gradient" x1="0" y1="0" x2="400" y2="100">
                                <stop offset="0%" stop-color="#3B82F6"/>
                                <stop offset="100%" stop-color="#1D4ED8"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>

                <div class="space-y-4">
                    <h2 class="text-4xl font-oxanium-bold text-gray-900 dark:text-white">
                        Authentication Required
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 font-ubuntu-light max-w-2xl mx-auto">
                        You need to be logged in to access this page.
                        Please sign in with your credentials or create an account to continue.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    <a href="{{ route('login') }}"
                       class="font-ubuntu-regular bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white px-8 py-3 rounded-xl hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                        <i class="las la-sign-in-alt"></i>
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="font-ubuntu-regular bg-gray-100 dark:bg-[#1a1a3a] text-gray-900 dark:text-white px-8 py-3 rounded-xl hover:bg-gray-200 dark:hover:bg-[#1a1a3a]/80 transition-all flex items-center justify-center gap-2">
                        <i class="las la-user-plus"></i>
                        Create Account
                    </a>
                </div>

                <!-- Help Options -->
                <div class="bg-gray-100 dark:bg-[#1a1a3a]/50 p-6 rounded-xl max-w-xl mx-auto">
                    <h3 class="text-gray-900 dark:text-white font-oxanium-bold mb-4">Having Trouble Signing In?</h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="p-4 bg-white dark:bg-[#12122b] rounded-lg">
                            <i class="las la-key text-blue-600 dark:text-pink-500 text-2xl mb-2"></i>
                            <h4 class="text-gray-900 dark:text-white font-semibold mb-2">Forgot Password?</h4>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Reset your password via email verification</p>
                            <a href="{{ route('password.request') }}" class="text-blue-600 dark:text-pink-400 hover:text-blue-700 dark:hover:text-pink-300 text-sm flex items-center justify-center gap-1">
                                Reset Password
                                <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                        <div class="p-4 bg-white dark:bg-[#12122b] rounded-lg">
                            <i class="las la-question-circle text-blue-600 dark:text-pink-500 text-2xl mb-2"></i>
                            <h4 class="text-gray-900 dark:text-white font-semibold mb-2">Need Help?</h4>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Contact our support team for assistance</p>
                            <a href="#" class="text-blue-600 dark:text-pink-400 hover:text-blue-700 dark:hover:text-pink-300 text-sm flex items-center justify-center gap-1">
                                Contact Support
                                <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <a href="{{ route('home') }}"
                       class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-pink-500 transition-colors inline-flex items-center gap-2">
                        <i class="las la-arrow-left"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
