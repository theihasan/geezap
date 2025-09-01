@extends('v2.layouts.app')
@section('content')
    <section class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-[#12122b] relative py-20">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10" style="background-image: url('https://placehold.co/1920x800/2a2a4a/FFFFFF'); background-size: cover;"></div>

        <div class="max-w-4xl mx-auto px-6 relative">
            <div class="text-center space-y-12">
                <!-- 500 SVG -->
                <div class="relative">
                    <svg class="w-96 h-48 mx-auto" viewBox="0 0 400 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- 5 -->
                        <path d="M80 20H20V50C20 50 20 50 50 50C80 50 80 80 50 80C20 80 20 80 20 80"
                              stroke="url(#gradient)" stroke-width="4"/>
                        <!-- 0 -->
                        <circle cx="150" cy="50" r="30" stroke="url(#gradient)" stroke-width="4"/>
                        <!-- 0 -->
                        <circle cx="250" cy="50" r="30" stroke="url(#gradient)" stroke-width="4"/>
                        <!-- Server Icon -->
                        <path d="M310 20H390V40H310V20ZM310 45H390V65H310V45ZM310 70H390V90H310V70Z"
                              stroke="url(#gradient)" stroke-width="4"/>
                        <defs>
                            <linearGradient id="gradient" x1="0" y1="0" x2="400" y2="100">
                                <stop offset="0%" stop-color="#3B82F6"/>
                                <stop offset="100%" stop-color="#1D4ED8"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>

                <!-- Message -->
                <div class="space-y-4">
                    <h2 class="text-4xl font-oxanium-bold text-gray-900 dark:text-white">
                        Server Error
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 font-ubuntu-light max-w-2xl mx-auto">
                        Oops! Something went wrong on our end. Our team has been notified and is working to fix the issue.
                        Please try again in a few moments.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    <a href="{{ url()->previous() }}"
                       class="font-ubuntu-regular bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white px-8 py-3 rounded-xl hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                        <i class="las la-redo-alt"></i>
                        Try Again
                    </a>
                    <a href="{{ route('home') }}"
                       class="font-ubuntu-regular bg-gray-100 dark:bg-[#1a1a3a] text-gray-900 dark:text-white px-8 py-3 rounded-xl hover:bg-gray-200 dark:hover:bg-[#1a1a3a]/80 transition-all flex items-center justify-center gap-2">
                        <i class="las la-home"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
