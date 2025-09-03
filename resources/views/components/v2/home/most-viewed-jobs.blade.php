@props(['mostViewedJobs'])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach ($mostViewedJobs as $job)
        <a href="{{ route('job.show', $job->slug) }}" class="group relative bg-white dark:bg-[#1a1a3a] rounded-lg overflow-hidden transition-all duration-300 hover:bg-gray-50 dark:hover:bg-[#1e1e4a] border border-gray-200 dark:border-gray-700 block"
           style="animation-delay: {{ $loop->index * 100 }}ms">
            <!-- Accent Line -->
            <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-blue-500 dark:from-pink-500 to-blue-600 dark:to-purple-600 transform origin-top scale-y-0 group-hover:scale-y-100 transition-transform duration-500"></div>

            <div class="p-4">
                <!-- Views Counter -->
                <div class="absolute top-4 right-4">
                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-500/10 dark:bg-pink-500/10 rounded-full text-xs text-gray-600 dark:text-gray-400 border border-blue-500/20 dark:border-pink-500/20 min-w-fit">
                        <i class="las la-eye text-blue-600 dark:text-pink-500 text-xs"></i>
                        <span class="font-mono text-xs">{{ \App\Helpers\NumberFormatter::formatNumber($job->views) }}</span>
                    </div>
                </div>

                <!-- Compact Header: Company Info & Job Type -->
                <div class="flex items-start justify-between gap-3 mb-3">
                    <!-- Company Info -->
                    <div class="flex items-center gap-3">
                        <div class="relative w-8 h-8 rounded-lg bg-gray-100 dark:bg-[#12122b] flex items-center justify-center overflow-hidden flex-shrink-0">
                            @if($job->employer_logo)
                                <img src="{{ $job->employer_logo }}" alt="{{ $job->employer_name }}" class="w-full h-full object-contain p-1">
                            @else
                                <i class="las la-building text-blue-600 dark:text-pink-500 text-lg"></i>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-gray-900 dark:text-white font-medium text-sm truncate">{{ $job->employer_name }}</h4>
                            <span class="text-xs text-gray-600 dark:text-gray-400">{{ $job->category?->name }}</span>
                        </div>
                    </div>
                    
                    <!-- Employment Type Badge -->
                    {{-- <span class="flex items-center gap-1 rounded-full bg-blue-500/10 dark:bg-pink-500/10 px-2 py-0.5 text-xs font-medium text-blue-600 dark:text-pink-400 border border-blue-500/20 dark:border-pink-500/20 flex-shrink-0">
                        <i class="las la-clock text-xs"></i>
                        {{ $job->employment_type }}
                    </span> --}}
                </div>

                <!-- Job Title -->
                <div class="mb-3">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-pink-400 transition-colors line-clamp-2 leading-tight">{{ $job->job_title }}</h3>
                </div>

                <!-- Compact Details Row -->
                <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400 mb-3">
                    <div class="flex items-center gap-3">
                        @if($job->city || $job->state)
                            <div class="flex items-center gap-1">
                                <i class="las la-map-marker text-blue-600/70 dark:text-pink-500/70"></i>
                                <span>{{ $job->city ?? $job->state }}</span>
                            </div>
                        @endif

                        <div class="flex items-center gap-1">
                            <i class="las la-calendar text-blue-600/70 dark:text-green-500/70"></i>
                            <span>{{ $job->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Salary -->
                <div class="flex items-center justify-between">
                    @if ($job->min_salary && $job->max_salary)
                        <div class="text-gray-900 dark:text-white font-medium text-sm">
                            <span class="text-blue-600 dark:text-pink-400">
                                ${{ \App\Helpers\NumberFormatter::formatNumber($job->min_salary) }} - ${{ \App\Helpers\NumberFormatter::formatNumber($job->max_salary) }}
                            </span>
                            <span class="text-gray-600 dark:text-gray-400 text-xs">/ {{ $job->salary_period }}</span>
                        </div>
                    @endif

                    <div class="inline-flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-pink-400 transition-colors">
                        View Details →
                    </div>
                </div>
            </div>
        </a>
    @endforeach
</div>

<!-- Minimal Explore Button -->
<div class="mt-12 text-center">
    <a href="{{ route('job.index') }}"
       class="inline-flex items-center gap-3 px-8 py-3 rounded-xl text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-pink-400 transition-colors">
        <span class="text-lg">View All Jobs</span>
        <i class="las la-arrow-right"></i>
    </a>
</div>
