@props(['job'])

<div class="relative group rounded-xl bg-white dark:bg-[#1a1a3a] border border-gray-200 dark:border-gray-800 hover:border-blue-500 dark:hover:border-pink-500 transition-colors duration-200">
    <div class="flex gap-6 p-5">
        <!-- Left Column -->
        <div class="flex gap-4 flex-1">
            <!-- Logo -->
            <div class="shrink-0">
                <img
                    src="{{ $job->employer_logo ?? 'https://placehold.co/100x100/2a2a4a/FFFFFF' }}"
                    alt="{{ $job->employer_name }}"
                    class="w-12 h-12 rounded-lg object-cover bg-gray-100 dark:bg-gray-800/50"
                    loading="lazy"
                >
            </div>

            <!-- Main Info -->
            <div class="min-w-0">
                <h3 class="text-gray-900 dark:text-white font-medium truncate hover:text-blue-600 dark:hover:text-pink-500 transition-colors">
                    {{ $job->job_title }}
                </h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $job->employer_name }}</p>

                <!-- Job Meta -->
                <div class="flex flex-wrap gap-4 mt-3 text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-1.5">
                        <i class="las la-map-marker"></i>
                        {{ $job->is_remote ? 'Remote' : $job->city }}
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i class="las la-clock"></i>
                        {{ $job->employment_type }}
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i class="las la-calendar"></i>
                        {{ $job->created_at->diffForHumans() }}
                    </div>
                </div>

                <!-- Tags -->
                <div class="flex gap-2 mt-3">
                    <span class="px-2.5 py-1 bg-blue-500/10 dark:bg-pink-500/10 text-blue-600 dark:text-pink-400 text-xs font-medium rounded-md">
                        {{ $job->category->name }}
                    </span>
                    @if($job->benefits)
                        <span class="px-2.5 py-1 bg-blue-600/10 dark:bg-purple-500/10 text-blue-700 dark:text-purple-400 text-xs font-medium rounded-md">
                            {{ count($job->benefits) }} Benefits
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="flex flex-col items-end justify-between gap-3">
            <!-- Salary -->
            @if ($job->min_salary && $job->max_salary)
                <div class="text-right">
                    <div class="text-blue-600 dark:text-pink-400 font-medium">
                        ${{ \App\Helpers\NumberFormatter::formatNumber($job->min_salary) }} -
                        ${{ \App\Helpers\NumberFormatter::formatNumber($job->max_salary) }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-500">per {{ $job->salary_period }}</div>
                </div>
            @endif

            <!-- Action Button -->
            <a href="{{ route('job.show', $job->slug) }}"
               class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-medium text-white
                      bg-gradient-to-r from-blue-600 to-blue-700 dark:from-pink-600 dark:to-purple-600 hover:from-blue-500 hover:to-blue-600 dark:hover:from-pink-500 dark:hover:to-purple-500
                      transition-all duration-200">
                View Role
                <i class="las la-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Benefits Tooltip -->
    @if($job->benefits)
        <div class="absolute top-full left-0 mt-2 w-64 p-4 bg-white dark:bg-[#1a1a3a] border border-gray-200 dark:border-gray-800 rounded-lg
                    opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-10">
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
