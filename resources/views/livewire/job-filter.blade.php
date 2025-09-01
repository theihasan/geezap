<div x-data="{
    sections: {
        basic: true,
        source: false,
        location: false,
        jobType: false
    }
}">
    <x-loading />
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Filter Sidebar -->
        <div class="bg-white dark:bg-[#1a1a3a] rounded-2xl p-6 border border-gray-200 dark:border-gray-700 mb-8 md:mb-0 md:col-span-1 col-span-full md:sticky md:top-6 h-fit">
            <h3 class="text-xl font-ubuntu-bold text-gray-900 dark:text-white mb-4">
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
        <div class="col-span-full md:col-span-3 relative">
            <!-- Improved Loading State for Filters -->
            <div wire:loading wire:target="search, source, exclude_source, country, category, remote, types, clearAllFilters"
                 class="absolute inset-0 bg-white/90 dark:bg-[#1a1a3a]/90 rounded-2xl border border-gray-200 dark:border-gray-700 flex items-center justify-center z-20">
                <div class="text-center">
                    <div class="inline-block h-16 w-16 animate-spin rounded-full border-4 border-solid border-blue-500 dark:border-purple-500 border-r-transparent motion-reduce:animate-[spin_1.5s_linear_infinite] mb-4"></div>
                    <p class="text-gray-700 dark:text-gray-300 text-lg font-medium">Loading results...</p>
                </div>
            </div>

            <!-- Results -->
            <div class="space-y-4">
                @forelse($jobs as $job)
                    <x-v2.job.card :job="$job"/>
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
</div>
