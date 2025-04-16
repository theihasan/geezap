@props(['job'])

<a href="{{ route('job.show', $job->slug) }}" class="block">
    <div class="relative bg-[#1a1a3a] rounded-lg border border-gray-700 p-4 hover:border-pink-500/50 transition hover:scale-105 mb-4">
        {{-- Header: Logo, Title, Company --}}
        <div class="flex gap-3">
            @if ($job->employer_logo)
                <img src="{{ $job->employer_logo }}" alt="{{ $job->employer_name }}" class="w-10 h-10 rounded-lg object-contain bg-white/5 p-1">
            @else
                <img src="https://placehold.co/32x32" alt="{{ $job->employer_name }}" class="w-10 h-10 rounded-lg object-contain bg-white/5 p-1">
            @endif

            <div class="flex-1">
                <h3 class="text-white font-semibold hover:text-pink-300">{{ $job->job_title }}</h3>
                <p class="text-gray-400 text-sm">{{ $job->employer_name }} • {{ $job->is_remote ? 'Remote' : $job->city }}</p>
            </div>
        </div>

        {{-- Job Details Grid --}}
        <div class="grid grid-cols-2 gap-2 mt-3 text-sm">
            @if ($job->min_salary && $job->max_salary)
                <div class="text-pink-300 font-medium">
                    ${{ \App\Helpers\NumberFormatter::formatNumber($job->min_salary) }} -
                    ${{ \App\Helpers\NumberFormatter::formatNumber($job->max_salary) }} / {{ $job->salary_period }}
                </div>
            @endif

            <div class="text-gray-400">
                <i class="las la-calendar-alt text-pink-300"></i>
                {{ $job->posted_at?->diffForHumans() }}
            </div>

            @if ($job->state && $job->country)
                <div class="text-gray-400">
                    <i class="las la-map-marker-alt text-pink-300"></i>
                    {{ $job->state }}, {{ $job->country }}
                </div>
            @endif

            <div class="text-gray-400">
                <i class="las la-clock text-pink-300"></i>
                {{ $job->employment_type }}
            </div>
        </div>

        {{-- Tags --}}
        <div class="flex flex-wrap gap-2 mt-3">
            <span class="px-2 py-1 bg-pink-500/10 text-pink-300 rounded-full text-xs">
                {{ $job->category->name }}
            </span>
            <span class="px-2 py-1 bg-pink-500/10 text-pink-300 rounded-full text-xs">
                {{ $job->employment_type }}
            </span>
        </div>

        {{-- View Details Button (Moved to bottom) --}}
        <div class="flex justify-end mt-4">
            <span class="px-6 py-2.5 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-md hover:opacity-90 transition text-sm">
                View Details
            </span>
        </div>
    </div>
</a>
