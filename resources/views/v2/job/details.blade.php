@extends('v2.layouts.app')

@push('extra-css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
@endpush

@section('content')
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <nav class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-6">
                <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-pink-300 transition-colors">Home</a>
                <i class="las la-angle-right"></i>
                <a href="{{ route('job.index') }}" class="hover:text-blue-600 dark:hover:text-pink-300 transition-colors">Jobs</a>
                <i class="las la-angle-right"></i>
                <span class="text-gray-900 dark:text-white">{{ $job->job_title }}</span>
            </nav>

            <!-- Job Details Main Section -->
            <!-- Enhanced Job Header -->
            <div class="bg-gradient-to-br from-white to-gray-50 dark:from-[#1a1a3a] dark:to-[#2a2a4a] p-6 sm:p-8 rounded-3xl border border-gray-200 dark:border-gray-700 mb-8 shadow-lg">
                <!-- Header with Job Title and Actions -->
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6 mb-8">
                    <!-- Left: Job Title and Company Info -->
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 text-sm bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full font-medium">
                                    <i class="las la-clock mr-1"></i>
                                    {{ $job->posted_at?->diffForHumans() }}
                                </span>
                                @if($job->is_remote)
                                    <span class="px-3 py-1 text-sm bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full font-medium">
                                        <i class="las la-laptop mr-1"></i>
                                        Remote
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Share & Save Actions - Mobile/Small screens -->
                            <div class="flex items-center gap-2 lg:hidden">
                                <button onclick="shareJob()" class="p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-pink-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-all">
                                    <i class="las la-share-alt text-xl"></i>
                                </button>
                        <livewire:jobs.bookmark-job :job="$job" />
                            </div>
                        </div>

                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white font-oxanium-semibold mb-6 leading-tight">
                            {{ $job->job_title }}
                        </h1>
                        
                        <!-- Company and Location Info -->
                        <div class="flex flex-wrap items-center gap-6 text-lg">
                            <div class="flex items-center gap-3">
                                @if($job->employer_logo)
                                    <img src="{{ $job->employer_logo }}" alt="{{ $job->employer_name }}" class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white text-lg font-bold">
                                        {{ substr($job->employer_name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-gray-900 dark:text-white font-bold">{{ $job->employer_name }}</p>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $job->industry ?? 'Technology' }}</p>
                                </div>
                            </div>
                            
                            @if($job->country)
                                <div class="flex items-center gap-2">
                                    <i class="las la-map-marker text-gray-500 dark:text-gray-400 text-xl"></i>
                                    <span class="text-gray-700 dark:text-gray-300 font-medium">
                                        {{ \App\Helpers\CountryFlag::getCountry($job->country) }}
                                        <span class="ml-2">{{ \App\Helpers\CountryFlag::getFlag($job->country) }}</span>
                                    </span>
                                </div>
                            @endif
                            
                            <div class="flex items-center gap-2">
                                <i class="las {{ $job->is_remote ? 'la-laptop' : 'la-building' }} text-gray-500 dark:text-gray-400 text-xl"></i>
                                <span class="text-gray-700 dark:text-gray-300 font-medium">
                                    {{ $job->is_remote ? 'Remote Work' : 'On-site' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right: Actions for larger screens -->
                    <div class="hidden lg:flex flex-col items-end gap-4">
                        <div class="flex items-center gap-3">
                            <button onclick="shareJob()" class="p-3 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-pink-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-all">
                                <i class="las la-share-alt text-xl"></i>
                            </button>
                            <livewire:jobs.bookmark-job :job="$job" />
                        </div>
                        
                        @if($job->min_salary && $job->max_salary)
                            <div class="text-right">
                                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Salary Range</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    ${{ number_format($job->min_salary/1000) }}k - ${{ number_format($job->max_salary/1000) }}k
                                </p>
                                @if($job->salary_period)
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">/ {{ $job->salary_period }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Job Metadata - Inline Style -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <!-- Left: Job Metadata -->
                        <div class="flex flex-wrap items-center gap-6 text-sm">
                            <!-- Employment Type -->
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500 dark:text-gray-400 font-medium">Type:</span>
                                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full font-semibold">
                                    {{ $job->employment_type }}
                                </span>
                            </div>

                            <!-- Posted Date -->
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500 dark:text-gray-400 font-medium">Posted:</span>
                                <span class="text-gray-900 dark:text-white font-semibold">
                                    {{ $job->posted_at?->isoFormat('MMM DD, YYYY') }}
                                </span>
                            </div>

                            @if($job->required_experience)
                                <!-- Experience Required -->
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Experience:</span>
                                    <span class="text-gray-900 dark:text-white font-semibold">
                                        {{ number_format($job->required_experience / 12, 1) }}+ Years
                                    </span>
                                </div>
                            @endif

                            <!-- Mobile Salary Display -->
                            @if($job->min_salary && $job->max_salary)
                                <div class="flex items-center gap-2 lg:hidden">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Salary:</span>
                                    <span class="text-gray-900 dark:text-white font-semibold">
                                        ${{ number_format($job->min_salary/1000) }}k - ${{ number_format($job->max_salary/1000) }}k
                                        @if($job->salary_period)
                                            <span class="text-gray-500 dark:text-gray-400">/ {{ $job->salary_period }}</span>
                                        @endif
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Right: Social Share Icons -->
                        <div class="flex items-center gap-2">
                            <span class="text-gray-500 dark:text-gray-400 text-sm font-medium mr-2">Share:</span>
                            <div class="flex items-center gap-2">
                                <!-- Facebook -->
                                <button onclick="shareOnFacebook()" class="w-9 h-9 bg-[#1877F2] hover:bg-[#166FE5] text-white rounded-lg flex items-center justify-center transition-colors duration-200" title="Share on Facebook">
                                    <i class="lab la-facebook-f text-sm"></i>
                                </button>
                                
                                <!-- X (Twitter) -->
                                <button onclick="shareOnTwitter()" class="w-9 h-9 bg-black hover:bg-gray-800 text-white rounded-lg flex items-center justify-center transition-colors duration-200" title="Share on X">
                                    <i class="lab la-twitter text-sm"></i>
                                </button>
                                
                                <!-- LinkedIn -->
                                <button onclick="shareOnLinkedIn()" class="w-9 h-9 bg-[#0A66C2] hover:bg-[#004182] text-white rounded-lg flex items-center justify-center transition-colors duration-200" title="Share on LinkedIn">
                                    <i class="lab la-linkedin-in text-sm"></i>
                                </button>
                                
                                <!-- Email -->
                                <button onclick="shareViaEmail()" class="w-9 h-9 bg-gray-600 hover:bg-gray-700 dark:bg-gray-500 dark:hover:bg-gray-600 text-white rounded-lg flex items-center justify-center transition-colors duration-200" title="Share via Email">
                                    <i class="las la-envelope text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Description & Requirements Section with Sidebar -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Left Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Job Description -->
                    <div class="bg-white dark:bg-[#1a1a3a] p-6 sm:p-8 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                                <i class="las la-file-alt text-blue-600 dark:text-pink-300 text-xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Job Description</h3>
                        </div>
                        <div class="text-gray-700 dark:text-gray-300 leading-relaxed prose prose-gray dark:prose-invert max-w-none text-base">
                            {!! nl2br($job->description) !!}
                        </div>
                    </div>

                    @if($job->responsibilities)
                        <!-- Responsibilities -->
                        <div class="bg-white dark:bg-[#1a1a3a] p-6 sm:p-8 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                                    <i class="las la-tasks text-green-600 dark:text-green-400 text-xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Key Responsibilities</h3>
                            </div>
                            <ul class="space-y-3">
                                @foreach($job->responsibilities as $responsibility)
                                    <li class="flex items-start gap-3 text-gray-700 dark:text-gray-300">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                        <span>{{ $responsibility }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($job->qualifications)
                        <!-- Requirements -->
                        <div class="bg-white dark:bg-[#1a1a3a] p-6 sm:p-8 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                                    <i class="las la-check-circle text-purple-600 dark:text-purple-400 text-xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Requirements</h3>
                            </div>
                            <ul class="space-y-3">
                                @foreach($job->qualifications as $qualification)
                                    <li class="flex items-start gap-3 text-gray-700 dark:text-gray-300">
                                        <div class="w-2 h-2 bg-purple-500 rounded-full mt-2 flex-shrink-0"></div>
                                        <span>{{ $qualification }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Right Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Company Info -->
                    <div class="bg-white dark:bg-[#1a1a3a] p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm sticky top-6">
                        <div class="flex items-center gap-4 mb-6">
                            @if($job->employer_logo)
                            <img src="{{ $job->employer_logo }}" alt="{{ $job->employer_name }}" class="w-16 h-16 rounded-xl object-cover border-2 border-gray-100 dark:border-gray-700">
                            @else
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center text-white text-2xl font-bold">
                                {{ substr($job->employer_name, 0, 1) }}
                            </div>
                            @endif
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $job->employer_name }}</h3>
                                <p class="text-gray-600 dark:text-gray-400 font-medium">{{ $job->industry ?? 'Technology' }}</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            @if($job->country)
                                <div class="flex items-center gap-4 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                        <i class="las la-map-marker text-blue-600 dark:text-pink-300 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Location</p>
                                        <p class="text-gray-900 dark:text-white font-semibold flex items-center gap-2">
                                            {{ \App\Helpers\CountryFlag::getCountry($job->country) }}
                                            <span class="text-lg">{{ \App\Helpers\CountryFlag::getFlag($job->country) }}</span>
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center gap-4 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                    <i class="las la-clock text-green-600 dark:text-green-400 text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Job Type</p>
                                    <p class="text-gray-900 dark:text-white font-semibold">{{ $job->employment_type }}</p>
                                </div>
                            </div>

                            @if($job->required_experience)
                                <div class="flex items-center gap-4 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                        <i class="las la-briefcase text-purple-600 dark:text-purple-400 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Experience</p>
                                        <p class="text-gray-900 dark:text-white font-semibold">{{ number_format($job->required_experience / 12, 1) }}+ Years</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($job->benefits)
                            <div class="border-t border-gray-200 dark:border-gray-700 mt-6 pt-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                                        <i class="las la-gift text-emerald-600 dark:text-emerald-400"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Benefits</h3>
                                </div>
                                <ul class="space-y-2">
                                    @foreach($job->benefits as $benefit)
                                        <li class="flex items-start gap-3 text-gray-700 dark:text-gray-300 text-sm">
                                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full mt-2 flex-shrink-0"></div>
                                            <span>{{ $benefit }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                    </div>

                    <!-- Location Map -->
{{--                    @if($job->latitude && $job->longitude)--}}
{{--                    <div class="bg-white dark:bg-[#1a1a3a] p-6 rounded-2xl border border-gray-200 dark:border-gray-700">--}}
{{--                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">--}}
{{--                            <i class="las la-map text-blue-600 dark:text-pink-300"></i>--}}
{{--                            Job Location--}}
{{--                        </h3>--}}
{{--                        <div id="job-map" class="h-64 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700" --}}
{{--                             data-lat="{{ $job->latitude }}" --}}
{{--                             data-lng="{{ $job->longitude }}"--}}
{{--                             data-title="{{ $job->job_title }}"--}}
{{--                             data-company="{{ $job->employer_name }}"--}}
{{--                             data-location="{{ $job->state }}, {{ $job->country }}">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    @endif--}}


                </div>
            </div>

            <!-- Cover Letter Generator -->
            <livewire:cover-letter-chat :job="$job" />

            <!-- Apply Now Section Before Related Jobs -->
            <livewire:apply-job :job="$job" />

            <!-- Related Jobs Section -->
            @if($relatedJobs->count() > 0)
                <div class="mt-16">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 dark:from-pink-500 dark:to-purple-500 rounded-xl flex items-center justify-center">
                            <i class="las la-briefcase text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Related Jobs</h2>
                            <p class="text-gray-600 dark:text-gray-400">Discover similar opportunities that match your interests</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($relatedJobs as $relatedJob)
                            <div class="group bg-white dark:bg-[#1a1a3a] rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-blue-500/50 dark:hover:border-pink-500/50 hover:shadow-xl dark:hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                                <!-- Card Header with Company Logo and Job Type Badge -->
                                <div class="relative p-6 pb-4 bg-gradient-to-br from-gray-50 to-white dark:from-gray-800/50 dark:to-[#1a1a3a]">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 flex items-center justify-center overflow-hidden">
                                                @if($relatedJob->employer_logo)
                                                    <img src="{{ $relatedJob->employer_logo }}" alt="{{ $relatedJob->employer_name }}" class="w-8 h-8 object-contain">
                                                @else
                                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white text-sm font-bold">
                                                        {{ substr($relatedJob->employer_name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="text-gray-900 dark:text-white font-semibold text-sm line-clamp-1">{{ $relatedJob->employer_name }}</h3>
                                                <p class="text-gray-500 dark:text-gray-400 text-xs flex items-center gap-1">
                                                    <i class="las la-clock text-xs"></i>
                                                    {{ $relatedJob->posted_at?->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="px-2.5 py-1 text-xs bg-gradient-to-r from-blue-500/10 to-purple-500/10 dark:from-pink-500/10 dark:to-purple-500/10 text-blue-600 dark:text-pink-300 rounded-full font-medium border border-blue-500/20 dark:border-pink-500/20">
                                            {{ $relatedJob->employment_type }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="px-6 pb-6">
                                    <a href="{{ route('job.show', $relatedJob->slug) }}" class="block group-hover:text-blue-600 dark:group-hover:text-pink-300 transition-colors">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 line-clamp-2 leading-tight">
                                            {{ $relatedJob->job_title }}
                                        </h4>
                                    </a>

                                    <!-- Job Details -->
                                    <div class="space-y-3 mb-4">
                                        <!-- Location -->
                                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 text-sm">
                                            <div class="w-5 h-5 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class="las la-map-marker text-xs"></i>
                                            </div>
                                            <span class="flex items-center gap-1.5">
                                                {{ \App\Helpers\CountryFlag::getCountry($relatedJob->country) }}
                                                <span class="text-base">{{ \App\Helpers\CountryFlag::getFlag($relatedJob->country) }}</span>
                                            </span>
                                        </div>

                                        <!-- Remote/On-site -->
                                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 text-sm">
                                            <div class="w-5 h-5 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class="las {{ $relatedJob->is_remote ? 'la-laptop' : 'la-building' }} text-xs"></i>
                                            </div>
                                            <span>{{ $relatedJob->is_remote ? 'Remote Work' : 'On-site' }}</span>
                                        </div>

                                        <!-- Salary if available -->
                                        @if($relatedJob->min_salary && $relatedJob->max_salary)
                                            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 text-sm">
                                                <div class="w-5 h-5 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <i class="las la-dollar-sign text-xs"></i>
                                                </div>
                                                <span class="font-medium text-gray-900 dark:text-white">
                                                    ${{ number_format($relatedJob->min_salary/1000) }}k - ${{ number_format($relatedJob->max_salary/1000) }}k
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Action Button -->
                                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                                        <a href="{{ route('job.show', $relatedJob->slug) }}" class="w-full bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 hover:from-blue-50 hover:to-blue-100 dark:hover:from-pink-900/20 dark:hover:to-purple-900/20 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-pink-300 px-4 py-2.5 rounded-xl font-medium text-sm transition-all duration-300 flex items-center justify-center gap-2 border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-pink-500/30">
                                            <span>View Details</span>
                                            <i class="las la-arrow-right text-sm group-hover:translate-x-0.5 transition-transform"></i>
                                        </a>
                                    </div>
                                </div>

                                <!-- Hover Effect Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 dark:from-pink-500/5 dark:to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none rounded-2xl"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection



@push('extra-js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    

    
    // Share job functionality
    window.shareJob = function() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $job->job_title }} at {{ $job->employer_name }}',
                text: 'Check out this job opportunity!',
                url: window.location.href
            }).catch(console.error);
        } else {
            navigator.clipboard.writeText(window.location.href).then(() => {
                alert('Job link copied to clipboard!');
            }).catch(() => {
                alert('Could not copy link. Please copy manually: ' + window.location.href);
            });
        }
    };
    
    // Social Media Share Functions
    window.shareOnFacebook = function() {
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent('{{ $job->job_title }} at {{ $job->employer_name }} - Check out this job opportunity!');
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${text}`, '_blank', 'width=600,height=400');
    };
    
    window.shareOnTwitter = function() {
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent('{{ $job->job_title }} at {{ $job->employer_name }} - Check out this job opportunity!');
        window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400');
    };
    
    window.shareOnLinkedIn = function() {
        const url = encodeURIComponent(window.location.href);
        const title = encodeURIComponent('{{ $job->job_title }} at {{ $job->employer_name }}');
        const summary = encodeURIComponent('Check out this job opportunity!');
        window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}&title=${title}&summary=${summary}`, '_blank', 'width=600,height=400');
    };
    
    window.shareViaEmail = function() {
        const subject = encodeURIComponent('Job Opportunity: {{ $job->job_title }} at {{ $job->employer_name }}');
        const body = encodeURIComponent(`Hi there,\n\nI found this job opportunity that might interest you:\n\n{{ $job->job_title }} at {{ $job->employer_name }}\n\nCheck it out: ${window.location.href}\n\nBest regards`);
        window.location.href = `mailto:?subject=${subject}&body=${body}`;
    };
    


    // Map functionality
    const mapContainer = document.getElementById('job-map');

    if (mapContainer) {
        const lat = parseFloat(mapContainer.dataset.lat);
        const lng = parseFloat(mapContainer.dataset.lng);
        const title = mapContainer.dataset.title;
        const company = mapContainer.dataset.company;
        const location = mapContainer.dataset.location;

        if (lat && lng) {
            // Initialize the map
            const map = L.map('job-map', {
                zoomControl: false,
                scrollWheelZoom: false
            }).setView([lat, lng], 13);

            // Add zoom control in bottom right
            L.control.zoom({
                position: 'bottomright'
            }).addTo(map);

            // Add tile layer with dark theme
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 20
            }).addTo(map);

            // Create custom icon
            const customIcon = L.divIcon({
                html: '<div class="custom-marker"><i class="las la-map-marker-alt"></i></div>',
                className: 'custom-div-icon',
                iconSize: [40, 40],
                iconAnchor: [20, 40],
                popupAnchor: [0, -40]
            });

            // Add marker
            const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);

            // Add popup
            marker.bindPopup(`
                <div class="custom-popup">
                    <h4 class="font-semibold text-white mb-1">${title}</h4>
                    <p class="text-gray-300 text-sm mb-1">${company}</p>
                    <p class="text-gray-400 text-xs">${location}</p>
                </div>
            `);

            marker.openPopup();
        }
    }
});
</script>

<style>
.custom-div-icon {
    background: transparent;
    border: none;
}

.custom-marker {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50% 50% 50% 0;
    transform: rotate(-45deg);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.dark .custom-marker {
    background: linear-gradient(135deg, #ec4899, #8b5cf6);
    box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
}

.custom-marker i {
    transform: rotate(45deg);
    font-size: 20px;
}

.leaflet-popup-content-wrapper {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 12px !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
}

.dark .leaflet-popup-content-wrapper {
    background: #1a1a3a !important;
    border: 1px solid #374151 !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5) !important;
}

.leaflet-popup-content {
    margin: 16px !important;
}

.leaflet-popup-tip {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
}

.dark .leaflet-popup-tip {
    background: #1a1a3a !important;
    border: 1px solid #374151 !important;
}

.custom-popup h4 {
    color: #111827;
    font-weight: 600;
    margin-bottom: 4px;
}

.dark .custom-popup h4 {
    color: #ffffff;
}

.custom-popup p {
    margin: 0;
}

.leaflet-control-zoom {
    border: none !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
}

.dark .leaflet-control-zoom {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3) !important;
}

.leaflet-control-zoom a {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    color: #3b82f6 !important;
    font-weight: bold !important;
}

.dark .leaflet-control-zoom a {
    background: #1a1a3a !important;
    border: 1px solid #374151 !important;
    color: #ec4899 !important;
}

.leaflet-control-zoom a:hover {
    background: #f3f4f6 !important;
    color: #1d4ed8 !important;
}

.dark .leaflet-control-zoom a:hover {
    background: #374151 !important;
    color: #f472b6 !important;
}
</style>
@endpush
