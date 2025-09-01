<section class="bg-white dark:bg-[#12122b] py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="mb-8 sm:mb-12 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h2 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">Latest Jobs</h2>
                <p class="text-gray-600 dark:text-gray-300">Discover the latest job openings developers are exploring</p>
            </div>
        </div>

        <!-- Two Column Job Listings Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($latestJobs as $job)
                <div class="group relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700/50 bg-white dark:bg-gradient-to-br dark:from-[#1a1a3a] dark:to-[#12122b] p-6 transition-all duration-300 hover:border-blue-500/50 dark:hover:border-pink-500/50 hover:shadow-xl hover:shadow-blue-500/10 dark:hover:shadow-pink-500/10 hover:-translate-y-1 animate-fadeIn" style="animation-delay: {{ $loop->index * 100 }}ms">
                    <!-- Top Section: Company Info & Job Type -->
                    <div class="flex items-start justify-between gap-4">
                        <!-- Company Info -->
                        <div class="flex items-center gap-4">
                            <div class="relative group-hover:scale-110 transition-transform duration-300">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 dark:from-pink-500 to-blue-600 dark:to-purple-600 rounded-lg blur opacity-50 group-hover:opacity-100 transition-opacity"></div>
                                <div class="relative h-12 w-12 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700/50 bg-gray-50 dark:bg-[#12122b] flex items-center justify-center">
                                    @if($job->employer_logo)
                                        <img src="{{ $job->employer_logo }}" alt="{{ $job->employer_name }}" class="h-full w-full object-contain p-1">
                                    @else
                                        <i class="las la-building text-blue-600 dark:text-pink-500 text-2xl"></i>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-pink-400 transition-colors">{{ $job->employer_name }}</h3>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $job->category->name }}</span>
                            </div>
                        </div>
                        <!-- Job Type Badge -->
                        <span class="flex items-center gap-1.5 rounded-full bg-blue-500/10 dark:bg-pink-500/10 px-3 py-1.5 text-xs font-medium text-blue-600 dark:text-pink-400 border border-blue-500/20 dark:border-pink-500/20">
                            <i class="las la-clock text-base"></i>
                            {{ $job->employment_type }}
                        </span>
                    </div>

                    <!-- Job Title & Location -->
                    <div class="mt-6">
                        <a href="{{ route('job.show', $job->slug) }}" class="group/title">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white group-hover/title:text-blue-600 dark:group-hover/title:text-pink-400 transition-colors line-clamp-2">{{ $job->job_title }}</h3>
                        </a>
                        <div class="mt-3 flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                            @if($job->state && $job->country)
                                <div class="flex items-center gap-2">
                                    <i class="las la-map-marker text-lg text-blue-600/70 dark:text-pink-400/70"></i>
                                    {{ $job->state }}, {{ $job->country }}
                                </div>
                            @endif
                            <div class="flex items-center gap-2">
                                <i class="las la-calendar text-lg text-blue-600/70 dark:text-pink-400/70"></i>
                                {{ $job->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <!-- Tags & Salary -->
                    <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex gap-2 flex-wrap">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-500/10 dark:bg-pink-500/10 text-blue-600 dark:text-pink-400 text-sm font-medium rounded-lg border border-blue-500/20 dark:border-pink-500/20">
                                <i class="las la-code text-base"></i>
                                {{ $job->category->name }}
                            </span>
                            @if($job->benefits)
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-600/10 dark:bg-purple-500/10 text-blue-700 dark:text-purple-400 text-sm font-medium rounded-lg border border-blue-600/20 dark:border-purple-500/20">
                                    <i class="las la-gift text-base"></i>
                                    {{ count($job->benefits) }} Benefits
                                </span>
                            @endif
                        </div>
                        @if($job->min_salary && $job->max_salary)
                            <div class="text-gray-900 dark:text-white font-medium">
                                <span class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-500 bg-clip-text text-transparent">
                                    ${{ number_format($job->min_salary) }} - ${{ number_format($job->max_salary) }}
                                </span>
                                <span class="text-gray-600 dark:text-gray-400 text-sm">/ {{ $job->salary_period }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Apply Button -->
                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('job.show', $job->slug) }}"
                           class="group/btn relative inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 px-5 py-2.5 text-sm font-medium text-white transition-all overflow-hidden">
                            <span class="relative z-10 flex items-center gap-2 transition-transform group-hover/btn:translate-x-1">
                                View Details
                                <i class="las la-arrow-right text-lg"></i>
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-500 dark:from-purple-600 dark:to-pink-500 opacity-0 group-hover/btn:opacity-100 transition-opacity"></div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12 flex justify-center">
            <a href="{{ route('job.index') }}"
               class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 px-8 py-4 text-lg font-medium text-white transition-opacity hover:opacity-90">
                Explore All Job Opportunities
                <i class="las la-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
