@extends('v2.layouts.app')
@section('content')
    <!-- Hero Section -->
    <section class="relative bg-[#12122b] py-20">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10"
            style="background-image: url('https://placehold.co/1920x800/2a2a4a/FFFFFF'); background-size: cover;"></div>

        <div class="relative mx-auto max-w-7xl px-6">
            <div class="grid items-center gap-12 md:grid-cols-2">
                <!-- Left Content -->
                <div class="space-y-8">
                    <!-- Stats Banner -->
                    <div class="font-ubuntu-light inline-block rounded-full bg-pink-500/10 px-4 py-2 backdrop-blur-sm">
                        <span class="font-oxanium font-medium text-pink-300">🎯 Over {{ $availableJobs }}+ jobs
                            available</span>
                    </div>

                    <!-- Main Heading -->
                    <h1 class="font-oxanium-bold text-5xl leading-tight text-white md:text-6xl">
                        Find Your Next <span
                            class="bg-gradient-to-r from-pink-500 to-purple-500 bg-clip-text text-transparent">
                            Dream Job
                        </span> in Tech
                    </h1>

                    <!-- Subheading -->
                    <p class="font-ubuntu-light text-xl leading-relaxed text-gray-100">
                        Join thousands of developers who have found their perfect roles through our platform. We connect
                        talented developers with top tech companies.
                    </p>

                    <!-- Search Box -->
                    <div class="relative z-10 rounded-2xl border border-white/10 bg-white/10 backdrop-blur-md p-4 sm:p-5 shadow-xl">
                        <form action="{{ route('job.index') }}" method="get" class="relative">
                            <div class="flex flex-col md:flex-row gap-4">
                                <!-- Job Search Input -->
                                <div class="relative flex-1 group">
                                    <input
                                        name="search"
                                        type="text"
                                        placeholder="Job title, keyword, or company"
                                        class="w-full h-14 pl-12 pr-4 rounded-xl bg-white/10 border border-white/5 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500/50 focus:border-transparent transition-all"
                                        autocomplete="off"
                                    >
                                    <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-pink-500/5 to-purple-600/5 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                                </div>

                                <!-- Search Button -->
                                <button type="submit" class="h-14 px-8 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 text-white font-medium transition-all hover:shadow-lg hover:shadow-pink-500/20 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-pink-500/50 flex items-center justify-center gap-2">
                                    <span>Find Jobs</span>
                                    <i class="las la-search-location"></i>
                                </button>
                            </div>

                            <!-- Advanced Filters (Optional) -->
                            <div class="flex flex-wrap items-center gap-3 mt-4 text-sm text-gray-300">
                                <span class="font-medium">Popular:</span>
                                <a href="{{ route('job.index', ['search' => 'development']) }}" class="px-3 py-1 rounded-full bg-white/5 hover:bg-white/10 transition-colors">Development</a>
                                <a href="{{ route('job.index', ['search' => 'design']) }}" class="px-3 py-1 rounded-full bg-white/5 hover:bg-white/10 transition-colors">Design</a>
                                <a href="{{ route('job.index', ['remote' => '1']) }}" class="px-3 py-1 rounded-full bg-white/5 hover:bg-white/10 transition-colors">Remote</a>
                                <a href="{{ route('job.index', ['source' => 'LinkedIn']) }}" class="px-3 py-1 rounded-full bg-white/5 hover:bg-white/10 transition-colors">LinkedIn</a>
                                <a href="{{ route('job.index', ['category' => '1']) }}" class="px-3 py-1 rounded-full bg-white/5 hover:bg-white/10 transition-colors">Laravel</a>
                            </div>
                        </form>
                    </div>


                    <!-- Quick Stats -->
                    <div class="grid grid-cols-3 gap-6 pt-4">
                        <div class="text-center">
                            <div class="mb-1 text-3xl font-bold text-white">{{ $availableJobs }}+</div>
                            <div class="text-sm text-gray-300">Active Jobs</div>
                        </div>
                        <div class="text-center">
                            <div class="mb-1 text-3xl font-bold text-white">{{ $jobCategoriesCount }}+</div>
                            <div class="text-sm text-gray-300">Categories</div>
                        </div>
                        <div class="text-center">
                            <div class="mb-1 text-3xl font-bold text-white">{{ App\Models\User::query()->count() }}+</div>
                            <div class="text-sm text-gray-300">Developers</div>
                        </div>
                    </div>
                </div>

                <!-- Right Content -->
                <div class="relative hidden md:block">
                    <!-- Main Image -->
                    <img src="https://placehold.co/600x500/2a2a4a/FFFFFF" alt="Developer Working"
                        class="rounded-2xl shadow-2xl" loading="lazy">

                    <!-- Floating Card 1 -->
                    <div
                        class="absolute -left-6 -top-6 rounded-xl border border-white/10 bg-[#1a1a3a]/90 p-4 backdrop-blur-sm">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-pink-500/20">
                                <i class="las la-code text-pink-500"></i>
                            </div>
                            <div>
                                <div class="font-medium text-white">1,200+</div>
                                <div class="text-sm text-gray-400">Developer Jobs</div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Card 2 -->
                    <div
                        class="absolute -bottom-6 -right-6 rounded-xl border border-white/10 bg-[#1a1a3a]/90 p-4 backdrop-blur-sm">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-purple-500/20">
                                <i class="las la-building text-purple-500"></i>
                            </div>
                            <div>
                                <div class="font-medium text-white">500+</div>
                                <div class="text-sm text-gray-400">Tech Companies</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Latest jobs start -->
    @if ($latestJobs)
        <x-v2.home.latestJobs :latestJobs="$latestJobs"></x-v2.home.latestJobs>
    @endif
    <!-- Latest jobs end -->

    <section class="bg-[#12122b] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-12 flex items-end justify-between">
                <div>
                    <h2 class="mb-2 text-3xl font-bold text-white">Most Viewed Jobs</h2>
                    <p class="text-gray-300">Discover the positions developers are exploring</p>
                </div>
            </div>
            @if($mostViewedJobs)
                <x-v2.home.most-viewed-jobs :mostViewedJobs="$mostViewedJobs"></x-v2.home.most-viewed-jobs>
            @endif
        </div>
    </section>

    <!-- Job Categories -->
    <section class="bg-[#12122b] py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="mb-8 sm:mb-12 text-center">
                <h2 class="mb-2 text-3xl font-bold text-white">Browse by Category</h2>
                <p class="text-gray-300">Find your perfect role in these specialized areas</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach ($jobCategories as $category)
                    <div class="group relative overflow-hidden rounded-xl border border-gray-700 bg-[#1a1a3a] transition-all duration-300 hover:border-pink-500/50 hover:shadow-lg hover:shadow-pink-500/10 animate-fadeIn" style="animation-delay: {{ $loop->index * 100 }}ms">
                        <!-- Category Card Content -->
                        <div class="p-5 flex items-center gap-4">
                            <!-- Category Icon -->
                            <div class="flex-shrink-0 h-14 w-14 flex items-center justify-center rounded-xl bg-pink-500/10 group-hover:bg-pink-500/20 transition-colors">
                                @if($category->category_image)
                                    <img src="{{ url($category->category_image) }}"
                                         alt="{{ $category->name }}" class="w-8 h-8 object-contain" loading="lazy">
                                @else
                                    <i class="las la-briefcase text-2xl text-pink-300"></i>
                                @endif
                            </div>

                            <!-- Category Info -->
                            <div class="flex-1">
                                <a href="{{ route('job.index', ["category" => $category->id]) }}">
                                    <h3 class="text-lg font-semibold text-white group-hover:text-pink-400 transition-colors">{{ ucwords($category->name) }}</h3>
                                </a>
                                <p class="text-gray-400 text-sm mt-1">{{ $category->jobs_count }} open positions</p>
                            </div>

                            <!-- Arrow Icon -->
                            <div class="flex-shrink-0">
                                <a href="{{ route('job.index', ["category" => $category->id]) }}" class="w-8 h-8 flex items-center justify-center rounded-full bg-[#12122b] group-hover:bg-pink-500/20 transition-colors">
                                    <i class="las la-arrow-right text-pink-400 group-hover:text-pink-300 transition-colors"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Bottom Progress Bar (visual element) -->
                        <div class="h-1 w-full bg-[#12122b]">
                            <div class="h-full bg-gradient-to-r from-pink-500 to-purple-600" style="width: {{ min(100, max(10, $category->jobs_count)) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-10 sm:mt-12 flex justify-center">
                <a href="{{ route('job.categories') }}"
                   class="group flex items-center gap-2 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 px-6 py-3 sm:px-8 sm:py-4 text-base sm:text-lg font-medium text-white transition-all hover:opacity-90 hover:scale-105 transform">
                    See All Categories
                    <i class="las la-arrow-right transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-[#0A0A1B] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
                <div class="rounded-2xl bg-[#12122b] p-8 text-center">
                    <div class="font-oxanium-semibold text-4xl text-white">{{ $lastWeekAddedJobsCount }}</div>
                    <div class="text-gray-300">Job Added in Last Week</div>
                </div>
                <div class="rounded-2xl bg-[#12122b] p-8 text-center">
                    <div class="font-oxanium-semibold text-4xl text-white">{{ $todayAddedJobsCount }}</div>
                    <div class="text-gray-300">Today Added Jobs</div>
                </div>
                <div class="rounded-2xl bg-[#12122b] p-8 text-center">
                    <div class="font-oxanium-semibold text-4xl text-white">{{ $availableJobs }}</div>
                    <div class="text-gray-300">Available Jobs</div>
                </div>
                <div class="rounded-2xl bg-[#12122b] p-8 text-center">
                    <div class="font-oxanium-semibold text-4xl text-white">{{ $jobCategoriesCount }}</div>
                    <div class="text-gray-300">Job Categories</div>
                </div>
                <!-- More stat cards... -->
            </div>
        </div>
    </section>
@endsection
@push('extra-css')
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out forwards;
            opacity: 0;
        }
    </style>
@endpush
