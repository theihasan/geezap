@extends('v2.layouts.app')
@section('content')
    <section class="py-20 bg-[#12122b]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Left Sidebar Filter Section -->
                <x-v2.job.filter></x-v2.job.filter>
                <!-- Job Listings Section -->
                <div class="col-span-full md:col-span-3">
                    @forelse($jobs as $job)
                        <x-v2.job.card :job="$job"></x-v2.job.card>
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
