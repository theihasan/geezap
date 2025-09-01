@extends('v2.layouts.app')

@push('extra-css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
@endpush

@section('content')
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Job Details Main Section -->
            <div class="bg-white dark:bg-[#1a1a3a] p-6 rounded-2xl border border-gray-200 dark:border-gray-700 mb-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white font-oxanium-semibold">{{ $job->job_title }}</h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $job->employer_name }} • {{ $job->state }}, {{ $job->country }} • {{ $job->is_remote ? 'Remote' : 'On-site' }}</p>
                    </div>
                    <div class="mt-4 md:mt-0 flex flex-col items-start md:items-end">
                        @if($job->min_salary && $job->max_salary)
                            <span class="text-blue-600 dark:text-pink-300 font-semibold text-xl">
                                ${{ number_format($job->min_salary) }} - ${{ number_format($job->max_salary) }} @if($job->salary_period) / {{ $job->salary_period }} @endif
                            </span>
                        @endif
                        <a href="#applyjob" class="mt-2 px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white rounded-lg hover:opacity-90 transition-opacity font-medium flex items-center gap-2">
                            <i class="las la-paper-plane text-xl"></i> Apply Now
                        </a>
                    </div>
                </div>

                <div class="text-gray-600 dark:text-gray-400 text-sm space-y-4 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="flex items-center gap-2">
                        <i class="las la-calendar-alt text-blue-600 dark:text-pink-300"></i>
                        <span>Posted on: <span class="text-gray-900 dark:text-white">{{ $job->posted_at?->isoFormat('Do MMMM, YYYY') }}</span></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="las la-clock text-blue-600 dark:text-pink-300"></i>
                        <span>Employment Type: <span class="text-gray-900 dark:text-white">{{ $job->employment_type }}</span></span>
                    </div>
                </div>
            </div>

            <!-- Job Description & Requirements Section with Sidebar -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Left Content -->
                <div class="md:col-span-2 bg-white dark:bg-[#1a1a3a] p-6 rounded-2xl border border-gray-200 dark:border-gray-700 space-y-6">
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">Job Description</h3>
                    <div class="text-gray-700 dark:text-gray-300 leading-relaxed prose prose-gray dark:prose-invert max-w-none">
                        {!! nl2br($job->description) !!}
                    </div>

                    @if($job->responsibilities)
                        <h3 class="text-2xl font-semibold mt-8 text-gray-900 dark:text-white">Responsibilities</h3>
                        <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                            @foreach($job->responsibilities as $responsibility)
                                <li>{{ $responsibility }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if($job->qualifications)
                        <h3 class="text-2xl font-semibold mt-8 text-gray-900 dark:text-white">Requirements</h3>
                        <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                            @foreach($job->qualifications as $qualification)
                                <li>{{ $qualification }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Right Sidebar -->
                <div class="md:col-span-1 space-y-6">
                    <!-- Company Info -->
                    <div class="bg-white dark:bg-[#1a1a3a] p-6 rounded-2xl border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-4 mb-6">
                            @if($job->employer_logo)
                            <img src="{{ $job->employer_logo }}" alt="{{ $job->employer_name }}" class="w-16 h-16 rounded-xl object-cover">
                            @else
                            <img src="https://placehold.co/32x32" alt="{{ $job->employer_name }}" class="w-16 h-16 rounded-xl object-cover">
                            @endif
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $job->employer_name }}</h3>
                                <p class="text-gray-600 dark:text-gray-400">{{ $job->industry ?? 'Technology' }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @if($job->required_experience)
                                <div class="flex items-center gap-3">
                                    <i class="las la-briefcase text-blue-600 dark:text-pink-300 text-2xl"></i>
                                    <div>
                                        <p class="text-gray-600 dark:text-gray-400">Experience Required</p>
                                        <p class="text-gray-900 dark:text-white">{{ number_format($job->required_experience / 12, 1) }}+ Years</p>
                                    </div>
                                </div>
                            @endif

                            @if($job->state && $job->country)
                                <div class="flex items-center gap-3">
                                    <i class="las la-map-marker text-blue-600 dark:text-pink-300 text-2xl"></i>
                                    <div>
                                        <p class="text-gray-600 dark:text-gray-400">Location</p>
                                        <p class="text-gray-900 dark:text-white">{{ $job->state }}, {{ $job->country }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center gap-3">
                                <i class="las la-clock text-blue-600 dark:text-pink-300 text-2xl"></i>
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400">Job Type</p>
                                    <p class="text-gray-900 dark:text-white">{{ $job->employment_type }}</p>
                                </div>
                            </div>
                        </div>

                        @if($job->benefits)
                            <div class="border-t border-gray-200 dark:border-gray-700 mt-6 pt-6">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Benefits</h3>
                                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                                    @foreach($job->benefits as $benefit)
                                        <li>{{ $benefit }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!-- Location Map -->
{{--                    @if($job->latitude && $job->longitude)--}}
{{--                    <div class="bg-white dark:bg-[#1a1a3a] p-6 rounded-2xl border border-gray-200 dark:border-gray-700">--}}
{{--                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">--}}
{{--                            <i class="las la-map text-blue-600 dark:text-pink-300"></i>--}}
{{--                            Job Location--}}
{{--                        </h3>--}}
{{--                        <div id="job-map" class="h-64 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700" --}}
{{--                             data-lat="{{ $job->latitude }}" --}}
{{--                             data-lng="{{ $job->longitude }}"--}}
{{--                             data-title="{{ $job->job_title }}"--}}
{{--                             data-company="{{ $job->employer_name }}"--}}
{{--                             data-location="{{ $job->state }}, {{ $job->country }}">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    @endif--}}

                    <!-- Separate card for buttons -->
                    <livewire:jobs.save-for-letter :job="$job" />
                </div>
            </div>

            <!-- Cover Letter Generator -->
            <livewire:cover-letter-chat :job="$job" />

            <!-- Apply Now Section Before Related Jobs -->
            <livewire:apply-job :job="$job" />

            <!-- Related Jobs Section -->
            @if($relatedJobs->count() > 0)
                <div class="mt-16">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-8">Related Jobs</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedJobs as $relatedJob)
                            <div class="group bg-white dark:bg-[#1a1a3a] p-6 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-blue-500/50 dark:hover:border-pink-500/50 transition">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-gray-100 dark:bg-white/10 rounded-xl flex items-center justify-center">
                                            <a href="{{ route('job.show', $relatedJob->slug) }}">
                                                @if($relatedJob->employer_logo)
                                                <img src="{{ $relatedJob->employer_logo }}" alt="{{ $relatedJob->employer_name }}" class="w-8 h-8 object-contain">
                                                @else
                                                    <img src="https://placehold.co/32x32" alt="{{ $relatedJob->employer_name }}" class="w-8 h-8 object-contain">
                                                @endif
                                            </a>
                                        </div>
                                        <div>
                                            <h3 class="text-gray-900 dark:text-white font-medium">{{ $relatedJob->employer_name }}</h3>
                                            <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $relatedJob->posted_at?->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 text-sm bg-blue-500/10 dark:bg-pink-500/10 text-blue-600 dark:text-pink-300 rounded-full">
                                    {{ $relatedJob->employment_type }}
                                </span>
                                </div>
                                <a href="{{ route('job.show', $relatedJob->slug) }}">
                                <h4 class="text-lg text-gray-900 dark:text-white font-medium mb-2">{{ $relatedJob->job_title }}</h4>
                                </a>
                                <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">
                                    <i class="las la-map-marker"></i>
                                    {{ $relatedJob->state }}, {{ $relatedJob->country }}
                                </span>
                                    <a href="{{ route('job.show', $relatedJob->slug) }}" class="text-blue-600 dark:text-pink-300 hover:text-blue-700 dark:hover:text-pink-400 transition">
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

@push('extra-js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapContainer = document.getElementById('job-map');

    if (mapContainer) {
        const lat = parseFloat(mapContainer.dataset.lat);
        const lng = parseFloat(mapContainer.dataset.lng);
        const title = mapContainer.dataset.title;
        const company = mapContainer.dataset.company;
        const location = mapContainer.dataset.location;

        if (lat && lng) {
            // Initialize the map
            const map = L.map('job-map', {
                zoomControl: false,
                scrollWheelZoom: false
            }).setView([lat, lng], 13);

            // Add zoom control in bottom right
            L.control.zoom({
                position: 'bottomright'
            }).addTo(map);

            // Add tile layer with dark theme
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 20
            }).addTo(map);

            // Create custom icon
            const customIcon = L.divIcon({
                html: '<div class="custom-marker"><i class="las la-map-marker-alt"></i></div>',
                className: 'custom-div-icon',
                iconSize: [40, 40],
                iconAnchor: [20, 40],
                popupAnchor: [0, -40]
            });

            // Add marker
            const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);

            // Add popup
            marker.bindPopup(`
                <div class="custom-popup">
                    <h4 class="font-semibold text-white mb-1">${title}</h4>
                    <p class="text-gray-300 text-sm mb-1">${company}</p>
                    <p class="text-gray-400 text-xs">${location}</p>
                </div>
            `);

            marker.openPopup();
        }
    }
});
</script>

<style>
.custom-div-icon {
    background: transparent;
    border: none;
}

.custom-marker {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50% 50% 50% 0;
    transform: rotate(-45deg);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.dark .custom-marker {
    background: linear-gradient(135deg, #ec4899, #8b5cf6);
    box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
}

.custom-marker i {
    transform: rotate(45deg);
    font-size: 20px;
}

.leaflet-popup-content-wrapper {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 12px !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
}

.dark .leaflet-popup-content-wrapper {
    background: #1a1a3a !important;
    border: 1px solid #374151 !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5) !important;
}

.leaflet-popup-content {
    margin: 16px !important;
}

.leaflet-popup-tip {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
}

.dark .leaflet-popup-tip {
    background: #1a1a3a !important;
    border: 1px solid #374151 !important;
}

.custom-popup h4 {
    color: #111827;
    font-weight: 600;
    margin-bottom: 4px;
}

.dark .custom-popup h4 {
    color: #ffffff;
}

.custom-popup p {
    margin: 0;
}

.leaflet-control-zoom {
    border: none !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
}

.dark .leaflet-control-zoom {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3) !important;
}

.leaflet-control-zoom a {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    color: #3b82f6 !important;
    font-weight: bold !important;
}

.dark .leaflet-control-zoom a {
    background: #1a1a3a !important;
    border: 1px solid #374151 !important;
    color: #ec4899 !important;
}

.leaflet-control-zoom a:hover {
    background: #f3f4f6 !important;
    color: #1d4ed8 !important;
}

.dark .leaflet-control-zoom a:hover {
    background: #374151 !important;
    color: #f472b6 !important;
}
</style>
@endpush
