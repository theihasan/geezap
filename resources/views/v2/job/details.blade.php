@extends('v2.layouts.app')
@section('content')
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-6">
            <!-- CV Generation Call to Action -->
            <div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-2xl p-6 mb-8 flex flex-col md:flex-row items-center justify-between">
                <div>
                    <h3 class="text-xl font-semibold">Generate a CV for this Job!</h3>
                    <p class="text-white mt-2">Based on your profile and this job description, you can create a tailored CV to apply directly.</p>
                </div>
                <button class="mt-4 md:mt-0 px-6 py-2 bg-white text-pink-600 font-medium rounded-lg hover:bg-gray-200 transition flex items-center gap-2" onclick="generateCv()">
                    <i class="las la-file-alt text-xl"></i> Generate CV
                </button>
            </div>

            <!-- Job Details Main Section -->
            <div class="bg-[#1a1a3a] p-6 rounded-2xl border border-gray-700 mb-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h2 class="text-3xl font-bold text-white font-oxanium-semibold">{{ $job->job_title }}</h2>
                        <p class="text-gray-400 mt-2">{{ $job->employer_name }} • {{ $job->state }}, {{ $job->country }} • {{ $job->is_remote ? 'Remote' : 'On-site' }}</p>
                    </div>
                    <div class="mt-4 md:mt-0 flex flex-col items-start md:items-end">
                        @if($job->min_salary && $job->max_salary)
                            <span class="text-pink-300 font-semibold text-xl">${{ number_format($job->min_salary) }} - ${{ number_format($job->max_salary) }} / {{ $job->salary_period }}</span>
                        @endif
                        <a href="{{ $job->apply_link }}" class="mt-2 px-6 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg hover:opacity-90 transition-opacity font-medium flex items-center gap-2">
                            <i class="las la-paper-plane text-xl"></i> Apply Now
                        </a>
                    </div>
                </div>

                <div class="text-gray-400 text-sm space-y-4 border-t border-gray-700 pt-6">
                    <div class="flex items-center gap-2">
                        <i class="las la-calendar-alt text-pink-300"></i>
                        <span>Posted on: <span class="text-white">{{ $job->posted_at->isoFormat('Do MMMM, YYYY') }}</span></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="las la-clock text-pink-300"></i>
                        <span>Employment Type: <span class="text-white">{{ $job->employment_type }}</span></span>
                    </div>
                </div>
            </div>

            <!-- Job Description & Requirements Section with Sidebar -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Left Content -->
                <div class="md:col-span-2 bg-[#1a1a3a] p-6 rounded-2xl border border-gray-700 space-y-6">
                    <h3 class="text-2xl font-semibold text-white">Job Description</h3>
                    <div class="text-gray-300 leading-relaxed prose prose-invert max-w-none">
                        {!! nl2br($job->description) !!}
                    </div>

                    @if($job->responsibilities)
                        <h3 class="text-2xl font-semibold mt-8 text-white">Responsibilities</h3>
                        <ul class="list-disc list-inside text-gray-300 space-y-2">
                            @foreach($job->responsibilities as $responsibility)
                                <li>{{ $responsibility }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if($job->qualifications)
                        <h3 class="text-2xl font-semibold mt-8 text-white">Requirements</h3>
                        <ul class="list-disc list-inside text-gray-300 space-y-2">
                            @foreach($job->qualifications as $qualification)
                                <li>{{ $qualification }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Right Sidebar -->
                <div class="md:col-span-1">
                    <div class="bg-[#1a1a3a] p-6 rounded-2xl border border-gray-700">
                        <div class="flex items-center gap-4 mb-6">
                            @if($job->employer_logo)
                            <img src="{{ $job->employer_logo }}" alt="{{ $job->employer_name }}" class="w-16 h-16 rounded-xl object-cover">
                            @else
                            <img src="https://placehold.co/32x32" alt="{{ $job->employer_name }}" class="w-16 h-16 rounded-xl object-cover">
                            @endif
                            <div>
                                <h3 class="text-xl font-semibold text-white">{{ $job->employer_name }}</h3>
                                <p class="text-gray-400">{{ $job->industry ?? 'Technology' }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @if($job->required_experience)
                                <div class="flex items-center gap-3">
                                    <i class="las la-briefcase text-pink-300 text-2xl"></i>
                                    <div>
                                        <p class="text-gray-400">Experience Required</p>
                                        <p class="text-white">{{ number_format($job->required_experience / 12, 1) }}+ Years</p>
                                    </div>
                                </div>
                            @endif

                            @if($job->state && $job->country)
                                <div class="flex items-center gap-3">
                                    <i class="las la-map-marker text-pink-300 text-2xl"></i>
                                    <div>
                                        <p class="text-gray-400">Location</p>
                                        <p class="text-white">{{ $job->state }}, {{ $job->country }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center gap-3">
                                <i class="las la-clock text-pink-300 text-2xl"></i>
                                <div>
                                    <p class="text-gray-400">Job Type</p>
                                    <p class="text-white">{{ $job->employment_type }}</p>
                                </div>
                            </div>
                        </div>

                        @if($job->benefits)
                            <div class="border-t border-gray-700 mt-6 pt-6">
                                <h3 class="text-xl font-semibold text-white mb-4">Benefits</h3>
                                <ul class="list-disc list-inside text-gray-300 space-y-2">
                                    @foreach($job->benefits as $benefit)
                                        <li>{{ $benefit }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!-- Separate card for buttons -->
                    <div class="bg-[#1a1a3a] p-6 rounded-2xl border border-gray-700 mt-6">
                        <button onclick="generateCv()" class="w-full px-6 py-3 bg-white text-pink-600 rounded-lg hover:bg-gray-200 transition font-medium flex items-center justify-center gap-2 mb-4">
                            <i class="las la-file-alt text-xl"></i> Generate CV
                        </button>
                    </div>
                </div>
            </div>

            <!-- Additional CV Generation Call to Action -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-500 mt-8 p-6 rounded-2xl border border-gray-700 text-center text-white">
                <h3 class="text-2xl font-semibold mb-4">Generate a Tailored CV Before Applying!</h3>
                <p class="text-gray-100 mb-6">A customized CV will make your application stand out. Use your profile and this job description to create the perfect CV!</p>
                <div class="flex justify-center">
                    <button class="px-8 py-3 bg-white text-pink-600 rounded-lg hover:bg-gray-200 transition font-medium text-lg flex items-center gap-2" onclick="generateCv()">
                        <i class="las la-file-alt text-xl"></i> Generate CV
                    </button>
                </div>
            </div>

            <!-- Apply Now Section Before Related Jobs -->
            <div class="bg-[#1a1a3a] mt-8 p-6 rounded-2xl border border-gray-700 text-center">
                <h3 class="text-2xl font-semibold mb-4 text-white">Ready to Apply?</h3>
                <p class="text-gray-300 mb-6">Click the button below to start your application process.</p>
                <div class="flex justify-center">
                    <a href="{{ $job->apply_link }}" class="px-8 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg hover:opacity-90 transition-opacity font-medium text-lg flex items-center gap-2">
                        <i class="las la-paper-plane text-xl"></i> Apply Now
                    </a>
                </div>
            </div>

            <!-- Related Jobs Section -->
            @if($relatedJobs->count() > 0)
                <div class="mt-16">
                    <h2 class="text-2xl font-semibold text-white mb-8">Related Jobs</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedJobs as $relatedJob)
                            <div class="group bg-[#1a1a3a] p-6 rounded-2xl border border-gray-700 hover:border-pink-500/50 transition">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                                            <a href="{{ route('job.show', $relatedJob->slug) }}">
                                                @if($relatedJob->employer_logo)
                                                <img src="{{ $relatedJob->employer_logo }}" alt="{{ $relatedJob->employer_name }}" class="w-8 h-8 object-contain">
                                                @else
                                                    <img src="https://placehold.co/32x32" alt="{{ $relatedJob->employer_name }}" class="w-8 h-8 object-contain">
                                                @endif
                                            </a>
                                        </div>
                                        <div>
                                            <h3 class="text-white font-medium">{{ $relatedJob->employer_name }}</h3>
                                            <p class="text-gray-400 text-sm">{{ $relatedJob->posted_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 text-sm bg-pink-500/10 text-pink-300 rounded-full">
                                    {{ $relatedJob->employment_type }}
                                </span>
                                </div>
                                <a href="{{ route('job.show', $relatedJob->slug) }}">
                                <h4 class="text-lg text-white font-medium mb-2">{{ $relatedJob->job_title }}</h4>
                                </a>
                                <div class="flex justify-between items-center">
                                <span class="text-gray-400">
                                    <i class="las la-map-marker"></i>
                                    {{ $relatedJob->state }}, {{ $relatedJob->country }}
                                </span>
                                    <a href="{{ route('job.show', $relatedJob->slug) }}" class="text-pink-300 hover:text-pink-400 transition">
                                        View Job <i class="las la-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
