@extends('v2.layouts.app')
@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gray-50 dark:bg-[#12122b] py-20">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10 bg-gradient-to-b from-blue-500/10 dark:from-purple-500/10 to-transparent">
            <div class="absolute inset-0 bg-[url('/assets/grid-pattern.svg')] bg-center opacity-20"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Stats Banner -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-2 rounded-full bg-blue-500/10 dark:bg-pink-500/10 px-4 py-2 backdrop-blur-sm border border-blue-500/20 dark:border-pink-500/20">
                    <span class="flex h-2 w-2 rounded-full bg-blue-500 dark:bg-pink-500 animate-pulse"></span>
                    <span class="font-oxanium font-medium text-blue-700 dark:text-pink-300">{{ $availableJobs }}+ jobs available</span>
                </div>
            </div>

            <!-- Main Content -->
            <div class="mx-auto max-w-4xl text-center space-y-6">
                <h1 class="font-oxanium-bold text-4xl sm:text-5xl md:text-6xl leading-tight text-gray-900 dark:text-white">
                    Find Your Next <span class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-500 bg-clip-text text-transparent">Dream Job</span> in
                    <span class="relative inline-block">Tech
                        <div class="absolute -bottom-2 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-500 rounded-full"></div>
                    </span>
                </h1>

                <p class="font-ubuntu-light text-lg sm:text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    Join thousands of developers who have found their perfect roles through our platform.
                </p>
                <!-- Enhanced Search Box -->
                <div class="mt-8 w-full">
                    <form action="{{ route('job.index') }}" method="get" class="relative">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <!-- Main Search Input -->
                            <div class="flex-1 relative group">
                                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                                    <i class="las la-search text-gray-400 text-lg"></i>
                                </div>
                                <input
                                    name="search"
                                    type="text"
                                    placeholder="Search by job title, company, or keyword"
                                    class="w-full h-14 pl-12 pr-4 rounded-xl bg-white dark:bg-white/10 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-pink-500/50 focus:border-transparent transition-all backdrop-blur-sm"
                                    autocomplete="off"
                                >
                            </div>

                            <!-- Filters Button -->
                            <div class="flex gap-3">
                                <div class="relative">
                                    <button
                                        type="button"
                                        onclick="toggleFilters()"
                                        class="h-14 px-6 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 hover:bg-gray-50 dark:hover:bg-white/10 text-gray-900 dark:text-white transition-all flex items-center gap-2 backdrop-blur-sm"
                                    >
                                        <i class="las la-filter"></i>
                                        <span>Filters</span>
                                    </button>
                                </div>

                                <!-- Search Button -->
                                <button
                                    type="submit"
                                    class="h-14 px-8 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white font-medium transition-all hover:shadow-lg hover:shadow-blue-500/20 dark:hover:shadow-pink-500/20 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-pink-500/50 flex items-center gap-2"
                                >
                                    <span>Search</span>
                                    <i class="las la-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Expandable Filters -->
                        <div id="filters" class="hidden mt-3 p-4 rounded-xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 backdrop-blur-sm">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Location Filter -->
{{--                                <div class="relative">--}}
{{--                                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">--}}
{{--                                        <i class="las la-globe text-gray-400"></i>--}}
{{--                                    </div>--}}
{{--                                    <select--}}
{{--                                        name="country"--}}
{{--                                        class="w-full h-12 pl-12 pr-10 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500/50 focus:border-transparent transition-all appearance-none"--}}
{{--                                    >--}}
{{--                                        <option value="">Select Location</option>--}}
{{--                                        @foreach($countries as $code => $name)--}}
{{--                                            <option value="{{ $name->code }}" class="bg-[#1a1a3a] text-white">{{ $name->name }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">--}}
{{--                                        <i class="las la-angle-down text-gray-400"></i>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <!-- Work Type Filter -->
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                                        <i class="las la-building text-gray-400"></i>
                                    </div>
                                    <select
                                        name="is_remote"
                                        class="w-full h-12 pl-12 pr-10 rounded-lg bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-pink-500/50 focus:border-transparent transition-all appearance-none"
                                    >
                                        <option value="">Work Type</option>
                                        <option value="1" class="bg-white dark:bg-[#1a1a3a] text-gray-900 dark:text-white">Remote Only</option>
                                        <option value="0" class="bg-white dark:bg-[#1a1a3a] text-gray-900 dark:text-white">On-site Only</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                                        <i class="las la-angle-down text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Popular Searches -->
                    <div class="flex flex-wrap items-center gap-3 mt-6 text-sm text-gray-600 dark:text-gray-400 text-center mb-8">
                        <span class="font-medium">Popular:</span>
                        <a href="{{ route('job.index', ['search' => 'development']) }}"
                           class="px-3 py-1.5 rounded-full bg-white dark:bg-white/5 hover:bg-gray-50 dark:hover:bg-white/10 border border-gray-200 dark:border-white/10 transition-all">
                           Development
                        </a>
                        <a href="{{ route('job.index', ['search' => 'design']) }}"
                           class="px-3 py-1.5 rounded-full bg-white dark:bg-white/5 hover:bg-gray-50 dark:hover:bg-white/10 border border-gray-200 dark:border-white/10 transition-all">
                           Design
                        </a>
                        <a href="{{ route('job.index', ['remote' => '1']) }}"
                           class="px-3 py-1.5 rounded-full bg-white dark:bg-white/5 hover:bg-gray-50 dark:hover:bg-white/10 border border-gray-200 dark:border-white/10 transition-all">
                           Remote
                        </a>
                        <a href="{{ route('job.index', ['source' => 'LinkedIn']) }}"
                           class="px-3 py-1.5 rounded-full bg-white dark:bg-white/5 hover:bg-gray-50 dark:hover:bg-white/10 border border-gray-200 dark:border-white/10 transition-all">
                           LinkedIn
                        </a>
                        <a href="{{ route('job.index', ['category' => '1']) }}"
                           class="px-3 py-1.5 rounded-full bg-white dark:bg-white/5 hover:bg-gray-50 dark:hover:bg-white/10 border border-gray-200 dark:border-white/10 transition-all">
                           Laravel
                        </a>
                    </div>
                         <!-- Popular Jobs by Country -->
                    <div class="flex flex-wrap items-center gap-3 pt-4 text-sm text-gray-700 dark:text-gray-300">
                        <span class="font-medium">Top Countries:</span>
                        @foreach($topCountries as $country)
                            <a href="{{ route('job.index', ['country' => $country->code]) }}"
                               class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-white dark:bg-white/5 hover:bg-gray-50 dark:hover:bg-white/10 border border-gray-200 dark:border-white/10 transition-all group">
                                <span>{{ $country->getFlag() }} {{ $country->name }}</span>
                                <span class="text-blue-600 dark:text-pink-500 group-hover:text-blue-700 dark:group-hover:text-pink-400">{{ $country->jobs_count }}+</span>
                            </a>
                        @endforeach
                    </div>
                </div>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-3 gap-4 sm:gap-8 max-w-2xl mx-auto mt-16 text-center">
            <div class="space-y-2">
                <h3 class="font-oxanium-bold text-3xl sm:text-4xl text-gray-900 dark:text-white">{{$availableJobs}}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base">Active Jobs</p>
            </div>
            <div class="space-y-2">
                <h3 class="font-oxanium-bold text-3xl sm:text-4xl text-gray-900 dark:text-white">{{ $jobCategoriesCount }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base">Categories</p>
            </div>
            <div class="space-y-2">
                <h3 class="font-oxanium-bold text-3xl sm:text-4xl text-gray-900 dark:text-white">{{App\Models\User::count()}}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base">Developers</p>
            </div>
        </div>
    </div>
</section>

<!-- Add this script at the end of your blade file or in your JS bundle -->
<script>
function toggleFilters() {
    const filters = document.getElementById('filters');
    filters.classList.toggle('hidden');
}
</script>

    <!-- Latest jobs start -->
    @if ($latestJobs)
        <x-v2.home.latestJobs :latestJobs="$latestJobs"></x-v2.home.latestJobs>
    @endif
    <!-- Latest jobs end -->

    <section class="bg-gray-50 dark:bg-[#12122b] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-12 flex items-end justify-between">
                <div>
                    <h2 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">Most Viewed Jobs</h2>
                    <p class="text-gray-600 dark:text-gray-300">Discover the positions developers are exploring</p>
                </div>
            </div>
            @if($mostViewedJobs)
                <x-v2.home.most-viewed-jobs :mostViewedJobs="$mostViewedJobs"></x-v2.home.most-viewed-jobs>
            @endif
        </div>
    </section>

    <!-- Job Categories -->
    <section class="bg-white dark:bg-[#12122b] py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="mb-12 sm:mb-16">
                <div class="relative inline-block">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white tracking-tight">Browse by Category</h2>
                    <div class="absolute -bottom-2 left-0 w-1/2 h-px bg-gradient-to-r from-blue-500 dark:from-pink-500 to-transparent"></div>
                </div>
                <p class="mt-4 text-gray-600 dark:text-gray-300 text-lg">Find your perfect role in these specialized areas</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach ($jobCategories as $category)
                    <a href="{{ route('job.index', ['category' => $category->id]) }}"
                       class="group relative overflow-hidden bg-white dark:bg-[#1a1a3a]/50 backdrop-blur-sm rounded-2xl transition-all duration-300 hover:bg-gray-50 dark:hover:bg-[#1a1a3a] hover:scale-[1.02] border border-gray-200 dark:border-gray-700">
                        <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-blue-500/0 dark:from-pink-500/0 via-blue-500 dark:via-pink-500 to-blue-500/0 dark:to-pink-500/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                        <div class="p-6 sm:p-8">
                            <!-- Category Icon with Gradient Background -->
                            <div class="inline-flex items-center justify-center rounded-xl bg-gradient-to-br from-blue-500/10 dark:from-pink-500/10 to-blue-600/10 dark:to-purple-500/10 p-3 mb-6">
                                @if($category->category_image)
                                    <img src="{{ url($category->category_image) }}"
                                         alt="{{ $category->name }}"
                                         class="w-8 h-8 object-contain transform group-hover:scale-110 transition-transform duration-300"
                                         loading="lazy">
                                @else
                                    <i class="las la-briefcase text-2xl text-blue-600 dark:text-pink-300 transform group-hover:scale-110 transition-transform duration-300"></i>
                                @endif
                            </div>

                            <!-- Category Info with Improved Typography -->
                            <div class="space-y-3">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white tracking-tight group-hover:text-blue-600 dark:group-hover:text-pink-400 transition-colors">
                                    {{ ucwords($category->name) }}
                                </h3>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 dark:text-gray-400 font-mono text-sm">{{ $category->jobs_count }} positions</span>
                                    <span class="text-blue-600 dark:text-pink-400 opacity-0 transform translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300">→</span>
                                </div>
                            </div>
                        </div>

                        <!-- Minimal Progress Indicator -->
                        <div class="absolute bottom-0 left-0 w-full h-px bg-blue-500/10 dark:bg-pink-500/10">
                            <div class="h-full bg-gradient-to-r from-blue-500 dark:from-pink-500 to-blue-600 dark:to-purple-500 transition-all duration-500 group-hover:opacity-100 opacity-50"
                                 style="width: {{ min(100, max(10, $category->jobs_count)) }}%"></div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Minimalist View All Button -->
            <div class="mt-12 sm:mt-16 flex justify-center">
                <a href="{{ route('job.categories') }}"
                   class="group relative overflow-hidden px-8 py-4 rounded-xl bg-white dark:bg-[#1a1a3a]/50 backdrop-blur-sm border border-blue-500/20 dark:border-pink-500/20 text-gray-900 dark:text-white font-medium transition-all duration-300 hover:bg-gray-50 dark:hover:bg-[#1a1a3a] hover:border-blue-500/40 dark:hover:border-pink-500/40">
                    <span class="relative z-10 flex items-center gap-3">
                        <span>View All Categories</span>
                        <span class="transform group-hover:translate-x-1 transition-transform">→</span>
                    </span>
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-gray-50 dark:bg-[#0A0A1B] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
                <div class="rounded-2xl bg-white dark:bg-[#12122b] p-8 text-center">
                    <div class="font-oxanium-semibold text-4xl text-gray-900 dark:text-white">{{ $lastWeekAddedJobsCount }}</div>
                    <div class="text-gray-600 dark:text-gray-300">Job Added in Last Week</div>
                </div>
                <div class="rounded-2xl bg-white dark:bg-[#12122b] p-8 text-center">
                    <div class="font-oxanium-semibold text-4xl text-gray-900 dark:text-white">{{ $todayAddedJobsCount }}</div>
                    <div class="text-gray-600 dark:text-gray-300">Today Added Jobs</div>
                </div>
                <div class="rounded-2xl bg-white dark:bg-[#12122b] p-8 text-center">
                    <div class="font-oxanium-semibold text-4xl text-gray-900 dark:text-white">{{ $availableJobs }}</div>
                    <div class="text-gray-600 dark:text-gray-300">Available Jobs</div>
                </div>
                <div class="rounded-2xl bg-white dark:bg-[#12122b] p-8 text-center">
                    <div class="font-oxanium-semibold text-4xl text-gray-900 dark:text-white">{{ $jobCategoriesCount }}</div>
                    <div class="text-gray-600 dark:text-gray-300">Job Categories</div>
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


