@extends('v2.auth.app')
@section('title') Job Preferences - Onboarding @endsection
@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-lg mx-auto">
            <!-- Compact Progress Header -->
            <div class="text-center mb-6">
                <div class="flex items-center justify-center space-x-2 mb-3">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                    <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                    <div class="w-2 h-2 bg-indigo-500 rounded-full ring-2 ring-indigo-200 dark:ring-indigo-800"></div>
                </div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Your Preferences</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Step 3 of 3 • Almost done!</p>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-800">
                <form action="{{ route('onboarding.preferences.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    
                    <!-- Notification Preferences -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Notifications</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                                <div>
                                    <label for="email_notifications" class="font-medium text-gray-900 dark:text-white">Email alerts</label>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">New job notifications</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="email_notifications" value="0">
                                    <input 
                                        type="checkbox" 
                                        id="email_notifications" 
                                        name="email_notifications" 
                                        value="1"
                                        {{ $preferences->email_notifications ? 'checked' : '' }}
                                        class="sr-only peer"
                                    >
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                                <div>
                                    <label for="job_alerts" class="font-medium text-gray-900 dark:text-white">Job alerts</label>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Matching opportunities</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="job_alerts" value="0">
                                    <input 
                                        type="checkbox" 
                                        id="job_alerts" 
                                        name="job_alerts" 
                                        value="1"
                                        {{ $preferences->job_alerts ? 'checked' : '' }}
                                        class="sr-only peer"
                                    >
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                                <div>
                                    <label for="newsletter" class="font-medium text-gray-900 dark:text-white">Newsletter</label>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Weekly career tips</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="newsletter" value="0">
                                    <input 
                                        type="checkbox" 
                                        id="newsletter" 
                                        name="newsletter" 
                                        value="1"
                                        {{ $preferences->newsletter ? 'checked' : '' }}
                                        class="sr-only peer"
                                    >
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                                <div>
                                    <label for="marketing_emails" class="font-medium text-gray-900 dark:text-white">Updates</label>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Features & promotions</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="marketing_emails" value="0">
                                    <input 
                                        type="checkbox" 
                                        id="marketing_emails" 
                                        name="marketing_emails" 
                                        value="1"
                                        {{ $preferences->marketing_emails ? 'checked' : '' }}
                                        class="sr-only peer"
                                    >
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Job Preferences -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Job Discovery</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                                <div>
                                    <label for="remote_only" class="font-medium text-gray-900 dark:text-white">Remote only</label>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Filter remote positions</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="remote_only" value="0">
                                    <input 
                                        type="checkbox" 
                                        id="remote_only" 
                                        name="remote_only" 
                                        value="1"
                                        {{ $preferences->remote_only ? 'checked' : '' }}
                                        class="sr-only peer"
                                    >
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                                <div>
                                    <label for="show_recommendations" class="font-medium text-gray-900 dark:text-white">AI suggestions</label>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Personalized matches</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="show_recommendations" value="0">
                                    <input 
                                        type="checkbox" 
                                        id="show_recommendations" 
                                        name="show_recommendations" 
                                        value="1"
                                        {{ $preferences->show_recommendations ? 'checked' : '' }}
                                        class="sr-only peer"
                                    >
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-4">
                        <button
                            type="submit"
                            class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-colors duration-200 flex items-center justify-center space-x-2"
                        >
                            <span>Complete Setup</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                        
                        <a href="{{ route('onboarding.skip') }}" 
                           class="px-4 py-3 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 text-center transition-colors duration-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg text-sm">
                            Skip
                        </a>
                    </div>
                </form>
            </div>

            <!-- Footer Navigation -->
            <div class="flex items-center justify-between mt-4 px-2">
                <a href="{{ route('onboarding.essential-info') }}" 
                   class="flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back
                </a>
                
                <div class="text-xs text-gray-400 dark:text-gray-500">
                    Privacy protected • <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:underline">Help</a>
                </div>
            </div>
        </div>
    </div>
@endsection