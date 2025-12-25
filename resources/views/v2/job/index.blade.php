@extends('v2.layouts.app')
@section('content')
    <section class="py-6 md:py-20 bg-gray-50 dark:bg-[#12122b] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <!-- Desktop Header -->
            <div class="hidden lg:block mb-8">
                <h2 class="text-2xl font-sans text-gray-900 dark:text-white">
                    Available Jobs <span class="text-blue-600 dark:text-pink-500" id="job-count">({{ $jobs->total() }})</span>
                </h2>
            </div>
            
            <!-- Mobile Header -->
            <div class="block lg:hidden mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                        Find Jobs
                    </h1>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <span id="job-count-mobile">{{ $jobs->total() }}</span> jobs
                    </div>
                </div>
            </div>

            <livewire:job-filter />
        </div>
    </section>
@endsection
@push('extra-js')
    <script>
        const initialJobCount = document.getElementById('job-count').textContent.replace(/[()]/g, '');
        localStorage.setItem('last_job_count', initialJobCount);

        function updateJobCountDisplay(count) {
            const jobCountElement = document.getElementById('job-count');
            const jobCountMobileElement = document.getElementById('job-count-mobile');
            
            if (jobCountElement) {
                jobCountElement.textContent = `(${count})`;
            }
            if (jobCountMobileElement) {
                jobCountMobileElement.textContent = count;
            }
        }
        
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('jobCountUpdated', (count) => {
                localStorage.setItem('last_job_count', count);
                updateJobCountDisplay(count);
            });
        });
        
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                const lastCount = localStorage.getItem('last_job_count');
                if (lastCount) {
                    updateJobCountDisplay(lastCount);
                }
                
                // Refresh the Livewire component instead of calling render() directly
                const livewireElement = document.querySelector('[wire\\:id]');
                if (livewireElement && typeof window.Livewire !== 'undefined') {
                    const componentId = livewireElement.getAttribute('wire:id');
                    const livewireComponent = Livewire.find(componentId);
                    
                    if (livewireComponent) {
                        // Use $refresh to properly refresh the component
                        livewireComponent.$refresh();
                    }
                }
            }
        });
        
        document.getElementById('jobs-filter-form')?.addEventListener('submit', function() {
            const checkboxes = document.querySelectorAll('.type-checkbox');
            const types = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value)
                .join(',');
            document.getElementById('types-hidden-input').value = types;
        });
    </script>
@endpush