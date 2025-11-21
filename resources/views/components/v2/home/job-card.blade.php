@props(['job'])

<div class="relative group rounded-xl bg-white dark:bg-[#1a1a3a] border border-gray-200 dark:border-gray-800 hover:border-blue-500 dark:hover:border-pink-500 transition-all duration-200 hover:shadow-lg">
    <!-- Mobile Layout (Default) -->
    <div class="block lg:hidden p-4 md:p-5">
        <!-- Header Row: Logo, Title & Bookmark -->
        <div class="flex items-start gap-3 mb-3">
            <!-- Logo -->
            <div class="shrink-0">
                <img
                    src="{{ $job->employer_logo ?? 'https://placehold.co/100x100/2a2a4a/FFFFFF' }}"
                    alt="{{ $job->employer_name }}"
                    class="w-12 h-12 md:w-14 md:h-14 rounded-lg object-cover bg-gray-100 dark:bg-gray-800/50"
                    loading="lazy"
                >
            </div>

            <!-- Title & Company -->
            <div class="flex-1 min-w-0">
                <h3 class="text-gray-900 dark:text-white font-semibold text-lg md:text-xl leading-tight mb-1 hover:text-blue-600 dark:hover:text-pink-500 transition-colors">
                    {{ $job->job_title }}
                </h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm md:text-base">{{ $job->employer_name }}</p>
            </div>

            <!-- Bookmark Button -->
            <div class="shrink-0">
                <livewire:jobs.bookmark-job :jobId="$job->id" />
            </div>
        </div>

        <!-- Job Meta - Mobile Optimized -->
        <div class="flex flex-wrap gap-3 mb-3 text-sm text-gray-600 dark:text-gray-400">
            <div class="flex items-center gap-1.5">
                <i class="las la-map-marker text-blue-500 dark:text-pink-500"></i>
                <span class="font-medium">{{ $job->is_remote ? 'Remote' : $job->city }}</span>
            </div>
            <div class="flex items-center gap-1.5">
                <i class="las la-clock text-blue-500 dark:text-pink-500"></i>
                <span>{{ $job->employment_type }}</span>
            </div>
            <div class="flex items-center gap-1.5">
                <i class="las la-calendar text-blue-500 dark:text-pink-500"></i>
                <span>{{ $job->created_at->diffForHumans() }}</span>
            </div>
        </div>

        <!-- Salary & Tags Row -->
        <div class="flex items-center justify-between gap-3 mb-4">
            <!-- Tags -->
            <div class="flex gap-2 flex-wrap">
                <span class="px-2.5 py-1.5 bg-blue-500/10 dark:bg-pink-500/10 text-blue-600 dark:text-pink-400 text-xs font-medium rounded-lg">
                    {{ $job->category->name }}
                </span>
                @if($job->benefits && count($job->benefits) > 0)
                    <span class="px-2.5 py-1.5 bg-green-500/10 dark:bg-green-500/10 text-green-600 dark:text-green-400 text-xs font-medium rounded-lg">
                        {{ count($job->benefits) }} Benefits
                    </span>
                @endif
            </div>

            <!-- Salary -->
            @if ($job->min_salary && $job->max_salary)
                <div class="text-right shrink-0">
                    <div class="text-blue-600 dark:text-pink-400 font-bold text-sm md:text-base">
                        ${{ \App\Helpers\NumberFormatter::formatNumber($job->min_salary) }}+
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-500">{{ $job->salary_period }}</div>
                </div>
            @endif
        </div>

        <!-- Action Button - Full Width on Mobile -->
        <a href="{{ route('job.show', $job->slug) }}"
           class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg text-sm font-medium text-white
                  bg-gradient-to-r from-blue-600 to-blue-700 dark:from-pink-600 dark:to-purple-600 
                  hover:from-blue-500 hover:to-blue-600 dark:hover:from-pink-500 dark:hover:to-purple-500
                  active:scale-95 transition-all duration-200 touch-manipulation">
            View Job Details
            <i class="las la-arrow-right"></i>
        </a>
    </div>

    <!-- Desktop Layout Override -->
    <div class="hidden lg:block">
        <div class="p-6">
            <!-- Top Row: Title, Company, Bookmark -->
            <div class="flex items-start justify-between gap-4 mb-4">
                <div class="flex items-start gap-4 min-w-0 flex-1">
                    <!-- Logo -->
                    <div class="shrink-0">
                        <img
                            src="{{ $job->employer_logo ?? 'https://placehold.co/100x100/2a2a4a/FFFFFF' }}"
                            alt="{{ $job->employer_name }}"
                            class="w-14 h-14 rounded-xl object-cover bg-gray-100 dark:bg-gray-800/50"
                            loading="lazy"
                        >
                    </div>
                    
                    <!-- Title & Company -->
                    <div class="min-w-0 flex-1">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white leading-tight hover:text-blue-600 dark:hover:text-pink-500 transition-colors mb-1 overflow-hidden" 
                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;" 
                            title="{{ $job->job_title }}">
                            {{ $job->job_title }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 font-medium">{{ $job->employer_name }}</p>
                    </div>
                </div>
                
                <!-- Bookmark -->
                <div class="shrink-0">
                    <livewire:jobs.bookmark-job :jobId="$job->id" />
                </div>
            </div>

            <!-- Middle Row: Job Meta Information -->
            <div class="flex flex-wrap items-center gap-6 mb-4 text-sm text-gray-600 dark:text-gray-400">
                <div class="flex items-center gap-2">
                    <i class="las la-map-marker-alt text-blue-500 dark:text-pink-500 text-base"></i>
                    <span class="font-medium">{{ $job->is_remote ? 'Remote' : $job->city }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="las la-briefcase text-blue-500 dark:text-pink-500 text-base"></i>
                    <span>{{ $job->employment_type }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="las la-clock text-blue-500 dark:text-pink-500 text-base"></i>
                    <span>{{ $job->created_at->diffForHumans() }}</span>
                </div>
                @if ($job->min_salary && $job->max_salary)
                    <div class="flex items-center gap-2 ml-auto">
                        <i class="las la-dollar-sign text-green-500 text-base"></i>
                        <span class="font-semibold text-green-600 dark:text-green-400">
                            ${{ \App\Helpers\NumberFormatter::formatNumber($job->min_salary) }} - ${{ \App\Helpers\NumberFormatter::formatNumber($job->max_salary) }}
                        </span>
                        <span class="text-xs text-gray-500">/ {{ $job->salary_period }}</span>
                    </div>
                @endif
            </div>

            <!-- Bottom Row: Tags and Action Button -->
            <div class="flex items-center justify-between gap-4">
                <!-- Tags -->
                <div class="flex gap-2 flex-wrap">
                    <span class="px-3 py-1.5 bg-blue-500/10 dark:bg-pink-500/10 text-blue-600 dark:text-pink-400 text-sm font-medium rounded-full">
                        {{ $job->category->name }}
                    </span>
                    @if($job->benefits && count($job->benefits) > 0)
                        <span class="px-3 py-1.5 bg-green-500/10 dark:bg-green-500/10 text-green-600 dark:text-green-400 text-sm font-medium rounded-full">
                            {{ count($job->benefits) }} Benefits
                        </span>
                    @endif
                </div>

                <!-- Action Button -->
                <div class="shrink-0">
                    <a href="{{ route('job.show', $job->slug) }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold text-white
                              bg-gradient-to-r from-blue-600 to-blue-700 dark:from-pink-600 dark:to-purple-600 
                              hover:from-blue-500 hover:to-blue-600 dark:hover:from-pink-500 dark:hover:to-purple-500
                              hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0
                              transition-all duration-200 whitespace-nowrap">
                        View Details
                        <i class="las la-arrow-right text-base"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Benefits Tooltip - Desktop Only -->
    @if($job->benefits)
        <div class="hidden lg:block absolute top-full left-0 mt-2 w-64 p-4 bg-white dark:bg-[#1a1a3a] border border-gray-200 dark:border-gray-800 rounded-lg
                    opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-10 shadow-lg">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Benefits</h4>
            <ul class="space-y-2">
                @foreach ($job->benefits as $benefit)
                    <li class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <i class="las la-check text-green-500"></i>
                        {{ $benefit }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
