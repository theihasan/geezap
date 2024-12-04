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
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-3 backdrop-blur-md">
                        <form action="{{ route('job.index') }}" method="get">
                            <div class="flex flex-col gap-3 md:flex-row">
                                <!-- Job Search -->
                                <div class="flex flex-1 items-center gap-3 rounded-xl bg-white/10 px-4 py-3">
                                    <i class="las la-search text-pink-500"></i>
                                    <input name="search" type="text" placeholder="Job title or keyword"
                                        class="w-full bg-transparent text-white placeholder-gray-400 focus:outline-none">
                                </div>
                                <!-- Search Button -->
                                <button
                                    class="font-ubuntu-regular w-full rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 px-8 py-3 font-medium text-white transition-opacity hover:opacity-90 md:w-auto">
                                    Search Jobs
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Popular Searches -->
                    <div class="space-y-3">
                        <h3 class="font-medium text-gray-100">Popular Searches:</h3>
                        <div class="flex flex-wrap gap-3">
                            <span
                                class="cursor-pointer rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-gray-100 transition-colors hover:border-pink-500">
                                Remote Jobs
                            </span>
                            <span
                                class="cursor-pointer rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-gray-100 transition-colors hover:border-pink-500">
                                Full Stack Developer
                            </span>
                            <span
                                class="cursor-pointer rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-gray-100 transition-colors hover:border-pink-500">
                                Frontend Engineer
                            </span>
                            <span
                                class="cursor-pointer rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-gray-100 transition-colors hover:border-pink-500">
                                React Developer
                            </span>
                        </div>
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
                        class="rounded-2xl shadow-2xl">

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
                <a href="{{ route('job.index') }}"
                    class="font-ubuntu-regular flex items-center gap-2 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 px-6 py-3 font-medium text-white transition-opacity hover:opacity-90">
                    See All Jobs
                    <i class="las la-arrow-right"></i>
                </a>
            </div>

            <!-- Enhanced Job Listings -->
            <div class="space-y-6">
                @foreach ($mostViewedJobs as $job)
                    <div
                        class="group relative rounded-2xl border border-gray-700 bg-[#1a1a3a] p-6 transition hover:border-pink-500/50">
                        <div class="flex flex-col gap-6 md:flex-row">
                            <!-- Left Side: Company Logo -->
                            <div class="relative md:w-48">
                                <a href="{{ route('job.show', $job->slug) }}">
                                    <img src="{{ $job->employer_logo ?? 'https://placehold.co/400x200/2a2a4a/FFFFFF' }}"
                                        alt="{{ $job->employer_name }}"
                                        class="h-32 w-full rounded-xl object-cover md:h-full">
                                </a>
                                <div
                                    class="absolute right-3 top-3 rounded-full bg-pink-500/90 px-3 py-1 text-sm text-white backdrop-blur-sm">
                                    {{ $job->views }} views
                                </div>
                            </div>

                            <!-- Right Side: Job Details -->
                            <div class="flex-1">
                                <!-- Top Section -->
                                <div class="mb-4 flex flex-col justify-between gap-4 md:flex-row">
                                    <div>
                                        <a href="{{ route('job.show', $job->slug) }}"
                                            class="font-medium text-white transition-colors hover:text-pink-500">
                                            <h3 class="text-xl font-semibold text-white">{{ $job->job_title }}</h3>
                                        </a>
                                        <p class="text-gray-300">{{ $job->employer_name }}</p>
                                    </div>
                                    @if ($job->min_salary && $job->max_salary)
                                        <div class="font-semibold text-pink-300">
                                            ${{ $job->min_salary }} - ${{ $job->max_salary }} / {{ $job->salary_period }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Job Details Grid -->
                                <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-4">
                                    <!-- Location -->
                                    <div class="flex items-center gap-2">
                                        <i class="las la-map-marker text-pink-500"></i>
                                        <span class="text-gray-300">{{ $job->is_remote ? 'Remote' : $job->city }}</span>
                                    </div>
                                    <!-- Posted Date -->
                                    <div class="flex items-center gap-2">
                                        <i class="las la-calendar text-pink-500"></i>
                                        <span class="text-gray-300">Posted {{ $job->created_at->diffForHumans() }}</span>
                                    </div>
                                    <!-- Experience -->
                                    <div class="flex items-center gap-2">
                                        <i class="las la-briefcase text-pink-500"></i>
                                        <span class="text-gray-300">{{ $job->experience_level ?? 'Not specified' }}</span>
                                    </div>
                                    <!-- Employment Type -->
                                    <div class="flex items-center gap-2">
                                        <i class="las la-clock text-pink-500"></i>
                                        <span class="text-gray-300">{{ $job->employment_type }}</span>
                                    </div>
                                </div>

                                {{--                                <!-- Tags --> --}}
                                {{--                                <div class="flex flex-wrap gap-2 mb-4"> --}}
                                {{--                                    <span class="px-3 py-1 text-sm text-pink-300 rounded-full bg-pink-500/10">{{ $job->job_category }}</span> --}}
                                {{--                                    @if ($job->qualifications) --}}
                                {{--                                        @foreach ($job->qualifications as $qualifications) --}}
                                {{--                                            <span class="px-3 py-1 text-sm text-pink-300 rounded-full bg-pink-500/10">{{ trim($qualifications) }}</span> --}}
                                {{--                                        @endforeach --}}
                                {{--                                    @endif --}}
                                {{--                                </div> --}}

                                <!-- Job Description Preview -->
                                @if ($job->description)
                                    <p class="mb-4 line-clamp-2 text-gray-300">
                                        {{ Str::limit(strip_tags($job->description), 300) }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Fixed Apply Button (visible on hover) -->
                        <div
                            class="absolute bottom-6 right-6 opacity-0 transition-all duration-300 group-hover:opacity-100">
                            <a href="{{ route('job.show', $job->slug) }}"
                                class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 px-6 py-3 text-white transition-opacity hover:opacity-90">
                                <span>Apply Now</span>
                                <i class="las la-arrow-right"></i>
                            </a>
                        </div>

                        <!-- Company Details Tooltip (visible on hover) -->
                        <div
                            class="pointer-events-none absolute bottom-0 left-6 z-10 w-72 translate-y-full transform rounded-xl border border-pink-500/50 bg-[#1a1a3a] p-4 opacity-0 shadow-xl transition-all duration-300 group-hover:opacity-100">
                            <div class="space-y-2 text-sm">
                                <div class="font-semibold text-white">{{ $job->employer_name }}</div>
                                <div class="text-gray-300">{{ $job->industry ?? 'Technology' }}</div>
                                <div class="text-gray-300">{{ $job->company_size ?? 'Company size not specified' }}</div>
                                @if ($job->benefits)
                                    @foreach ($job->benefits as $benefit)
                                        <div class="text-gray-300">{{ $benefit }}</div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                <a href="{{ route('job.index') }}"
                    class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 px-8 py-4 text-lg font-medium text-white transition-opacity hover:opacity-90">
                    Explore All Job Opportunities
                    <i class="las la-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Job Categories -->
    <section class="bg-[#12122b] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-12 text-center">
                <h2 class="mb-2 text-3xl font-bold text-white">Browse by Category</h2>
                <p class="text-gray-300">Find your perfect role in these specialized areas</p>
            </div>

            <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
                @foreach ($jobCategories as $category)
                    <div
                        class="group rounded-2xl border border-gray-700 bg-[#1a1a3a] p-6 transition hover:border-pink-500/50 hover:bg-[#222250]">
                        <div
                            class="mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-pink-500/10 group-hover:bg-pink-500/20">
                            <a href={{ url('jobs?category=' . $category->id) }}>
                                <img src="{{ url($category->category_image) ?? 'https://placehold.co/50x50/2a2a4a/FFFFFF' }}"
                                    class="h-8 w-8" alt="{{ $category->job_category }}">
                            </a>
                        </div>
                        <a href="{{ url('jobs?category=' . $category->id) }}"">
                            <h3 class="mb-2 text-xl font-semibold text-white">{{ ucwords($category->name) }}</h3>
                        </a>
                        <p class="mb-4 text-gray-300">{{ $category->jobs_count }} open positions</p>
                        <a href="{{ url('jobs?category=' . $category->id) }}"
                            class="flex items-center text-pink-300 transition hover:text-pink-400">
                            Browse Jobs <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                <a href="{{ route('job.categories') }}"
                    class="font-ubuntu-regular flex items-center gap-2 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 px-8 py-4 text-lg font-medium text-white transition-opacity hover:opacity-90">
                    See All Categories
                    <i class="las la-arrow-right"></i>
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
