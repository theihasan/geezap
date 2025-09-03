<section class="bg-white dark:bg-[#12122b] py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="mb-8 sm:mb-12 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h2 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">Latest Jobs</h2>
                <p class="text-gray-600 dark:text-gray-300">Discover the latest job openings developers are exploring</p>
            </div>
        </div>

        <!-- Compact Two Column Job Listings Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($latestJobs as $job)
                <a href="{{ route('job.show', $job->slug) }}" class="group relative overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700/50 bg-white dark:bg-[#1a1a3a] p-4 transition-all duration-300 hover:border-blue-500/50 dark:hover:border-pink-500/50 hover:shadow-md animate-fadeIn block" style="animation-delay: {{ $loop->index * 100 }}ms">
                    <!-- Compact Header: Company Info & Job Type -->
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <!-- Company Info -->
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div class="h-8 w-8 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700/50 bg-gray-50 dark:bg-[#12122b] flex items-center justify-center flex-shrink-0">
                                    @if($job->employer_logo)
                                        <img src="{{ $job->employer_logo }}" alt="{{ $job->employer_name }}" class="h-full w-full object-contain p-1">
                                    @else
                                        <i class="las la-building text-blue-600 dark:text-pink-500 text-lg"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-medium text-gray-900 dark:text-white text-sm truncate">{{ $job->employer_name }}</h3>
                                <span class="text-xs text-gray-600 dark:text-gray-400">{{ $job->category->name }}</span>
                            </div>
                        </div>
                        <!-- Job Type Badge -->
                        <span class="flex items-center gap-1 rounded-full bg-blue-500/10 dark:bg-pink-500/10 px-2 py-0.5 text-xs font-medium text-blue-600 dark:text-pink-400 border border-blue-500/20 dark:border-pink-500/20 flex-shrink-0">
                            <i class="las la-clock text-xs"></i>
                            {{ $job->employment_type }}
                        </span>
                    </div>

                    <!-- Job Title -->
                    <div class="mb-3">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-pink-400 transition-colors line-clamp-2 leading-tight">{{ $job->job_title }}</h3>
                    </div>

                    <!-- Compact Details Row -->
                    <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400 mb-3">
                        <div class="flex items-center gap-3">
                            @if($job->state && $job->country)
                                <div class="flex items-center gap-1">
                                    <i class="las la-map-marker text-blue-600/70 dark:text-pink-400/70"></i>
                                    <span>{{ $job->state }}, {{ $job->country }}</span>
                                </div>
                            @endif
                            <div class="flex items-center gap-1">
                                <i class="las la-calendar text-blue-600/70 dark:text-pink-400/70"></i>
                                <span>{{ $job->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        
                        @if($job->benefits)
                            <span class="px-2 py-0.5 bg-blue-600/10 dark:bg-purple-500/10 text-blue-700 dark:text-purple-400 text-xs font-medium rounded border border-blue-600/20 dark:border-purple-500/20">
                                {{ count($job->benefits) }} Benefits
                            </span>
                        @endif
                    </div>

                    <!-- Salary & Action -->
                    <div class="flex items-center justify-between">
                        @if($job->min_salary && $job->max_salary)
                            <div class="text-gray-900 dark:text-white font-medium text-sm">
                                <span class="text-blue-600 dark:text-pink-400">
                                    ${{ number_format($job->min_salary) }} - ${{ number_format($job->max_salary) }}
                                </span>
                                <span class="text-gray-600 dark:text-gray-400 text-xs">/ {{ $job->salary_period }}</span>
                            </div>
                        @endif

                        <div class="inline-flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-pink-400 transition-colors">
                            View Details →
                        </div>
                    </div>
                </a>
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
