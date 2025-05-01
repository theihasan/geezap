<div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Filter Sidebar -->
        <div class="bg-[#1a1a3a] rounded-2xl p-6 border border-gray-700 mb-8 md:mb-0 md:col-span-1 col-span-full md:sticky md:top-6 h-fit">
            <h3 class="text-xl font-ubuntu-bold text-white mb-4">
                Filter Jobs
                @if($this->getActiveFilterCount() > 0)
                    <span class="ml-2 px-2 py-0.5 text-sm bg-pink-500 text-white rounded-full">
                        {{ $this->getActiveFilterCount() }}
                    </span>
                @endif
            </h3>

            <!-- Basic Filters Section -->
            <div class="border-b border-gray-700 pb-4 mb-4">
                <button type="button"
                        wire:click="$toggle('sections.basic')"
                        class="flex justify-between items-center w-full text-left text-white font-medium">
                    <span>Basic Filters</span>
                    <i class="las {{ $sections['basic'] ? 'la-angle-up' : 'la-angle-down' }}"></i>
                </button>

                @if($sections['basic'])
                    <div class="space-y-4 mt-3">
                        <!-- Search Filter -->
                        <div>
                            <label class="text-gray-400 text-sm">Search Jobs</label>
                            <input type="text" wire:model.live.debounce.300ms="search"
                                   placeholder="Search by title..."
                                   class="w-full bg-[#12122b] border border-gray-700 text-gray-300 rounded-lg px-4 py-2 mt-2 focus:border-pink-500 focus:outline-none">
                        </div>

                        <!-- Job Category Filter -->
                        <div>
                            <label class="text-gray-400 text-sm">Job Category</label>
                            <select wire:model.live="category"
                                    class="w-full bg-[#12122b] border border-gray-700 text-gray-300 rounded-lg px-4 py-2 mt-2 focus:border-pink-500 focus:outline-none">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Remote Job Filter -->
                        <div>
                            <label class="flex items-center gap-3 text-gray-300 cursor-pointer">
                                <input type="checkbox" wire:model.live="remote"
                                       class="w-5 h-5 bg-[#12122b] text-pink-500 border border-gray-700 rounded-lg focus:ring-pink-500">
                                <span>Remote Only</span>
                            </label>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Source Filters Section -->
            <div class="border-b border-gray-700 pb-4 mb-4">
                <button type="button"
                        wire:click="$toggle('sections.source')"
                        class="flex justify-between items-center w-full text-left text-white font-medium">
                    <span>Source Filters</span>
                    <i class="las {{ $sections['source'] ? 'la-angle-up' : 'la-angle-down' }}"></i>
                </button>

                @if($sections['source'])
                    <div class="space-y-4 mt-3">
                        <!-- Source Filter -->
                        <div>
                            <label class="text-gray-400 text-sm">Source</label>
                            <select wire:model.live="source"
                                    class="w-full bg-[#12122b] border border-gray-700 text-gray-300 rounded-lg px-4 py-2 mt-2 focus:border-pink-500 focus:outline-none">
                                <option value="">All Sources</option>
                                @foreach($publishers as $publisher)
                                    <option value="{{ $publisher }}">{{ $publisher }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Exclude Source Filter -->
                        <div>
                            <label class="text-gray-400 text-sm">Exclude Source</label>
                            <select wire:model.live="exclude_source"
                                    class="w-full bg-[#12122b] border border-gray-700 text-gray-300 rounded-lg px-4 py-2 mt-2 focus:border-pink-500 focus:outline-none">
                                <option value="">Don't Exclude Any</option>
                                @foreach($publishers as $publisher)
                                    <option value="{{ $publisher }}">{{ $publisher }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Location Filters Section -->
            <div class="border-b border-gray-700 pb-4 mb-4">
                <button type="button"
                        wire:click="$toggle('sections.location')"
                        class="flex justify-between items-center w-full text-left text-white font-medium">
                    <span>Location Filters</span>
                    <i class="las {{ $sections['location'] ? 'la-angle-up' : 'la-angle-down' }}"></i>
                </button>

                @if($sections['location'])
                    <div class="space-y-4 mt-3">
                        <!-- Country Filter -->
                        <div>
                            <label class="text-gray-400 text-sm">Country</label>
                            <select wire:model.live="country"
                                    class="w-full bg-[#12122b] border border-gray-700 text-gray-300 rounded-lg px-4 py-2 mt-2 focus:border-pink-500 focus:outline-none">
                                <option value="">All Countries</option>
                                @foreach($countries as $code => $country)
                                    <option value="{{ $code }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Job Type Filters Section -->
            <div class="border-b border-gray-700 pb-4 mb-4">
                <button type="button"
                        wire:click="$toggle('sections.jobType')"
                        class="flex justify-between items-center w-full text-left text-white font-medium">
                    <span>Job Type Filters</span>
                    <i class="las {{ $sections['jobType'] ? 'la-angle-up' : 'la-angle-down' }}"></i>
                </button>

                @if($sections['jobType'])
                    <div class="space-y-4 mt-3">
                        <div class="space-y-2">
                            @foreach ($jobTypes as $value => $label)
                                <label class="flex items-center gap-3 text-gray-300 cursor-pointer">
                                    <input type="checkbox" wire:model.live="types" value="{{ $value }}"
                                           class="w-5 h-5 bg-[#12122b] text-pink-500 border border-gray-700 rounded-lg focus:ring-pink-500">
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Clear Filters Button -->
            <div class="flex items-center justify-between mt-6">
                <button type="button"
                        wire:click="clearAllFilters"
                        class="px-4 py-2 text-gray-300 hover:text-white transition-colors">
                    Clear All
                </button>
            </div>
        </div>

        <!-- Job Listings -->
        <div class="col-span-full md:col-span-3">
            <!-- Loading State for Filters -->
            <div wire:loading wire:target="search, source, exclude_source, country, category, remote, types, clearAllFilters"
                 class="bg-[#1a1a3a] rounded-2xl border border-gray-700 p-6 text-center">
                <div class="inline-block h-12 w-12 animate-spin rounded-full border-4 border-solid border-purple-500 border-r-transparent motion-reduce:animate-[spin_1.5s_linear_infinite] mb-4"></div>
                <p class="text-gray-300">Loading results...</p>
            </div>

            <!-- Results -->
            <div wire:loading.remove wire:target="search, source, exclude_source, country, category, remote, types, clearAllFilters"
                 class="space-y-4">
                @forelse($jobs as $job)
                    <x-v2.job.card :job="$job"/>
                @empty
                    <div class="bg-[#1a1a3a] rounded-2xl border border-gray-700 p-6 text-center">
                        <p class="text-gray-400">No jobs found matching your criteria.</p>
                    </div>
                @endforelse

                <!-- Load More Button -->
                @if($hasMorePages)
                    <div class="mt-6 text-center">
                        <button wire:click="loadMore"
                                wire:loading.attr="disabled"
                                wire:target="loadMore"
                                class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors disabled:opacity-50">
                            <span wire:loading.remove wire:target="loadMore">Load More</span>
                            <span wire:loading wire:target="loadMore">
                        <div class="inline-block h-4 w-4 mr-2 animate-spin rounded-full border-2 border-solid border-white border-r-transparent"></div>
                        Loading...
                    </span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
