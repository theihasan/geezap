@props(['mostViewedJobs'])

<div class="space-y-8">
    @foreach ($mostViewedJobs as $job)
        <div class="group relative bg-gradient-to-r from-[#1a1a3a] to-[#1e1e4a] rounded-2xl overflow-hidden shadow-lg hover:shadow-pink-500/20 transition-all duration-300">
            <!-- Popularity Indicator Bar -->
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-pink-500 to-purple-600"></div>

            <!-- Main Content -->
            <div class="p-6">
                <!-- Header: Company and Views -->
                <div class="flex justify-between items-center mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-[#12122b] rounded-lg flex items-center justify-center overflow-hidden border border-gray-700">
                            @if($job->employer_logo)
                                <img src="{{ $job->employer_logo }}" alt="{{ $job->employer_name }}" class="w-full h-full object-contain p-1">
                            @else
                                <i class="las la-building text-pink-500 text-2xl"></i>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-white font-medium">{{ $job->employer_name }}</h4>
                            <p class="text-gray-400 text-sm">{{ $job->category->name }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 bg-[#12122b] rounded-full px-3 py-1.5 border border-pink-500/20">
                        <div class="flex items-center justify-center w-5 h-5 rounded-full bg-pink-500/20">
                            <i class="las la-eye text-pink-500 text-sm"></i>
                        </div>
                        <span class="text-pink-400 text-sm font-medium">{{ \App\Helpers\NumberFormatter::formatNumber($job->views) }}</span>
                    </div>
                </div>

                <!-- Job Title -->
                <a href="{{ route('job.show', $job->slug) }}" class="block mb-4">
                    <h3 class="text-xl font-bold text-white group-hover:text-pink-400 transition-colors">{{ $job->job_title }}</h3>
                </a>

                <!-- Job Highlights -->
                <div class="flex flex-wrap gap-3 mb-5">
                    @if($job->is_remote)
                        <div class="flex items-center gap-1.5 bg-[#12122b] rounded-full px-3 py-1 text-sm text-gray-300">
                            <i class="las la-globe text-pink-500"></i>
                            <span>Remote</span>
                        </div>
                    @elseif($job->city || $job->state)
                        <div class="flex items-center gap-1.5 bg-[#12122b] rounded-full px-3 py-1 text-sm text-gray-300">
                            <i class="las la-map-marker text-pink-500"></i>
                            <span>{{ $job->city ?? $job->state }}</span>
                        </div>
                    @endif

                    <div class="flex items-center gap-1.5 bg-[#12122b] rounded-full px-3 py-1 text-sm text-gray-300">
                        <i class="las la-clock text-pink-500"></i>
                        <span>{{ $job->employment_type }}</span>
                    </div>

                    <div class="flex items-center gap-1.5 bg-[#12122b] rounded-full px-3 py-1 text-sm text-gray-300">
                        <i class="las la-calendar text-pink-500"></i>
                        <span>{{ $job->created_at->diffForHumans() }}</span>
                    </div>

                    @if($job->experience_level)
                        <div class="flex items-center gap-1.5 bg-[#12122b] rounded-full px-3 py-1 text-sm text-gray-300">
                            <i class="las la-briefcase text-pink-500"></i>
                            <span>{{ $job->experience_level }}</span>
                        </div>
                    @endif
                </div>

                <!-- Salary (if available) -->
                @if ($job->min_salary && $job->max_salary)
                    <div class="mb-5 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-pink-500/10 flex items-center justify-center">
                            <i class="las la-dollar-sign text-pink-500 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Salary Range</p>
                            <p class="text-white font-semibold">
                                ${{ \App\Helpers\NumberFormatter::formatNumber($job->min_salary) }} -
                                ${{ \App\Helpers\NumberFormatter::formatNumber($job->max_salary) }} /
                                {{ $job->salary_period }}
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Bottom Section: Description Preview and Action -->
                <div class="flex flex-col md:flex-row gap-6 items-end justify-between">
                    <!-- Description Preview -->
                    @if ($job->description)
                        <p class="text-gray-300 text-sm line-clamp-2 md:max-w-[60%]">
                            {{ Str::limit(strip_tags($job->description), 150) }}
                        </p>
                    @endif

                    <!-- Action Button -->
                    <a href="{{ route('job.show', $job->slug) }}"
                       class="flex items-center gap-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white px-5 py-2.5 rounded-xl hover:opacity-90 transition-all transform hover:scale-105 whitespace-nowrap">
                        <span>View Details</span>
                        <i class="las la-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="mt-12 flex justify-center">
    <a href="{{ route('job.index') }}"
       class="group flex items-center gap-2 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 px-8 py-4 text-lg font-medium text-white transition-all hover:opacity-90 hover:scale-105">
        Explore All Job Opportunities
        <i class="las la-arrow-right transition-transform group-hover:translate-x-1"></i>
    </a>
</div>
