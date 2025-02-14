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
        <!-- Source Filter -->
        <div>
            <label class="text-gray-400 text-sm">Source</label>
            <select name="source"
                    class="w-full bg-[#12122b] border border-gray-700 text-gray-300 rounded-lg px-4 py-2 mt-2 focus:border-pink-500 focus:outline-none">
                <option value="">All Sources</option>
                @foreach(App\Models\JobListing::distinct()->pluck('publisher') as $publisher)
                    <option value="{{ $publisher }}" @selected(request('source') == $publisher)>
                        {{ $publisher }}
                    </option>
                @endforeach
            </select>
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
