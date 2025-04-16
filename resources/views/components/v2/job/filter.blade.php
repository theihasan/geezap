<div
    class="bg-[#1a1a3a] rounded-2xl p-6 border border-gray-700 mb-8 md:mb-0 md:col-span-1 col-span-full md:sticky md:top-6 h-fit"
    x-data="{
        activeFilters: {
            search: '{{ request('search') }}',
            source: '{{ request('source') }}',
            exclude_source: '{{ request('exclude_source') }}',
            country: '{{ request('country') }}',
            category: '{{ request('category') }}',
            remote: {{ request('remote') ? 'true' : 'false' }},
            types: '{{ request('types') }}'
        },
        sections: {
            basic: {{ request('search') || request('category') || request('remote') || (!request('search') && !request('category') && !request('remote') && !request('source') && !request('exclude_source') && !request('country') && !request('types')) ? 'true' : 'false' }},
            source: {{ request('source') || request('exclude_source') ? 'true' : 'false' }},
            location: {{ request('country') ? 'true' : 'false' }},
            jobType: {{ request('types') ? 'true' : 'false' }}
        },
        getActiveFilterCount() {
            let count = 0;
            if (this.activeFilters.search) count++;
            if (this.activeFilters.source) count++;
            if (this.activeFilters.exclude_source) count++;
            if (this.activeFilters.country) count++;
            if (this.activeFilters.category) count++;
            if (this.activeFilters.remote) count++;
            if (this.activeFilters.types) count++;
            return count;
        },
        clearAllFilters() {
            this.activeFilters = {
                search: '',
                source: '',
                exclude_source: '',
                country: '',
                category: '',
                remote: false,
                types: ''
            };

            document.querySelectorAll('.type-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });

            document.getElementById('types-hidden-input').value = '';

            document.getElementById('jobs-filter-form').submit();
        }
    }">
    <h3 class="text-xl font-ubuntu-bold text-white mb-4">
        Filter Jobs
        <span x-show="getActiveFilterCount() > 0"
              class="ml-2 px-2 py-0.5 text-sm bg-pink-500 text-white rounded-full"
              x-text="getActiveFilterCount()"></span>
    </h3>

    <form method="GET" action="{{ route('job.index') }}" class="space-y-4 font-ubuntu" id="jobs-filter-form">
        <!-- Basic Filters Section -->
        <div class="border-b border-gray-700 pb-4 mb-4">
            <button type="button"
                    @click="sections.basic = !sections.basic"
                    class="flex justify-between items-center w-full text-left text-white font-medium">
                <span>Basic Filters</span>
                <i class="las" :class="sections.basic ? 'la-angle-up' : 'la-angle-down'"></i>
            </button>

            <div x-show="sections.basic" x-transition class="space-y-4 mt-3">
                <!-- Search Filter -->
                <div>
                    <label class="text-gray-400 text-sm">Search Jobs</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by title..."
                           x-model="activeFilters.search"
                           class="w-full bg-[#12122b] border border-gray-700 text-gray-300 rounded-lg px-4 py-2 mt-2 focus:border-pink-500 focus:outline-none">
                </div>

                <!-- Job Category Filter -->
                <div>
                    <label class="text-gray-400 text-sm">Job Category</label>
                    <select name="category"
                            x-model="activeFilters.category"
                            class="w-full bg-[#12122b] border border-gray-700 text-gray-300 rounded-lg px-4 py-2 mt-2 focus:border-pink-500 focus:outline-none">
                        <option value="">All Categories</option>
                        @foreach (\App\Models\JobCategory::all() as $category)
                            <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Remote Job Filter -->
                <div>
                    <label class="flex items-center gap-3 text-gray-300 cursor-pointer">
                        <input type="checkbox" name="remote" value="1" @checked(request('remote'))
                        x-model="activeFilters.remote"
                               class="w-5 h-5 bg-[#12122b] text-pink-500 border border-gray-700 rounded-lg focus:ring-pink-500">
                        <span>Remote Only</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Source Filters Section -->
        <div class="border-b border-gray-700 pb-4 mb-4">
            <button type="button"
                    @click="sections.source = !sections.source"
                    class="flex justify-between items-center w-full text-left text-white font-medium">
                <span>Source Filters</span>
                <i class="las" :class="sections.source ? 'la-angle-up' : 'la-angle-down'"></i>
            </button>

            <div x-show="sections.source" x-transition class="space-y-4 mt-3">
                <!-- Source Filter -->
                <div>
                    <label class="text-gray-400 text-sm">Source</label>
                    <select name="source"
                            x-model="activeFilters.source"
                            class="w-full bg-[#12122b] border border-gray-700 text-gray-300 rounded-lg px-4 py-2 mt-2 focus:border-pink-500 focus:outline-none">
                        <option value="">All Sources</option>
                        @foreach(App\Models\JobListing::distinct()->pluck('publisher') as $publisher)
                            <option value="{{ $publisher }}" @selected(request('source') == $publisher)>
                                {{ $publisher }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Exclude Source Filter -->
                <div>
                    <label class="text-gray-400 text-sm">Exclude Source</label>
                    <select name="exclude_source"
                            x-model="activeFilters.exclude_source"
                            class="w-full bg-[#12122b] border border-gray-700 text-gray-300 rounded-lg px-4 py-2 mt-2 focus:border-pink-500 focus:outline-none">
                        <option value="">Don't Exclude Any</option>
                        @foreach(App\Models\JobListing::distinct()->pluck('publisher') as $publisher)
                            <option value="{{ $publisher }}" @selected(request('exclude_source') == $publisher)>
                                {{ $publisher }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Location Filters Section -->
        <div class="border-b border-gray-700 pb-4 mb-4">
            <button type="button"
                    @click="sections.location = !sections.location"
                    class="flex justify-between items-center w-full text-left text-white font-medium">
                <span>Location Filters</span>
                <i class="las" :class="sections.location ? 'la-angle-up' : 'la-angle-down'"></i>
            </button>

            <div x-show="sections.location" x-transition class="space-y-4 mt-3">
                <!-- Country Filter -->
                <div>
                    <label class="text-gray-400 text-sm">Country</label>
                    <select name="country"
                            x-model="activeFilters.country"
                            class="w-full bg-[#12122b] border border-gray-700 text-gray-300 rounded-lg px-4 py-2 mt-2 focus:border-pink-500 focus:outline-none">
                        <option value="">All Countries</option>
                        @php
                            $countryCodes = App\Models\JobListing::distinct()->whereNotNull('country')->pluck('country');
                            $countries = App\Models\Country::whereIn('code', $countryCodes)->get()->keyBy('code');
                        @endphp
                        @foreach($countryCodes as $countryCode)
                            <option value="{{ $countryCode }}" @selected(request('country') == $countryCode)>
                                {{ $countries[$countryCode]->name ?? $countryCode }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Job Type Filters Section -->
        <div class="border-b border-gray-700 pb-4 mb-4">
            <button type="button"
                    @click="sections.jobType = !sections.jobType"
                    class="flex justify-between items-center w-full text-left text-white font-medium">
                <span>Job Type Filters</span>
                <i class="las" :class="sections.jobType ? 'la-angle-up' : 'la-angle-down'"></i>
            </button>

            <div x-show="sections.jobType" x-transition class="space-y-4 mt-3">
                <!-- Job Types Filter -->
                <div>
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
            </div>
        </div>

        <div class="flex items-center justify-between mt-6">
            <button type="button"
                    class="px-4 py-2 text-gray-300 hover:text-white transition-colors"
                    @click="clearAllFilters()">
                Clear All
            </button>

            <button type="submit"
                    class="bg-gradient-to-r from-pink-500 to-purple-600 text-white py-2 px-6 rounded-lg hover:opacity-90 transition-opacity font-ubuntu-medium">
                Apply Filters
            </button>
        </div>
    </form>
</div>

