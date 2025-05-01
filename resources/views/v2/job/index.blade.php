@extends('v2.layouts.app')
@section('content')
    <section class="py-20 bg-[#12122b]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-8">
                <h2 class="text-2xl font-ubuntu-bold text-white">
                    Available Jobs <span class="text-pink-500">({{ $jobs->total() }})</span>
                </h2>
            </div>
            <livewire:job-filter />
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

            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('jobs-filter-form').addEventListener('submit', function() {
                    const checkboxes = document.querySelectorAll('.type-checkbox');
                    const types = Array.from(checkboxes)
                        .filter(checkbox => checkbox.checked)
                        .map(checkbox => checkbox.value)
                        .join(',');
                    document.getElementById('types-hidden-input').value = types;
                });
            });
        });
    </script>
@endpush
