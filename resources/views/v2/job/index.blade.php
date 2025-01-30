@extends('v2.layouts.app')
@section('content')
    <section class="py-20 bg-[#12122b]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Left Sidebar Filter Section -->
                <div
                    class="bg-[#1a1a3a] rounded-2xl p-6 border border-gray-700 mb-8 md:mb-0 md:col-span-1 col-span-full md:sticky md:top-6 h-fit">
                    <h3 class="text-xl font-ubuntu-bold text-white mb-4">Filter Jobs</h3>
                    <form method="GET" action="{{ route('job.index') }}" class="space-y-4 font-ubuntu" id="jobs-filter-form">
                        <!-- Search Filter -->
                        <div>
                            <label class="text-gray-400 text-sm">Search Jobs</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search by title..."
                                class="w-full bg-[#12122b] border border-gray-700 text-gray-300 rounded-lg px-4 py-2 mt-2 focus:border-pink-500 focus:outline-none">
                        </div>

                        <!-- Job Category Filter -->
                        <div>
                            <label class="text-gray-400 text-sm">Job Category</label>
                            <select name="category"
                                class="w-full bg-[#12122b] border border-gray-700 text-gray-300 rounded-lg px-4 py-2 mt-2 focus:border-pink-500 focus:outline-none">
                                <option value="">All Categories</option>
                                @foreach (\App\Models\JobCategory::all() as $category)
                                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Job Types Filter -->
                        <div>
                            <label class="text-gray-400 text-sm block mb-2">Job Types</label>
                            <div class="space-y-2" id="job-types">
                                @foreach (['fulltime' => 'Full Time', 'contractor' => 'Contractor', 'parttime' => 'Part Time'] as $value => $label)
                                    <label class="flex items-center gap-3 text-gray-300 cursor-pointer">
                                        <input type="checkbox" value="{{ $value }}" @checked(in_array($value, explode(',', request('types', ''))))
                                            class="w-5 h-5 bg-[#12122b] text-pink-500 border border-gray-700 rounded-lg focus:ring-pink-500 type-checkbox">
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                                <input type="hidden" name="types" id="types-hidden-input" value="{{ request('types') }}">

                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-gradient-to-r from-pink-500 to-purple-600 text-white py-2 px-6 rounded-lg hover:opacity-90 transition-opacity font-ubuntu-medium mt-6">
                            Apply Filters
                        </button>
                    </form>
                </div>

                <!-- Job Listings Section -->
                <div class="col-span-full md:col-span-3">
                    @forelse($jobs as $job)
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
                                            <div class="text-pink-300 font-ubuntu-medium text-base md:text-lg mt-2 md:hidden">
                                                ${{ number_format($job->min_salary) }} - ${{ number_format($job->max_salary) }}
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
                    @empty
                        <div class="bg-[#1a1a3a] rounded-2xl border border-gray-700 p-6 text-center">
                            <p class="text-gray-400">No jobs found matching your criteria.</p>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                        <x-v2.pagination :jobs="$jobs"></x-v2.pagination>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('extra-js')
    <script>
        document.getElementById('jobs-filter-form').addEventListener('submit', function() {
            const checkboxes = document.querySelectorAll('.type-checkbox');
            const types = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value)
                .join(',');
            document.getElementById('types-hidden-input').value = types;
        });
    </script>
@endpush
