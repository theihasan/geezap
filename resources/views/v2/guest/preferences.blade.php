@extends('v2.layouts.app')
@section('content')
    <!-- Header -->
    <div class="bg-[#12122b] border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
            <div class="text-center">
                <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2 font-oxanium-bold">Find Your Perfect Job</h1>
                <p class="text-gray-400 font-ubuntu-regular">Set your preferences to get personalized job recommendations</p>
                <div class="mt-4 bg-pink-500/10 border border-pink-500/20 text-pink-400 px-4 py-2 rounded-xl inline-block">
                    <i class="las la-info-circle"></i>
                    <span class="text-sm">Limited to 10 job views per day. <a href="{{ route('register') }}" class="underline hover:text-pink-300">Register</a> for unlimited access!</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('guest.preferences.update') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Column 1: Categories & Regions -->
                <div class="space-y-6">
                    <!-- Job Categories -->
                    <div class="bg-[#12122b] rounded-2xl p-6 border border-gray-800">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-briefcase text-pink-500"></i>
                            Job Categories
                        </h2>
                        <div class="grid grid-cols-1 gap-2 max-h-64 overflow-y-auto">
                            @foreach($jobCategories->take(12) as $category)
                                <label class="relative cursor-pointer">
                                    <input type="checkbox" name="preferred_job_topics[]" value="{{ $category->id }}" 
                                           {{ in_array($category->id, $preferences?->preferred_job_topics ?? []) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="p-3 bg-white/5 hover:bg-white/10 border border-gray-700 peer-checked:border-pink-500 peer-checked:bg-pink-500/10 rounded-lg transition-all">
                                        <div class="text-white font-ubuntu-medium text-sm">{{ $category->name }}</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Preferred Regions -->
                    <div class="bg-[#12122b] rounded-2xl p-6 border border-gray-800">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-globe text-pink-500"></i>
                            Preferred Regions
                        </h2>
                        <div class="grid grid-cols-1 gap-2 max-h-64 overflow-y-auto">
                            @foreach($countries->take(20) as $country)
                                <label class="relative cursor-pointer">
                                    <input type="checkbox" name="preferred_job_categories_id[]" value="{{ $category->id }}" 
                                           {{ in_array($category->id, $preferences?->preferred_job_categories_id ?? []) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="p-3 bg-white/5 hover:bg-white/10 border border-gray-700 peer-checked:border-pink-500 peer-checked:bg-pink-500/10 rounded-lg transition-all">
                                        <div class="text-white font-ubuntu-medium text-sm">{{ $country->name }}</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Column 2: Job Types & Email Settings -->
                <div class="space-y-6">
                    <!-- Job Types & Remote -->
                    <div class="bg-[#12122b] rounded-2xl p-6 border border-gray-800">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-clock text-pink-500"></i>
                            Job Type
                        </h2>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 gap-3">
                                @foreach(['full-time' => 'Full Time', 'part-time' => 'Part Time', 'contract' => 'Contract', 'freelance' => 'Freelance'] as $value => $label)
                                    <label class="relative cursor-pointer">
                                        <input type="checkbox" name="preferred_job_types[]" value="{{ $value }}" 
                                               {{ in_array($value, $preferences?->preferred_job_types ?? []) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="p-3 bg-white/5 hover:bg-white/10 border border-gray-700 peer-checked:border-pink-500 peer-checked:bg-pink-500/10 rounded-lg transition-all">
                                            <div class="text-white font-ubuntu-medium text-sm">{{ $label }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg border border-gray-700">
                                <div>
                                    <h3 class="text-white font-ubuntu-medium text-sm">Remote Only</h3>
                                    <p class="text-gray-400 text-xs">Show only remote positions</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="remote_only" value="1" 
                                           {{ $preferences?->remote_only ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Email Alerts -->
                    <div class="bg-[#12122b] rounded-2xl p-6 border border-gray-800">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-envelope text-pink-500"></i>
                            Email Alerts
                            <span class="bg-yellow-500/10 text-yellow-400 px-2 py-1 rounded text-xs">Limited</span>
                        </h2>
                        <div class="space-y-4">
                            <input type="email" name="email" value="{{ $preferences?->email }}" 
                                   placeholder="Enter your email for job alerts"
                                   class="w-full bg-white/5 border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:border-pink-500 focus:outline-none">
                            
                            <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg border border-gray-700">
                                <div>
                                    <h3 class="text-white font-ubuntu-medium text-sm">Weekly Job Alerts</h3>
                                    <p class="text-gray-400 text-xs">Get weekly emails with matching jobs</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="email_alerts_enabled" value="1" 
                                           {{ $preferences?->email_alerts_enabled ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-pink-500 to-purple-600 hover:opacity-90 text-white px-6 py-4 rounded-xl transition-all flex items-center justify-center gap-2 font-oxanium-semibold text-lg">
                        <i class="las la-save"></i>
                        Update Preferences
                    </button>
                </div>

                <!-- Column 3: Recommended Jobs -->
                <div>
                    <div class="bg-[#12122b] rounded-2xl p-6 border border-gray-800 sticky top-6">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-star text-pink-500"></i>
                            Recommended Jobs
                            @if($preferences && $preferences->hasReachedDailyLimit())
                                <span class="bg-red-500/10 text-red-400 px-2 py-1 rounded text-xs">Daily Limit Reached</span>
                            @endif
                        </h2>
                        
                        @if($preferences && $preferences->hasReachedDailyLimit())
                            <div class="text-center py-8">
                                <i class="las la-lock text-4xl text-gray-600 mb-4"></i>
                                <p class="text-gray-400 mb-4">You've reached your daily limit of 10 job views.</p>
                                <a href="{{ route('register') }}" 
                                   class="bg-gradient-to-r from-pink-500 to-purple-600 hover:opacity-90 text-white px-6 py-2 rounded-xl transition-all inline-flex items-center gap-2 font-oxanium-semibold">
                                    <i class="las la-user-plus"></i>
                                    Register for Unlimited Access
                                </a>
                            </div>
                        @elseif($recommendedJobs && $recommendedJobs->count() > 0)
                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                @foreach($recommendedJobs as $job)
                                    <div class="bg-white/5 hover:bg-white/10 rounded-lg p-4 transition-all border border-gray-700 hover:border-pink-500/30">
                                        <h3 class="text-white font-ubuntu-medium text-sm mb-2 line-clamp-2">
                                            <a href="{{ route('job.show', $job->slug) }}" class="hover:text-pink-500 transition-colors">
                                                {{ $job->title }}
                                            </a>
                                        </h3>
                                        <p class="text-gray-400 text-xs mb-2">{{ $job->company }}</p>
                                        <div class="flex items-center justify-between text-xs">
                                            <div class="flex items-center gap-2 text-gray-400">
                                                @if($job->country)
                                                    <span class="flex items-center gap-1">
                                                        <i class="las la-map-marker"></i>
                                                        {{ $job->country }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if($job->is_remote)
                                                <span class="bg-green-500/10 text-green-400 px-2 py-1 rounded">
                                                    Remote
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-4 text-center">
                                <p class="text-gray-400 text-xs mb-2">
                                    @if($preferences)
                                        {{ 10 - $preferences->daily_views }} views remaining today
                                    @else
                                        10 views remaining today
                                    @endif
                                </p>
                                <a href="{{ route('register') }}" 
                                   class="text-pink-500 hover:text-pink-400 text-sm font-ubuntu-medium inline-flex items-center gap-1">
                                    Register for unlimited access
                                    <i class="las la-arrow-right"></i>
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="las la-search text-4xl text-gray-600 mb-4"></i>
                                <p class="text-gray-400 mb-4">Set your preferences above to see personalized job recommendations.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection