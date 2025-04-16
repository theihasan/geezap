<section class="bg-[#12122b] py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="mb-8 sm:mb-12 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h2 class="mb-2 text-3xl font-bold text-white">Latest Jobs</h2>
                <p class="text-gray-300">Discover the latest job openings developers are exploring</p>
            </div>
        </div>

        <!-- Two Column Job Listings Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($latestJobs as $job)
                <div class="group relative overflow-hidden rounded-xl border border-gray-700 bg-[#1a1a3a] p-6 transition-all duration-300 hover:border-pink-500/50 hover:shadow-lg hover:shadow-pink-500/10 hover:-translate-y-1 animate-fadeIn" style="animation-delay: {{ $loop->index * 100 }}ms">
                    <!-- Company Logo & Job Type -->
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 overflow-hidden rounded-lg border border-gray-700 bg-[#12122b] flex items-center justify-center">
                                @if($job->employer_logo)
                                    <img src="{{ $job->employer_logo }}" alt="{{ $job->employer_name }}" class="h-full w-full object-contain p-1">
                                @else
                                    <i class="las la-building text-pink-500 text-2xl"></i>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-medium text-white">{{ $job->employer_name }}</h3>
                                <span class="text-sm text-gray-400">{{ $job->category->name }}</span>
                            </div>
                        </div>
                        <span class="rounded-full bg-pink-500/10 px-3 py-1 text-xs font-medium text-pink-400">
                            {{ $job->employment_type }}
                        </span>
                    </div>

                    <!-- Job Title & Location -->
                    <div class="mt-5">
                        <a href="{{ route('job.show', $job->slug) }}" class="block">
                            <h3 class="text-xl font-semibold text-white mb-3 group-hover:text-pink-400 transition-colors">{{ $job->job_title }}</h3>
                        </a>
                        <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-gray-400">
                            @if($job->state && $job->country)
                                <div class="flex items-center gap-2">
                                    <i class="las la-map-marker text-lg"></i>
                                    {{ $job->state }}, {{ $job->country }}
                                </div>
                            @endif
                            <div class="flex items-center gap-2">
                                <i class="las la-calendar text-lg"></i>
                                {{ $job->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <!-- Salary & Tags -->
                    <div class="mt-5 flex flex-wrap items-center justify-between">
                        <div class="flex gap-2 flex-wrap">
                            <span class="px-3 py-1 bg-pink-500/10 text-pink-400 text-sm font-medium rounded-md">
                                {{ $job->category->name }}
                            </span>
                            @if($job->benefits)
                                <span class="px-3 py-1 bg-purple-500/10 text-purple-400 text-sm font-medium rounded-md">
                                    {{ count($job->benefits) }} Benefits
                                </span>
                            @endif
                        </div>
                        @if($job->min_salary && $job->max_salary)
                            <div class="text-white font-medium mt-3 sm:mt-0">
                                ${{ number_format($job->min_salary) }} - ${{ number_format($job->max_salary) }}
                                <span class="text-gray-400 text-sm">/ {{ $job->salary_period }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Apply Button -->
                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('job.show', $job->slug) }}"
                           class="rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 px-5 py-2.5 text-sm font-medium text-white transition-all hover:opacity-90 hover:scale-105 transform">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12 flex justify-center">
            <a href="{{ route('job.index') }}"
               class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 px-8 py-4 text-lg font-medium text-white transition-opacity hover:opacity-90">
                Explore All Job Opportunities
                <i class="las la-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
