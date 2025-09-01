@extends('v2.layouts.app')
@section('content')
    <section class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-[#12122b] relative py-20">
        <div class="absolute inset-0 opacity-10" style="background-image: url('https://placehold.co/1920x800/2a2a4a/FFFFFF'); background-size: cover;"></div>

        <div class="max-w-4xl mx-auto px-6 relative">
            <div class="text-center space-y-12">
                <div class="relative">
                    <svg class="w-96 h-48 mx-auto" viewBox="0 0 400 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 20L50 20L50 80M50 50L20 50L80 50" stroke="url(#gradient)" stroke-width="4"/>
                        <path d="M120 20L120 80" stroke="url(#gradient)" stroke-width="4"/>
                        <path d="M190 50C190 33.4315 203.431 20 220 20C236.569 20 250 33.4315 250 50M220 80L220 50"
                              stroke="url(#gradient)" stroke-width="4"/>
                        <circle cx="340" cy="50" r="30" stroke="url(#gradient)" stroke-width="4"/>
                        <path d="M340 35V50L350 60" stroke="url(#gradient)" stroke-width="4"/>
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
                        Page Expired
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 font-ubuntu-light max-w-2xl mx-auto">
                        Your session has timed out due to inactivity. Please refresh the page and try again.
                    </p>
                </div>

                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    <button onclick="window.location.reload()"
                            class="font-ubuntu-regular bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white px-8 py-3 rounded-xl hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                        <i class="las la-sync"></i>
                        Refresh Page
                    </button>
                    <a href="{{ route('home') }}"
                       class="font-ubuntu-regular bg-gray-100 dark:bg-[#1a1a3a] text-gray-900 dark:text-white px-8 py-3 rounded-xl hover:bg-gray-200 dark:hover:bg-[#1a1a3a]/80 transition-all flex items-center justify-center gap-2">
                        <i class="las la-home"></i>
                        Back to Home
                    </a>
                </div>

                <div class="bg-gray-100 dark:bg-[#1a1a3a]/50 p-6 rounded-xl max-w-xl mx-auto">
                    <h3 class="text-gray-900 dark:text-white font-oxanium-bold mb-4">Why Did This Happen?</h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3 text-left">
                            <i class="las la-clock text-blue-600 dark:text-pink-500 text-xl mt-1"></i>
                            <div>
                                <h4 class="text-gray-900 dark:text-white font-semibold">Session Timeout</h4>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Your session expired after a period of inactivity for security reasons.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 text-left">
                            <i class="las la-shield-alt text-blue-600 dark:text-pink-500 text-xl mt-1"></i>
                            <div>
                                <h4 class="text-gray-900 dark:text-white font-semibold">Security Measure</h4>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">This helps protect your data and maintain secure browsing.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap justify-center gap-3">
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 bg-gray-100 dark:bg-[#1a1a3a] hover:bg-gray-200 dark:hover:bg-[#1a1a3a]/80 text-gray-700 dark:text-gray-100 rounded-full text-sm transition-colors flex items-center gap-2">
                        <i class="las la-sign-in-alt"></i>
                        Login Again
                    </a>
                    <a href="#"
                       class="px-4 py-2 bg-gray-100 dark:bg-[#1a1a3a] hover:bg-gray-200 dark:hover:bg-[#1a1a3a]/80 text-gray-700 dark:text-gray-100 rounded-full text-sm transition-colors flex items-center gap-2">
                        <i class="las la-question-circle"></i>
                        Get Help
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
