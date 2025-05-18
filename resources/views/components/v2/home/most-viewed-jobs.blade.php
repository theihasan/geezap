@props(['mostViewedJobs'])

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @foreach ($mostViewedJobs as $job)
        <div class="group relative bg-[#1a1a3a] rounded-2xl overflow-hidden transition-all duration-300 hover:bg-[#1e1e4a]"
             style="animation-delay: {{ $loop->index * 100 }}ms">
            <!-- Accent Line -->
            <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-pink-500 to-purple-600 transform origin-top scale-y-0 group-hover:scale-y-100 transition-transform duration-500"></div>

            <div class="p-6">
                <!-- Views Counter -->
                <div class="absolute top-6 right-6">
                    <div class="flex items-center gap-2 text-sm text-gray-400">
                        <i class="las la-eye text-pink-500"></i>
                        <span class="font-mono">{{ \App\Helpers\NumberFormatter::formatNumber($job->views) }}</span>
                    </div>
                </div>

                <!-- Company Section -->
                <div class="flex items-start gap-4 mb-6">
                    <div class="relative w-12 h-12 rounded-lg bg-[#12122b] flex items-center justify-center overflow-hidden group-hover:scale-110 transition-transform duration-300">
                        @if($job->employer_logo)
                            <img src="{{ $job->employer_logo }}" alt="{{ $job->employer_name }}" class="w-full h-full object-contain p-2">
                        @else
                            <i class="las la-building text-pink-500 text-2xl"></i>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-white font-medium">{{ $job->employer_name }}</h4>
                        <span class="text-sm text-gray-400">{{ $job->category?->name }}</span>
                    </div>
                </div>

                <!-- Job Title -->
                <a href="{{ route('job.show', $job->slug) }}" class="block mb-6">
                    <h3 class="text-xl font-bold text-white group-hover:text-pink-400 transition-colors">{{ $job->job_title }}</h3>
                </a>

                <!-- Key Details -->
                <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                    @if($job->city || $job->state)
                        <div class="flex items-center gap-2 text-gray-400">
                            <i class="las la-map-marker text-pink-500/70"></i>
                            <span>{{ $job->city ?? $job->state }}</span>
                        </div>
                    @endif

                    <div class="flex items-center gap-2 text-gray-400">
                        <i class="las la-clock text-purple-500/70"></i>
                        <span>{{ $job->employment_type }}</span>
                    </div>

                    <div class="flex items-center gap-2 text-gray-400">
                        <i class="las la-calendar text-green-500/70"></i>
                        <span>{{ $job->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <!-- Salary -->
                @if ($job->min_salary && $job->max_salary)
                    <div class="flex items-baseline gap-2 mb-6">
                        <span class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-purple-500">
                            ${{ \App\Helpers\NumberFormatter::formatNumber($job->min_salary) }} -
                            ${{ \App\Helpers\NumberFormatter::formatNumber($job->max_salary) }}
                        </span>
                        <span class="text-gray-400 text-sm">/ {{ $job->salary_period }}</span>
                    </div>
                @endif

                <!-- Action Bar -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-700/30">
                    @if ($job->description)
                        <p class="text-gray-400 text-sm line-clamp-1 max-w-[60%]">{{ Str::limit(strip_tags($job->description), 80) }}</p>
                    @endif

                    <a href="{{ route('job.show', $job->slug) }}"
                       class="relative inline-flex items-center gap-2 text-white">
                        <span class="group-hover:mr-2 transition-all duration-300">View</span>
                        <i class="las la-arrow-right opacity-0 group-hover:opacity-100 transition-all duration-300"></i>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Minimal Explore Button -->
<div class="mt-12 text-center">
    <a href="{{ route('job.index') }}"
       class="inline-flex items-center gap-3 px-8 py-3 rounded-xl text-white hover:text-pink-400 transition-colors">
        <span class="text-lg">View All Jobs</span>
        <i class="las la-arrow-right"></i>
    </a>
</div>
