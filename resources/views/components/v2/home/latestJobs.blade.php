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
                <x-v2.home.job-card :job="$job"></x-v2.home.job-card>
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
