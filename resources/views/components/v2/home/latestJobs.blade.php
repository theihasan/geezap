<section class="bg-[#12122b] py-20">
    <div class="mx-auto max-w-7xl px-6">
        <div class="mb-12 flex items-end justify-between">
            <div>
                <h2 class="mb-2 text-3xl font-bold text-white">Latest Jobs</h2>
                <p class="text-gray-300">Discover the latest job openings developers are exploring</p>
            </div>
            <a href="{{ route('job.index') }}"
                class="font-ubuntu-regular flex items-center gap-2 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 px-6 py-3 font-medium text-white transition-opacity hover:opacity-90">
                See All Jobs
                <i class="las la-arrow-right"></i>
            </a>
        </div>

        <!-- Enhanced Job Listings -->
        <div class="space-y-6">
            @foreach ($latestJobs as $job)
                <div
                    class="group relative rounded-2xl border border-gray-700 bg-[#1a1a3a] p-6 transition hover:border-pink-500/50">
                    <div class="flex flex-col gap-6 md:flex-row">
                        <!-- Left Side: Company Logo -->
                        <div class="relative md:w-48">
                            <a href="{{ route('job.show', $job->slug) }}">
                                <img src="{{ $job->employer_logo ?? 'https://placehold.co/400x200/2a2a4a/FFFFFF' }}"
                                    alt="{{ $job->employer_name }}"
                                    class="h-32 w-full rounded-xl object-cover md:h-full">
                            </a>
                        </div>

                        <!-- Right Side: Job Details -->
                        <div class="flex-1">
                            <!-- Top Section -->
                            <div class="mb-4 flex flex-col justify-between gap-4 md:flex-row">
                                <div>
                                    <a href="{{ route('job.show', $job->slug) }}"
                                        class="font-medium text-white transition-colors hover:text-pink-500">
                                        <h3 class="text-xl font-semibold text-white">{{ $job->job_title }}</h3>
                                    </a>
                                    <p class="text-gray-300">{{ $job->employer_name }}</p>
                                </div>
                                @if ($job->min_salary && $job->max_salary)
                                    <div class="font-semibold text-pink-300">
                                        $ {{ \App\Helpers\NumberFormatter::formatNumber($job->min_salary) }} -
                                        $ {{ \App\Helpers\NumberFormatter::formatNumber($job->max_salary) }} /
                                        {{ $job->salary_period }}
                                    </div>
                                @endif
                            </div>

                            <!-- Job Details Grid -->
                            <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-4">
                                <!-- Location -->
                                <div class="flex items-center gap-2">
                                    <i class="las la-map-marker text-pink-500"></i>
                                    <span class="text-gray-300">{{ $job->is_remote ? 'Remote' : $job->city }}</span>
                                </div>
                                <!-- Posted Date -->
                                <div class="flex items-center gap-2">
                                    <i class="las la-calendar text-pink-500"></i>
                                    <span class="text-gray-300">Posted {{ $job->created_at->diffForHumans() }}</span>
                                </div>
                                <!-- Experience -->
                                <div class="flex items-center gap-2">
                                    <i class="las la-briefcase text-pink-500"></i>
                                    <span class="text-gray-300">{{ $job->experience_level ?? 'Not specified' }}</span>
                                </div>
                                <!-- Employment Type -->
                                <div class="flex items-center gap-2">
                                    <i class="las la-clock text-pink-500"></i>
                                    <span class="text-gray-300">{{ $job->employment_type }}</span>
                                </div>
                            </div>
                            @if ($job->description)
                                <p class="mb-4 line-clamp-2 text-gray-300">
                                    {{ Str::limit(strip_tags($job->description), 300) }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Fixed Apply Button (visible on hover) -->
                    <div
                        class="absolute bottom-6 right-6 opacity-0 transition-all duration-300 group-hover:opacity-100">
                        <a href="{{ route('job.show', $job->slug) }}"
                            class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-pink-500 to-purple-600 px-6 py-3 text-white transition-opacity hover:opacity-90">
                            <span>Apply Now</span>
                            <i class="las la-arrow-right"></i>
                        </a>
                    </div>

                    <!-- Company Details Tooltip (visible on hover) -->
                    @if($job->benefits)
                        <div
                            class="pointer-events-none absolute bottom-0 left-6 z-10 w-72 translate-y-full transform rounded-xl border border-pink-500/50 bg-[#1a1a3a] p-4 opacity-0 shadow-xl transition-all duration-300 group-hover:opacity-100">
                            <div class="space-y-2 text-sm">
                                <div class="font-semibold text-white">{{ $job->employer_name }}</div>
                                <div class="text-gray-300">{{ ucfirst($job->category->name) ?? 'Technology' }}</div>
                                @foreach ($job->benefits as $benefit)
                                    <div class="text-gray-300">{{ $benefit }}</div>
                                @endforeach

                            </div>
                        </div>
                    @endif
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
