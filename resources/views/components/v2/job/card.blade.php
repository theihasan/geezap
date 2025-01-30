@props(['job'])
<div class="bg-[#1a1a3a] rounded-2xl border border-gray-700 p-4 md:p-6 hover:border-pink-500/50 transition mb-6 font-ubuntu">
    <div class="flex flex-col md:flex-row md:justify-between md:items-start">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <a href="{{ route('job.show', $job->slug) }}" class="hidden md:block">
                @if ($job->employer_logo)
                    <img src="{{ $job->employer_logo }}" alt="{{ $job->employer_name }}"
                         class="w-12 h-12 rounded-xl object-contain bg-white/5 p-2">
                @else
                    <img src="https://placehold.co/32x32" alt="{{ $job->employer_name }}"
                         class="w-12 h-12 rounded-xl object-contain bg-white/5 p-2">
                @endif
            </a>
            <div>
                <a href="{{ route('job.show', $job->slug) }}">
                    <h3 class="text-lg md:text-xl font-oxanium-semibold text-white">{{ $job->job_title }}</h3>
                </a>
                <p class="text-gray-300 text-sm md:text-base">{{ $job->employer_name }} •
                    {{ $job->is_remote ? 'Remote' : $job->city }}</p>
                @if ($job->min_salary && $job->max_salary)
                    <div class="font-semibold text-pink-300">
                        $ {{ \App\Helpers\NumberFormatter::formatNumber($job->min_salary) }} -
                        $ {{ \App\Helpers\NumberFormatter::formatNumber($job->max_salary) }} /
                        {{ $job->salary_period }}
                    </div>
                @endif
            </div>
        </div>
        @if ($job->min_salary && $job->max_salary)
            <div class="text-pink-300 font-ubuntu-medium text-lg hidden md:block">
                ${{ number_format($job->min_salary) }} - ${{ number_format($job->max_salary) }}
            </div>
        @endif
    </div>

    <div class="text-gray-400 text-sm space-y-1 mt-4 font-ubuntu">
        <div class="flex items-center gap-2">
            <i class="las la-calendar-alt text-pink-300"></i>
            <span>Posted: <span class="text-white">{{ $job->posted_at?->diffForHumans() }}</span></span>
        </div>
        @if ($job->state && $job->country)
            <div class="flex items-center gap-2">
                <i class="las la-map-marker-alt text-pink-300"></i>
                <span>Location: <span class="text-white">{{ $job->state }}, {{ $job->country }}</span></span>
            </div>
        @endif
        <div class="flex items-center gap-2">
            <i class="las la-clock text-pink-300"></i>
            <span>Type: <span class="text-white">{{ $job->employment_type }}</span></span>
        </div>
    </div>

    <div class="flex flex-wrap gap-2 mt-4">
                                <span class="px-3 py-1 bg-pink-500/10 text-pink-300 rounded-full text-sm font-oxanium-semibold">
                                    {{ $job->category->name }}
                                </span>
        <span class="px-3 py-1 bg-pink-500/10 text-pink-300 rounded-full text-sm font-oxanium-semibold">
                                    {{ $job->employment_type }}
                                </span>
    </div>

    <div class="flex justify-end mt-6">
        <a href="{{ route('job.show', $job->slug) }}"
           class="w-full md:w-auto px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg hover:opacity-90 transition-opacity font-ubuntu-medium text-center">
            View Details
        </a>
    </div>
</div>
