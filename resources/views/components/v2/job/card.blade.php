@props(['job'])

<a href="{{ route('job.show', $job->slug) }}" class="block">
    <div class="relative bg-white dark:bg-[#1a1a3a] rounded-lg border border-gray-200 dark:border-gray-700 p-3 hover:border-blue-500/50 dark:hover:border-pink-500/50 transition-all hover:shadow-md mb-3">
        {{-- Compact Header: Logo, Title, Company, Employment Type --}}
        <div class="flex items-start gap-3">
            @if ($job->employer_logo)
                <img src="{{ $job->employer_logo }}" alt="{{ $job->employer_name }}" class="w-8 h-8 rounded-lg object-contain bg-gray-100 dark:bg-white/5 p-1 flex-shrink-0">
            @else
                <img src="https://placehold.co/32x32" alt="{{ $job->employer_name }}" class="w-8 h-8 rounded-lg object-contain bg-gray-100 dark:bg-white/5 p-1 flex-shrink-0">
            @endif

            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-gray-900 dark:text-white font-semibold text-sm leading-tight hover:text-blue-600 dark:hover:text-pink-300 truncate">{{ $job->job_title }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-xs mt-0.5">{{ $job->employer_name }}</p>
                    </div>
                    <span class="px-2 py-0.5 bg-blue-500/10 dark:bg-pink-500/10 text-blue-600 dark:text-pink-300 rounded-full text-xs font-medium flex-shrink-0">
                        {{ $job->employment_type }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Compact Job Details Row --}}
        <div class="flex items-center justify-between mt-2 text-xs text-gray-600 dark:text-gray-400">
            <div class="flex items-center gap-4">
                @if ($job->min_salary && $job->max_salary)
                    <div class="flex items-center gap-1">
                        <i class="las la-dollar-sign text-blue-600 dark:text-pink-300"></i>
                        <span class="text-blue-600 dark:text-pink-300 font-medium">
                            ${{ \App\Helpers\NumberFormatter::formatNumber($job->min_salary) }} - ${{ \App\Helpers\NumberFormatter::formatNumber($job->max_salary) }}
                        </span>
                    </div>
                @endif
                
                <div class="flex items-center gap-1">
                    <i class="las la-map-marker-alt text-blue-600 dark:text-pink-300"></i>
                    <span>{{ $job->is_remote ? 'Remote' : ($job->city ?: 'Location not specified') }}</span>
                </div>
                
                <div class="flex items-center gap-1">
                    <i class="las la-calendar-alt text-blue-600 dark:text-pink-300"></i>
                    <span>{{ $job->posted_at?->diffForHumans() }}</span>
                </div>
            </div>
            
            <span class="px-2 py-1 bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 rounded text-xs">
                {{ $job->category->name }}
            </span>
        </div>

        {{-- View Details Button (Compact) --}}
        <div class="flex justify-end mt-2">
            <span class="px-3 py-1.5 bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white rounded text-xs hover:opacity-90 transition">
                View Details →
            </span>
        </div>
    </div>
</a>
