@extends('v2.layouts.app')
@section('content')
    <!-- Header -->
    <div class="bg-[#12122b] border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
            <div class="text-center">
                <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2 font-oxanium-bold">Job Preferences</h1>
                <p class="text-gray-400 font-ubuntu-regular">Customize your job recommendations and notification settings</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12" x-data="preferencesData()">
        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.preferences.update') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Column 1: Categories & Regions -->
                <div class="space-y-6">
                    <!-- Job Categories -->
                    <div class="bg-[#12122b] rounded-2xl p-6 border border-gray-800">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-briefcase text-pink-500"></i>
                            Job Categories
                        </h2>
                        <div class="space-y-2 max-h-80 overflow-y-auto">
                            <template x-for="(category, index) in visibleCategories" :key="category.id">
                                <label class="relative cursor-pointer block">
                                    <input type="checkbox" 
                                           name="preferred_job_topics[]" 
                                           :value="category.id"
                                           x-model="selectedCategories"
                                           class="sr-only peer">
                                    <div class="p-3 bg-white/5 hover:bg-white/10 border border-gray-700 peer-checked:border-pink-500 peer-checked:bg-pink-500/10 rounded-lg transition-all">
                                        <div class="text-white font-ubuntu-medium text-sm" x-text="category.name"></div>
                                    </div>
                                </label>
                            </template>
                            
                            <!-- Show More/Less Button -->
                            <div class="text-center pt-2" x-show="allCategories.length > 5">
                                <button type="button" 
                                        @click="toggleCategoriesView()"
                                        class="text-pink-500 hover:text-pink-400 text-sm font-ubuntu-medium transition-colors">
                                    <span x-show="!showAllCategories && visibleCategories.length < allCategories.length">Show More Categories</span>
                                    <span x-show="showAllCategories || visibleCategories.length >= allCategories.length">Show Less</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Regions -->
                    <div class="bg-[#12122b] rounded-2xl p-6 border border-gray-800">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-globe text-pink-500"></i>
                            Preferred Regions
                        </h2>
                        <div class="space-y-2 max-h-80 overflow-y-auto">
                            <template x-for="(country, index) in visibleCountries" :key="country.id">
                                <label class="relative cursor-pointer block">
                                    <input type="checkbox" 
                                           name="preferred_regions[]" 
                                           :value="country.id"
                                           x-model="selectedRegions"
                                           class="sr-only peer">
                                    <div class="p-3 bg-white/5 hover:bg-white/10 border border-gray-700 peer-checked:border-pink-500 peer-checked:bg-pink-500/10 rounded-lg transition-all">
                                        <div class="text-white font-ubuntu-medium text-sm" x-text="country.name"></div>
                                    </div>
                                </label>
                            </template>
                            
                            <!-- Show More/Less Button -->
                            <div class="text-center pt-2" x-show="allCountries.length > 5">
                                <button type="button" 
                                        @click="toggleCountriesView()"
                                        class="text-pink-500 hover:text-pink-400 text-sm font-ubuntu-medium transition-colors">
                                    <span x-show="!showAllCountries && visibleCountries.length < allCountries.length">Show More Regions</span>
                                    <span x-show="showAllCountries || visibleCountries.length >= allCountries.length">Show Less</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column 2: Job Types, Salary & Settings -->
                <div class="space-y-6">
                    <!-- Job Types & Remote -->
                    <div class="bg-[#12122b] rounded-2xl p-6 border border-gray-800">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-clock text-pink-500"></i>
                            Job Type
                        </h2>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 gap-3">
                                @foreach(['full-time' => 'Full Time', 'part-time' => 'Part Time', 'contract' => 'Contract', 'freelance' => 'Freelance'] as $value => $label)
                                    <label class="relative cursor-pointer">
                                        <input type="checkbox" name="preferred_job_types[]" value="{{ $value }}" 
                                               {{ in_array($value, $preferences->preferred_job_types ?? []) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="p-3 bg-white/5 hover:bg-white/10 border border-gray-700 peer-checked:border-pink-500 peer-checked:bg-pink-500/10 rounded-lg transition-all">
                                            <div class="text-white font-ubuntu-medium text-sm">{{ $label }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg border border-gray-700">
                                <div>
                                    <h3 class="text-white font-ubuntu-medium text-sm">Remote Only</h3>
                                    <p class="text-gray-400 text-xs">Show only remote positions</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="remote_only" value="1" 
                                           {{ $preferences->remote_only ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Salary Range -->
                    <div class="bg-[#12122b] rounded-2xl p-6 border border-gray-800">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-dollar-sign text-pink-500"></i>
                            Salary Range
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Min Salary</label>
                                <input type="number" name="min_salary" value="{{ $preferences->min_salary ?? '' }}"
                                       placeholder="e.g. 50000"
                                       class="w-full bg-white/5 border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:border-pink-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Max Salary</label>
                                <input type="number" name="max_salary" value="{{ $preferences->max_salary ?? '' }}"
                                       placeholder="e.g. 100000"
                                       class="w-full bg-white/5 border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:border-pink-500 focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-pink-500 to-purple-600 hover:opacity-90 text-white px-6 py-4 rounded-xl transition-all flex items-center justify-center gap-2 font-oxanium-semibold text-lg">
                        <i class="las la-save"></i>
                        Save Preferences
                    </button>
                </div>

                <!-- Column 3: Email Notifications & Recommended Jobs -->
                <div class="space-y-6">
                    <!-- Email Notifications -->
                    <div class="bg-[#12122b] rounded-2xl p-6 border border-gray-800">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-envelope text-pink-500"></i>
                            Email Notifications
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Email Frequency</label>
                                <select name="email_frequency" class="w-full bg-white/5 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-pink-500 focus:outline-none">
                                    <option value="daily" {{ ($preferences->email_frequency ?? 'weekly') === 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ ($preferences->email_frequency ?? 'weekly') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ ($preferences->email_frequency ?? 'weekly') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Number of Emails per Period</label>
                                <select name="emails_per_frequency" class="w-full bg-white/5 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-pink-500 focus:outline-none">
                                    <option value="1" {{ ($preferences->emails_per_frequency ?? 5) == 1 ? 'selected' : '' }}>1 email</option>
                                    <option value="3" {{ ($preferences->emails_per_frequency ?? 5) == 3 ? 'selected' : '' }}>3 emails</option>
                                    <option value="5" {{ ($preferences->emails_per_frequency ?? 5) == 5 ? 'selected' : '' }}>5 emails</option>
                                    <option value="10" {{ ($preferences->emails_per_frequency ?? 5) == 10 ? 'selected' : '' }}>10 emails</option>
                                    <option value="15" {{ ($preferences->emails_per_frequency ?? 5) == 15 ? 'selected' : '' }}>15 emails</option>
                                    <option value="20" {{ ($preferences->emails_per_frequency ?? 5) == 20 ? 'selected' : '' }}>20 emails</option>
                                </select>
                                <p class="text-gray-400 text-xs mt-1">Maximum number of job emails you want to receive per frequency period</p>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg border border-gray-700">
                                    <div>
                                        <h3 class="text-white font-ubuntu-medium text-sm">Email Notifications</h3>
                                        <p class="text-gray-400 text-xs">Receive email notifications</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="email_notifications_enabled" value="1" 
                                               {{ ($preferences->email_notifications_enabled ?? true) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                                    </label>
                                </div>
                                
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg border border-gray-700">
                                    <div>
                                        <h3 class="text-white font-ubuntu-medium text-sm">Job Alerts</h3>
                                        <p class="text-gray-400 text-xs">Get notified about new matching jobs</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="job_alerts_enabled" value="1" 
                                               {{ ($preferences->job_alerts_enabled ?? true) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                                    </label>
                                </div>
                                
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg border border-gray-700">
                                    <div>
                                        <h3 class="text-white font-ubuntu-medium text-sm">Show Recommendations</h3>
                                        <p class="text-gray-400 text-xs">Display personalized job recommendations</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="show_recommendations" value="1" 
                                               {{ ($preferences->show_recommendations ?? true) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recommended Jobs -->
                    <div class="bg-[#12122b] rounded-2xl p-6 border border-gray-800">
                        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-star text-pink-500"></i>
                            Recommended Jobs
                        </h2>
                        
                        @if($recommendedJobs && $recommendedJobs->count() > 0)
                            <div class="space-y-3 max-h-64 overflow-y-auto">
                                @foreach($recommendedJobs as $job)
                                    <div class="bg-white/5 hover:bg-white/10 rounded-lg p-4 transition-all border border-gray-700 hover:border-pink-500/30">
                                        <h3 class="text-white font-ubuntu-medium text-sm mb-2 line-clamp-2">
                                            <a href="{{ route('job.show', $job->slug) }}" class="hover:text-pink-500 transition-colors">
                                                {{ $job->title }}
                                            </a>
                                        </h3>
                                        <p class="text-gray-400 text-xs mb-2">{{ $job->company }}</p>
                                        <div class="flex items-center justify-between text-xs">
                                            <div class="flex items-center gap-2 text-gray-400">
                                                @if($job->country)
                                                    <span class="flex items-center gap-1">
                                                        <i class="las la-map-marker"></i>
                                                        {{ $job->country }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if($job->is_remote)
                                                <span class="bg-green-500/10 text-green-400 px-2 py-1 rounded">
                                                    Remote
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="las la-search text-4xl text-gray-600 mb-4"></i>
                                <p class="text-gray-400 mb-4">Set your preferences above to see personalized job recommendations.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function preferencesData() {
            return {
                // Categories data
                allCategories: @json($jobCategories),
                selectedCategories: @json($preferences->preferred_job_topics ?? []),
                showAllCategories: false,
                categoriesDisplayCount: 5,
                
                // Countries data
                allCountries: @json($countries),
                selectedRegions: @json($preferences->preferred_regions ?? []),
                showAllCountries: false,
                countriesDisplayCount: 5,
                
                get visibleCategories() {
                    if (this.showAllCategories) {
                        return this.allCategories;
                    }
                    return this.allCategories.slice(0, this.categoriesDisplayCount);
                },
                
                get visibleCountries() {
                    if (this.showAllCountries) {
                        return this.allCountries;
                    }
                    return this.allCountries.slice(0, this.countriesDisplayCount);
                },
                
                toggleCategoriesView() {
                    if (this.showAllCategories) {
                        this.showAllCategories = false;
                        this.categoriesDisplayCount = 5;
                    } else {
                        if (this.categoriesDisplayCount >= this.allCategories.length) {
                            this.showAllCategories = false;
                            this.categoriesDisplayCount = 5;
                        } else {
                            this.categoriesDisplayCount += 5;
                            if (this.categoriesDisplayCount >= this.allCategories.length) {
                                this.showAllCategories = true;
                            }
                        }
                    }
                },
                
                toggleCountriesView() {
                    if (this.showAllCountries) {
                        this.showAllCountries = false;
                        this.countriesDisplayCount = 5;
                    } else {
                        if (this.countriesDisplayCount >= this.allCountries.length) {
                            this.showAllCountries = false;
                            this.countriesDisplayCount = 5;
                        } else {
                            this.countriesDisplayCount += 5;
                            if (this.countriesDisplayCount >= this.allCountries.length) {
                                this.showAllCountries = true;
                            }
                        }
                    }
                }
            }
        }
    </script>
@endsection