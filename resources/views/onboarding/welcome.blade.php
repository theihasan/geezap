@extends('v2.auth.app')
@section('title') Welcome to Geezap - Let's get you started @endsection
@section('content')
    <div class="min-h-screen flex items-center justify-center py-6 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-lg mx-auto">
            <!-- Progress Bar -->
            <div class="mb-6">
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">Step 1 of 3</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Welcome</span>
                </div>
                <div class="flex space-x-2">
                    <div class="flex-1 h-2 bg-indigo-600 rounded-full"></div>
                    <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                    <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 p-6 sm:p-8">
                <!-- Header -->
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Welcome to Geezap</h1>
                    <p class="text-gray-600 dark:text-gray-400">Ready to find your next opportunity? Let's get you set up in just 2 minutes.</p>
                </div>

                <!-- Quick Steps -->
                <div class="space-y-3 mb-8">
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                        <div class="flex-shrink-0 w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Tell us about yourself</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Occupation and location</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Set your preferences</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Notifications and job types</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                        <div class="flex-shrink-0 w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Start job hunting</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Discover personalized opportunities</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <a href="{{ route('onboarding.essential-info') }}" 
                       class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold text-center hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 block">
                        Get Started
                    </a>
                    
                    <a href="{{ route('onboarding.skip') }}" 
                       class="w-full text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 px-6 py-3 text-center text-sm transition-colors duration-200 block hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl">
                        Skip for now
                    </a>
                </div>
            </div>

            <!-- Help Link -->
            <div class="text-center mt-4 text-xs text-gray-500 dark:text-gray-400">
                Need help? <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:underline">Contact support</a>
            </div>
        </div>
    </div>
@endsection