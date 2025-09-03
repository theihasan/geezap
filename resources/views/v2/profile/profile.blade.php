@php use App\Enums\SkillProficiency; @endphp
@extends('v2.layouts.app')
@section('content')
    <!-- Profile Header -->
    <div class="bg-gray-50 dark:bg-[#12122b] border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-6">
                <!-- Profile Image -->
                <div class="relative group">
                    <img src="{{asset('assets/images/profile.jpg')}}" alt="{{ auth()->user()->name }}"
                         class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover border-2 border-blue-500/20 dark:border-pink-500/20">
                    <div class="absolute inset-0 bg-black/50 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <button class="text-white hover:text-blue-600 dark:hover:text-pink-500 transition-colors">
                            <i class="las la-camera text-2xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="flex-1 text-center sm:text-left">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2 font-oxanium-bold">{{ auth()->user()->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 font-ubuntu-regular">{{ auth()->user()->occupation }}</p>
                    <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-4 text-sm font-ubuntu-light">
                        @if(auth()->user()->state && auth()->user()->country)
                            <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                <i class="las la-map-marker"></i>
                                {{ auth()->user()->state }}, {{ auth()->user()->country }}
                            </span>
                        @endif
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                            <i class="las la-envelope"></i>
                            <span class="hidden sm:inline">{{ auth()->user()->email }}</span>
                            <span class="sm:hidden">Email</span>
                        </span>
                        @if(auth()->user()->phone)
                            <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                <i class="las la-phone"></i>
                                <span class="hidden sm:inline">{{ auth()->user()->phone }}</span>
                                <span class="sm:hidden">Phone</span>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3 mt-4 sm:mt-0">
                    <button class="bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-900 dark:text-white px-4 py-2 rounded-xl transition-colors flex items-center gap-2 font-ubuntu-medium text-sm sm:text-base relative cursor-not-allowed opacity-80">
                        <i class="las la-download"></i>
                        <span class="hidden sm:inline">Download CV</span>
                        <span class="sm:hidden">CV</span>
                        <span class="absolute -top-2 -right-2 bg-blue-500 dark:bg-pink-500 text-white text-xs py-1 px-2 rounded-full">Soon</span>
                    </button>
                    <a href="{{ route('profile.update') }}"
                       class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 hover:opacity-90 text-white px-4 sm:px-6 py-2 rounded-xl transition-all flex items-center gap-2 font-oxanium-semibold text-sm sm:text-base">
                        <i class="las la-edit"></i>
                        <span class="hidden sm:inline">Edit Profile</span>
                        <span class="sm:hidden">Edit</span>
                    </a>
                </div>


            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            <!-- Left Column -->
            <div class="space-y-6 sm:space-y-8">
                <!-- Personal Information -->
                <div class="bg-white dark:bg-[#12122b] rounded-2xl p-4 sm:p-6 border border-gray-200 dark:border-gray-800">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2 font-oxanium-semibold">
                        <i class="las la-user-circle text-blue-600 dark:text-pink-500"></i>
                        Personal Information
                    </h2>
                    <div class="space-y-4 font-ubuntu-light">
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400 block mb-1">Full Name</label>
                            <div class="text-gray-900 dark:text-white font-ubuntu-regular">{{ auth()->user()->name }}</div>
                        </div>
                        @if(auth()->user()->dob)
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400 block mb-1">Date of Birth</label>
                                <div class="text-gray-900 dark:text-white font-ubuntu-regular">{{ Carbon\Carbon::parse(auth()->user()->dob)->format('F d, Y') }}</div>
                            </div>
                        @endif
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400 block mb-1">Email</label>
                            <div class="text-gray-900 dark:text-white font-ubuntu-regular break-all">{{ auth()->user()->email }}</div>
                        </div>
                        @if(auth()->user()->phone)
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400 block mb-1">Phone</label>
                                <div class="text-gray-900 dark:text-white font-ubuntu-regular">{{ auth()->user()->phone }}</div>
                            </div>
                        @endif
                        @if(auth()->user()->address)
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400 block mb-1">Address</label>
                                <div class="text-gray-900 dark:text-white font-ubuntu-regular">{{ auth()->user()->address }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Social Links -->
                <div class="bg-white dark:bg-[#12122b] rounded-2xl p-4 sm:p-6 border border-gray-200 dark:border-gray-800">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2 font-oxanium-semibold">
                        <i class="las la-share-alt text-blue-600 dark:text-pink-500"></i>
                        Social Links
                    </h2>
                    <div class="space-y-4">
                        @foreach(['website', 'github', 'linkedin', 'twitter', 'facebook'] as $platform)
                            @if(auth()->user()->$platform)
                                <a href="{{ auth()->user()->$platform }}" target="_blank"
                                   class="flex items-center gap-3 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-pink-500 transition-colors">
                                    <i class="las la-{{ $platform }} text-2xl"></i>
                                    <span class="font-ubuntu-regular">{{ ucfirst($platform) }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Right Column -->
            <div class="lg:col-span-2 space-y-6 sm:space-y-8">
                @if(auth()->user()->bio)
                    <!-- Bio -->
                    <div class="bg-white dark:bg-[#12122b] rounded-2xl p-4 sm:p-6 border border-gray-200 dark:border-gray-800">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-user text-blue-600 dark:text-pink-500"></i>
                            About Me
                        </h2>
                        <p class="text-gray-700 dark:text-gray-300 font-ubuntu-light">
                            {{ auth()->user()->bio }}
                        </p>
                    </div>
                @endif

                <!-- Experience -->
                @if(auth()->user()->experience)
                    <div class="bg-white dark:bg-[#12122b] rounded-2xl p-4 sm:p-6 border border-gray-200 dark:border-gray-800">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-briefcase text-blue-600 dark:text-pink-500"></i>
                            Work Experience
                        </h2>
                        <div class="space-y-6 font-ubuntu-light">
                            @php
                                $experienceData = json_decode(auth()->user()->experience, true);
                            @endphp
                            @if(isset($experienceData['position']))
                                @foreach($experienceData['position'] as $index => $position)
                                    @php
                                        $startDate = !empty($experienceData['start_date'][$index]) ?
                                            \Carbon\Carbon::parse($experienceData['start_date'][$index]) : null;
                                        $endDate = !empty($experienceData['end_date'][$index]) ?
                                            \Carbon\Carbon::parse($experienceData['end_date'][$index]) : null;
                                        $isCurrentlyWorking = isset($experienceData['currently_working'][$index]) &&
                                            ($experienceData['currently_working'][$index] === 'on' ||
                                             $experienceData['currently_working'][$index] === true);
                                    @endphp

                                    <div class="border-l-2 border-blue-500/20 dark:border-pink-500/20 pl-4 sm:pl-6 {{ !$loop->last ? 'pb-6' : '' }} relative">
                                        <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full {{ $loop->first ? 'bg-blue-500 dark:bg-pink-500' : 'bg-blue-500/50 dark:bg-pink-500/50' }}"></div>
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 sm:gap-0">
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-white font-ubuntu-medium">{{ $position }}</h3>
                                                <p class="text-blue-600 dark:text-pink-400">{{ $experienceData['company_name'][$index] ?? '' }}</p>
                                            </div>

                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $startDate ? $startDate->format('M Y') : '' }}
                                                {{ $isCurrentlyWorking ? '- Present' : ($endDate ? '- ' . $endDate->format('M Y') : '') }}
                                            </span>
                                        </div>
                                        @if(!empty($experienceData['description'][$index]))
                                            <p class="text-gray-700 dark:text-gray-300 mt-2 font-ubuntu-regular text-sm sm:text-base">
                                                {{ $experienceData['description'][$index] }}
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-600 dark:text-gray-400">No experience added yet.</p>
                            @endif
                        </div>
                    </div>
                @endif
                <!-- Recommended Jobs Section -->
                @if($recommendedJobs && $recommendedJobs->count() > 0)
                    <div class="bg-white dark:bg-[#12122b] rounded-2xl p-6 border border-gray-200 dark:border-gray-800">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2 font-oxanium-semibold">
                                <i class="las la-star text-blue-600 dark:text-pink-500"></i>
                                Recommended Jobs for You
                            </h2>
                            <a href="{{ route('profile.preferences') }}" 
                               class="text-blue-600 dark:text-pink-500 hover:text-blue-700 dark:hover:text-pink-400 text-sm font-ubuntu-medium flex items-center gap-1">
                                <i class="las la-cog"></i>
                                Customize
                            </a>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($recommendedJobs as $job)
                                <div class="bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 rounded-xl p-4 transition-all border border-gray-200 dark:border-gray-700 hover:border-blue-500/30 dark:hover:border-pink-500/30">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h3 class="text-gray-900 dark:text-white font-ubuntu-medium text-sm mb-1 line-clamp-2">
                                                <a href="{{ route('job.show', $job->slug) }}" class="hover:text-blue-600 dark:hover:text-pink-500 transition-colors">
                                                    {{ $job->title }}
                                                </a>
                                            </h3>
                                            <p class="text-gray-600 dark:text-gray-400 text-xs font-ubuntu-light">{{ $job->company }}</p>
                                        </div>
                                        @if($job->is_remote)
                                            <span class="bg-green-500/10 text-green-600 dark:text-green-400 px-2 py-1 rounded-lg text-xs font-ubuntu-medium">
                                                Remote
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                            @if($job->country)
                                                <span class="flex items-center gap-1">
                                                    <i class="las la-map-marker"></i>
                                                    {{ $job->country }}
                                                </span>
                                            @endif
                                            @if($job->category)
                                                <span class="bg-blue-500/10 dark:bg-pink-500/10 text-blue-600 dark:text-pink-400 px-2 py-1 rounded">
                                                    {{ $job->category->name }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-gray-500 font-ubuntu-light">
                                            {{ $job->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 text-center">
                            <a href="{{ route('job.index') }}?recommended=1" 
                               class="text-blue-600 dark:text-pink-500 hover:text-blue-700 dark:hover:text-pink-400 text-sm font-ubuntu-medium inline-flex items-center gap-1">
                                View All Recommended Jobs
                                <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
