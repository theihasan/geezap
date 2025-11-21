<div x-data="{
    sections: {
        basic: true,
        source: false,
        location: false,
        jobType: false
    },
    showMobileFilter: false
}">
    <x-loading />
    
    <!-- Mobile Header with Search & Filter Button -->
    <div class="block lg:hidden mb-6">
        <!-- Search Bar -->
        <div class="relative mb-4">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="las la-search text-gray-400 dark:text-gray-500 text-xl"></i>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Search jobs..."
                   class="w-full pl-12 pr-4 py-4 bg-white dark:bg-[#1a1a3a] border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-300 rounded-2xl text-lg focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none shadow-sm">
        </div>

        <!-- Quick Filter Pills -->
        <div class="flex gap-2 overflow-x-auto pb-2 mb-4" style="-webkit-overflow-scrolling: touch;">
            <!-- Remote Toggle -->
            <button wire:click="$toggle('remote')"
                    class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all
                           {{ $remote ? 'bg-blue-500 dark:bg-pink-500 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                <i class="las la-home mr-1"></i>Remote
            </button>

            <!-- Job Type Pills -->
            @foreach($jobTypes as $value => $label)
                <button wire:click="toggleJobType('{{ $value }}')"
                        class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all
                               {{ in_array($value, $types) ? 'bg-blue-500 dark:bg-pink-500 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    {{ $label }}
                </button>
            @endforeach

            <!-- Filter Button -->
            <button @click="showMobileFilter = true"
                    class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all">
                <i class="las la-filter mr-1"></i>
                Filters
                @if($this->getActiveFilterCount() > 0)
                    <span class="ml-1 px-2 py-0.5 text-xs bg-blue-500 dark:bg-pink-500 text-white rounded-full">
                        {{ $this->getActiveFilterCount() }}
                    </span>
                @endif
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Desktop Filter Sidebar -->
        <div class="hidden lg:block bg-white dark:bg-[#1a1a3a] rounded-2xl p-6 border border-gray-200 dark:border-gray-700 mb-8 lg:mb-0 lg:col-span-1 col-span-full lg:sticky lg:top-6 h-fit">
            <h3 class="text-xl font-sans text-gray-900 dark:text-white mb-4">
                Filter Jobs
                @if($this->getActiveFilterCount() > 0)
                    <span class="ml-2 px-2 py-0.5 text-sm bg-blue-500 dark:bg-pink-500 text-white rounded-full">
                        {{ $this->getActiveFilterCount() }}
                    </span>
                @endif
            </h3>

            <!-- Basic Filters Section -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                <button type="button"
                        x-on:click="sections.basic = !sections.basic"
                        class="flex justify-between items-center w-full text-left text-gray-900 dark:text-white font-medium">
                    <span>Basic Filters</span>
                    <i class="las" :class="sections.basic ? 'la-angle-up' : 'la-angle-down'"></i>
                </button>

                <div x-show="sections.basic" class="space-y-4 mt-3">
                    <!-- Search Filter -->
                    <div>
                        <label class="text-gray-600 dark:text-gray-400 text-sm">Search Jobs</label>
                        <input type="text" wire:model.live.debounce.300ms="search"
                               placeholder="Search by title..."
                               class="w-full bg-gray-50 dark:bg-[#12122b] border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-300 rounded-lg px-4 py-2 mt-2 focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none">
                    </div>

                    <!-- Job Category Filter -->
                    <div>
                        <label class="text-gray-600 dark:text-gray-400 text-sm">Job Category</label>
                        <select wire:model.live="category"
                                class="w-full bg-gray-50 dark:bg-[#12122b] border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-300 rounded-lg px-4 py-2 mt-2 focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Remote Job Filter -->
                    <div>
                        <label class="flex items-center gap-3 text-gray-700 dark:text-gray-300 cursor-pointer">
                            <input type="checkbox" wire:model.live="remote"
                                   class="w-5 h-5 bg-gray-50 dark:bg-[#12122b] text-blue-500 dark:text-pink-500 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-blue-500 dark:focus:ring-pink-500">
                            <span>Remote Only</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Source Filters Section -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                <button type="button"
                        x-on:click="sections.source = !sections.source"
                        class="flex justify-between items-center w-full text-left text-gray-900 dark:text-white font-medium">
                    <span>Source Filters</span>
                    <i class="las" :class="sections.source ? 'la-angle-up' : 'la-angle-down'"></i>
                </button>

                <div x-show="sections.source" class="space-y-4 mt-3">
                    <!-- Source Filter -->
                    <div>
                        <label class="text-gray-600 dark:text-gray-400 text-sm">Source</label>
                        <select wire:model.live="source"
                                class="w-full bg-gray-50 dark:bg-[#12122b] border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-300 rounded-lg px-4 py-2 mt-2 focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none">
                            <option value="">All Sources</option>
                            @foreach($publishers as $publisher)
                                <option value="{{ $publisher }}">{{ $publisher }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Exclude Source Filter -->
                    <div>
                        <label class="text-gray-600 dark:text-gray-400 text-sm">Exclude Source</label>
                        <select wire:model.live="exclude_source"
                                class="w-full bg-gray-50 dark:bg-[#12122b] border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-300 rounded-lg px-4 py-2 mt-2 focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none">
                            <option value="">Don't Exclude Any</option>
                            @foreach($publishers as $publisher)
                                <option value="{{ $publisher }}">{{ $publisher }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Location Filters Section -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                <button type="button"
                        x-on:click="sections.location = !sections.location"
                        class="flex justify-between items-center w-full text-left text-gray-900 dark:text-white font-medium">
                    <span>Location Filters</span>
                    <i class="las" :class="sections.location ? 'la-angle-up' : 'la-angle-down'"></i>
                </button>

                <div x-show="sections.location" class="space-y-4 mt-3">
                    <!-- Country Filter -->
                    <div>
                        <label class="text-gray-600 dark:text-gray-400 text-sm">Country</label>
                        <select wire:model.live="country"
                                class="w-full bg-gray-50 dark:bg-[#12122b] border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-300 rounded-lg px-4 py-2 mt-2 focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none">
                            <option value="">All Countries</option>
                            @foreach($countries as $code => $country)
                                <option value="{{ $code }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Job Type Filters Section -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                <button type="button"
                        x-on:click="sections.jobType = !sections.jobType"
                        class="flex justify-between items-center w-full text-left text-gray-900 dark:text-white font-medium">
                    <span>Job Type Filters</span>
                    <i class="las" :class="sections.jobType ? 'la-angle-up' : 'la-angle-down'"></i>
                </button>

                <div x-show="sections.jobType" class="space-y-4 mt-3">
                    <div class="space-y-2">
                        @foreach ($jobTypes as $value => $label)
                            <label class="flex items-center gap-3 text-gray-700 dark:text-gray-300 cursor-pointer">
                                <input type="checkbox" wire:model.live="types" value="{{ $value }}"
                                       class="w-5 h-5 bg-gray-50 dark:bg-[#12122b] text-blue-500 dark:text-pink-500 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-blue-500 dark:focus:ring-pink-500">
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Clear Filters Button -->
            <div class="flex items-center justify-between mt-6">
                <button type="button"
                        wire:click="clearAllFilters"
                        class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                    Clear All
                </button>
            </div>
        </div>

        <!-- Job Listings -->
        <div class="col-span-full lg:col-span-3 relative">
            <!-- Improved Loading State for Filters -->
            <div wire:loading wire:target="search, source, exclude_source, country, category, remote, types, clearAllFilters"
                 class="absolute inset-0 bg-white/90 dark:bg-[#1a1a3a]/90 rounded-2xl border border-gray-200 dark:border-gray-700 flex items-center justify-center z-20">
                <div class="text-center">
                    <div class="inline-block h-16 w-16 animate-spin rounded-full border-4 border-solid border-blue-500 dark:border-purple-500 border-r-transparent motion-reduce:animate-[spin_1.5s_linear_infinite] mb-4"></div>
                    <p class="text-gray-700 dark:text-gray-300 text-lg font-medium">Loading results...</p>
                </div>
            </div>

            <!-- Results -->
            <div class="space-y-4 md:space-y-6">
                @forelse($jobs as $job)
                    <x-v2.home.job-card :job="$job"/>
                @empty
                    <div class="bg-white dark:bg-[#1a1a3a] rounded-2xl border border-gray-200 dark:border-gray-700 p-6 text-center">
                        <p class="text-gray-600 dark:text-gray-400">No jobs found matching your criteria.</p>
                    </div>
                @endforelse

                <!-- Load More Button -->
                @if($hasMorePages)
                    <div class="mt-6 text-center">
                        <button wire:click="loadMore"
                                wire:loading.attr="disabled"
                                wire:target="loadMore"
                                class="px-6 py-3 bg-blue-500 dark:bg-pink-500 text-white rounded-lg hover:bg-blue-600 dark:hover:bg-pink-600 transition-colors disabled:opacity-50">
                            <span wire:loading.remove wire:target="loadMore">Load More Jobs</span>
                            <span wire:loading wire:target="loadMore" class="flex items-center justify-center">
                                <div class="inline-block h-5 w-5 mr-3 animate-spin rounded-full border-2 border-solid border-white border-r-transparent"></div>
                                Loading more jobs...
                            </span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Mobile Filter Modal -->
    <div x-show="showMobileFilter" 
         x-cloak
         class="fixed inset-0 z-50 lg:hidden"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50" @click="showMobileFilter = false"></div>
        
        <!-- Modal Panel -->
        <div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-[#1a1a3a] rounded-t-2xl max-h-[85vh] overflow-hidden"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Filter Jobs
                    @if($this->getActiveFilterCount() > 0)
                        <span class="ml-2 px-2 py-0.5 text-sm bg-blue-500 dark:bg-pink-500 text-white rounded-full">
                            {{ $this->getActiveFilterCount() }}
                        </span>
                    @endif
                </h3>
                <button @click="showMobileFilter = false" 
                        class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i class="las la-times text-xl text-gray-500 dark:text-gray-400"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="overflow-y-auto max-h-[calc(85vh-140px)] p-6">
                <div class="space-y-6">
                    <!-- Basic Filters -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Basic Filters</h4>
                        <div class="space-y-4">
                            <!-- Job Category Filter -->
                            <div>
                                <label class="block text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Job Category</label>
                                <select wire:model.live="category"
                                        class="w-full bg-gray-50 dark:bg-[#12122b] border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-300 rounded-xl px-4 py-3 focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Remote Job Filter -->
                            <div>
                                <label class="flex items-center gap-3 text-gray-700 dark:text-gray-300 cursor-pointer p-3 hover:bg-gray-50 dark:hover:bg-gray-800/50 rounded-xl transition-colors">
                                    <input type="checkbox" wire:model.live="remote"
                                           class="w-5 h-5 bg-gray-50 dark:bg-[#12122b] text-blue-500 dark:text-pink-500 border border-gray-200 dark:border-gray-700 rounded focus:ring-blue-500 dark:focus:ring-pink-500">
                                    <span class="font-medium">Remote Only</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Location Filters -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Location</h4>
                        <div>
                            <label class="block text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Country</label>
                            <select wire:model.live="country"
                                    class="w-full bg-gray-50 dark:bg-[#12122b] border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-300 rounded-xl px-4 py-3 focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none">
                                <option value="">All Countries</option>
                                @foreach($countries as $code => $country)
                                    <option value="{{ $code }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Job Type Filters -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Employment Type</h4>
                        <div class="space-y-2">
                            @foreach ($jobTypes as $value => $label)
                                <label class="flex items-center gap-3 text-gray-700 dark:text-gray-300 cursor-pointer p-3 hover:bg-gray-50 dark:hover:bg-gray-800/50 rounded-xl transition-colors">
                                    <input type="checkbox" wire:model.live="types" value="{{ $value }}"
                                           class="w-5 h-5 bg-gray-50 dark:bg-[#12122b] text-blue-500 dark:text-pink-500 border border-gray-200 dark:border-gray-700 rounded focus:ring-blue-500 dark:focus:ring-pink-500">
                                    <span class="font-medium">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Source Filters -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Source Filters</h4>
                        <div class="space-y-4">
                            <!-- Source Filter -->
                            <div>
                                <label class="block text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Source</label>
                                <select wire:model.live="source"
                                        class="w-full bg-gray-50 dark:bg-[#12122b] border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-300 rounded-xl px-4 py-3 focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none">
                                    <option value="">All Sources</option>
                                    @foreach($publishers as $publisher)
                                        <option value="{{ $publisher }}">{{ $publisher }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Exclude Source Filter -->
                            <div>
                                <label class="block text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Exclude Source</label>
                                <select wire:model.live="exclude_source"
                                        class="w-full bg-gray-50 dark:bg-[#12122b] border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-300 rounded-xl px-4 py-3 focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none">
                                    <option value="">Don't Exclude Any</option>
                                    @foreach($publishers as $publisher)
                                        <option value="{{ $publisher }}">{{ $publisher }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex items-center gap-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <button type="button"
                        wire:click="clearAllFilters"
                        class="flex-1 px-4 py-3 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white border border-gray-200 dark:border-gray-600 rounded-xl font-medium transition-colors">
                    Clear All
                </button>
                <button @click="showMobileFilter = false"
                        class="flex-1 px-4 py-3 bg-blue-500 dark:bg-pink-500 text-white rounded-xl font-medium hover:bg-blue-600 dark:hover:bg-pink-600 transition-colors">
                    Show Results
                </button>
            </div>
        </div>
    </div>
</div>
