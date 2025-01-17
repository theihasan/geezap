@extends('v2.layouts.app')
@section('content')
    <section class="min-h-screen flex items-center justify-center bg-[#12122b] relative py-20">
        <div class="absolute inset-0 opacity-10" style="background-image: url('https://placehold.co/1920x800/2a2a4a/FFFFFF'); background-size: cover;"></div>

        <div class="max-w-4xl mx-auto px-6 relative">
            <div class="text-center space-y-12">
                <div class="relative">
                    <svg class="w-96 h-48 mx-auto" viewBox="0 0 400 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 20L50 20L50 80M50 50L20 50L80 50" stroke="url(#gradient)" stroke-width="4"/>
                        <circle cx="150" cy="50" r="30" stroke="url(#gradient)" stroke-width="4"/>
                        <path d="M280 25C280 25 280 25 250 25C220 25 220 50 250 50C220 50 220 75 250 75C280 75 280 75 280 75"
                              stroke="url(#gradient)" stroke-width="4"/>
                        <path d="M350 45V35C350 25 342 15 330 15C318 15 310 25 310 35V45M305 45H355V85H305V45Z"
                              stroke="url(#gradient)" stroke-width="4"/>
                        <defs>
                            <linearGradient id="gradient" x1="0" y1="0" x2="400" y2="100">
                                <stop offset="0%" stop-color="#EC4899"/>
                                <stop offset="100%" stop-color="#8B5CF6"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>

                <div class="space-y-4">
                    <h2 class="text-4xl font-oxanium-bold text-white">
                        Access Denied
                    </h2>
                    <p class="text-xl text-gray-300 font-ubuntu-light max-w-2xl mx-auto">
                        Sorry, you don't have permission to access this page.
                        Please make sure you're logged in with the correct credentials.
                    </p>
                </div>

                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    <a href="{{ route('home') }}"
                       class="font-ubuntu-regular bg-gradient-to-r from-pink-500 to-purple-600 text-white px-8 py-3 rounded-xl hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                        <i class="las la-home"></i>
                        Back to Home
                    </a>
                    <a href="{{ route('login') }}"
                       class="font-ubuntu-regular bg-[#1a1a3a] text-white px-8 py-3 rounded-xl hover:bg-[#1a1a3a]/80 transition-all flex items-center justify-center gap-2">
                        <i class="las la-sign-in-alt"></i>
                        Login
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
