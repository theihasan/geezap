@extends('v2.layouts.app')
@section('content')
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#12122b] to-[#1a1a3e] border-b border-gray-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl font-bold text-white mb-3 font-oxanium-bold">Job Preferences</h1>
                <p class="text-gray-300 text-lg font-ubuntu-regular">Customize your job recommendations and notification settings</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-6 py-4 rounded-xl mb-8 flex items-center gap-3">
                <i class="las la-check-circle text-xl"></i>
                <span class="font-ubuntu-medium">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('profile.preferences.update') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Left Column: Job Preferences -->
                <div class="space-y-8">
                    <!-- Job Categories -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
                                <i class="las la-briefcase text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 font-oxanium-bold">Job Categories</h2>
                                <p class="text-gray-500 text-sm">Select your preferred job categories</p>
                            </div>
                        </div>
                        
                        <div class="space-y-3 max-h-80 overflow-y-auto pr-2">
                            @php
                                $selectedCategories = $preferences->preferred_job_topics ?? [];
                                $showCount = 6;
                            @endphp
                            
                            @foreach($jobCategories as $index => $category)
                                <label class="group cursor-pointer block" 
                                       @if($index >= $showCount) style="display: none;" data-category-item @endif>
                                    <div class="flex items-center gap-4 p-4 bg-gray-50 hover:bg-blue-50 border-2 border-gray-200 rounded-xl transition-all duration-200 group-hover:shadow-md">
                                        <input type="checkbox" 
                                               name="preferred_job_topics[]" 
                                               value="{{ $category->id }}"
                                               {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}
                                               class="w-5 h-5 text-blue-500 border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                        <span class="text-gray-700 font-ubuntu-medium group-hover:text-blue-600 transition-colors">{{ $category->name }}</span>
                                    </div>
                                </label>
                            @endforeach
                            
                            @if(count($jobCategories) > $showCount)
                                <div class="text-center pt-4">
                                    <button type="button" 
                                            onclick="toggleCategoryVisibility()"
                                            id="categoryToggleBtn"
                                            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-ubuntu-medium transition-all duration-200 hover:shadow-lg">
                                        <span>Show More Categories</span>
                                        <i class="las la-chevron-down transition-transform" id="categoryToggleIcon"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Job Types -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-purple-500 rounded-xl flex items-center justify-center">
                                <i class="las la-clock text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 font-oxanium-bold">Job Types</h2>
                                <p class="text-gray-500 text-sm">Choose your preferred employment types</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach(['full-time' => 'Full Time', 'part-time' => 'Part Time', 'contract' => 'Contract', 'freelance' => 'Freelance'] as $value => $label)
                                <label class="group cursor-pointer">
                                    <div class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-purple-50 border-2 border-gray-200 rounded-xl transition-all duration-200 group-hover:shadow-md">
                                        <input type="checkbox" name="preferred_job_types[]" value="{{ $value }}" 
                                               {{ in_array($value, $preferences->preferred_job_types ?? []) ? 'checked' : '' }}
                                               class="w-5 h-5 text-purple-500 border-2 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                                        <span class="text-gray-700 font-ubuntu-medium group-hover:text-purple-600 transition-colors">{{ $label }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        
                        <!-- Remote Only Toggle -->
                        <div class="mt-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                        <i class="las la-home text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-900 font-ubuntu-bold text-sm">Remote Only</h3>
                                        <p class="text-gray-600 text-xs">Show only remote positions</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="remote_only" value="1" 
                                           {{ $preferences->remote_only ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-500 shadow-inner"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Preferred Regions -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                                <i class="las la-globe text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 font-oxanium-bold">Preferred Regions</h2>
                                <p class="text-gray-500 text-sm">Select your preferred work locations</p>
                            </div>
                        </div>
                        
                        <div class="space-y-3 max-h-80 overflow-y-auto pr-2">
                            @php
                                $selectedRegions = $preferences->preferred_regions ?? [];
                                $showCountRegions = 6;
                            @endphp
                            
                            @foreach($countries as $index => $country)
                                <label class="group cursor-pointer block"
                                       @if($index >= $showCountRegions) style="display: none;" data-region-item @endif>
                                    <div class="flex items-center gap-4 p-4 bg-gray-50 hover:bg-orange-50 border-2 border-gray-200 rounded-xl transition-all duration-200 group-hover:shadow-md">
                                        <input type="checkbox" 
                                               name="preferred_regions[]" 
                                               value="{{ $country->id }}"
                                               {{ in_array($country->id, $selectedRegions) ? 'checked' : '' }}
                                               class="w-5 h-5 text-orange-500 border-2 border-gray-300 rounded focus:ring-orange-500 focus:ring-2">
                                        <span class="text-gray-700 font-ubuntu-medium group-hover:text-orange-600 transition-colors">{{ $country->name }}</span>
                                    </div>
                                </label>
                            @endforeach
                            
                            @if(count($countries) > $showCountRegions)
                                <div class="text-center pt-4">
                                    <button type="button" 
                                            onclick="toggleRegionVisibility()"
                                            id="regionToggleBtn"
                                            class="inline-flex items-center gap-2 px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-xl font-ubuntu-medium transition-all duration-200 hover:shadow-lg">
                                        <span>Show More Regions</span>
                                        <i class="las la-chevron-down transition-transform" id="regionToggleIcon"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Email Settings -->
                <div class="space-y-8">
                    <!-- Email Notifications -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-pink-500 rounded-xl flex items-center justify-center">
                                <i class="las la-envelope text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 font-oxanium-bold">Email Settings</h2>
                                <p class="text-gray-500 text-sm">Configure your notification preferences</p>
                            </div>
                        </div>
                        
                        <!-- Email Frequency and Number of Emails Side by Side -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                            <!-- Email Frequency -->
                            <div>
                                <label class="block text-sm font-ubuntu-bold text-gray-700 mb-3">Email Frequency</label>
                                <div class="relative">
                                    <select name="email_frequency" class="w-full p-4 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-700 font-ubuntu-medium focus:border-pink-500 focus:bg-white transition-all appearance-none">
                                        <option value="daily" {{ ($preferences->email_frequency ?? 'weekly') == 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ ($preferences->email_frequency ?? 'weekly') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="monthly" {{ ($preferences->email_frequency ?? 'weekly') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="never" {{ ($preferences->email_frequency ?? 'weekly') == 'never' ? 'selected' : '' }}>Never</option>
                                    </select>
                                    <i class="las la-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                </div>
                            </div>
                            
                            <!-- Number of Emails per Period -->
                            <div>
                                <label class="block text-sm font-ubuntu-bold text-gray-700 mb-3">Emails per Period</label>
                                <div class="relative">
                                    <select name="emails_per_frequency" class="w-full p-4 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-700 font-ubuntu-medium focus:border-pink-500 focus:bg-white transition-all appearance-none">
                                        <option value="5" {{ ($preferences->emails_per_frequency ?? 5) == 5 ? 'selected' : '' }}>5 emails</option>
                                        <option value="10" {{ ($preferences->emails_per_frequency ?? 5) == 10 ? 'selected' : '' }}>10 emails</option>
                                        <option value="15" {{ ($preferences->emails_per_frequency ?? 5) == 15 ? 'selected' : '' }}>15 emails</option>
                                        <option value="20" {{ ($preferences->emails_per_frequency ?? 5) == 20 ? 'selected' : '' }}>20 emails</option>
                                    </select>
                                    <i class="las la-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Email Toggles -->
                        <div class="space-y-4">
                            <!-- Email Notifications -->
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                        <i class="las la-bell text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-900 font-ubuntu-bold text-sm">Email Notifications</h3>
                                        <p class="text-gray-600 text-xs">Receive email notifications</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="email_notifications_enabled" value="1" 
                                           {{ ($preferences->email_notifications_enabled ?? true) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-500 shadow-inner"></div>
                                </label>
                            </div>
                            
                            <!-- Job Alerts -->
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-violet-50 border border-purple-200 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                        <i class="las la-briefcase text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-900 font-ubuntu-bold text-sm">Job Alerts</h3>
                                        <p class="text-gray-600 text-xs">Get notified about new matching jobs</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="job_alerts_enabled" value="1" 
                                           {{ ($preferences->job_alerts_enabled ?? true) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-purple-500 shadow-inner"></div>
                                </label>
                            </div>
                            
                            <!-- Show Recommendations -->
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                                        <i class="las la-star text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-900 font-ubuntu-bold text-sm">Show Recommendations</h3>
                                        <p class="text-gray-600 text-xs">Display personalized job recommendations</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="show_recommendations" value="1" 
                                           {{ ($preferences->show_recommendations ?? true) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-500 shadow-inner"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Save Button -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                        <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-ubuntu-bold py-4 px-8 rounded-xl transition-all duration-200 transform hover:scale-[1.02] hover:shadow-xl flex items-center justify-center gap-3">
                            <i class="las la-save text-xl"></i>
                            <span class="text-lg">Save Preferences</span>
                        </button>
                        <p class="text-center text-gray-500 text-sm mt-4">Your preferences will be saved and applied to future job recommendations</p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- JavaScript for Show More/Less functionality -->
    <script>
        function toggleCategoryVisibility() {
            const items = document.querySelectorAll('[data-category-item]');
            const button = document.getElementById('categoryToggleBtn');
            const icon = document.getElementById('categoryToggleIcon');
            const isShowingMore = button.textContent.includes('Show Less');
            
            items.forEach(item => {
                if (isShowingMore) {
                    item.style.display = 'none';
                } else {
                    item.style.display = 'block';
                }
            });
            
            if (isShowingMore) {
                button.innerHTML = '<span>Show More Categories</span><i class="las la-chevron-down transition-transform" id="categoryToggleIcon"></i>';
            } else {
                button.innerHTML = '<span>Show Less Categories</span><i class="las la-chevron-up transition-transform" id="categoryToggleIcon"></i>';
            }
        }
        
        function toggleRegionVisibility() {
            const items = document.querySelectorAll('[data-region-item]');
            const button = document.getElementById('regionToggleBtn');
            const icon = document.getElementById('regionToggleIcon');
            const isShowingMore = button.textContent.includes('Show Less');
            
            items.forEach(item => {
                if (isShowingMore) {
                    item.style.display = 'none';
                } else {
                    item.style.display = 'block';
                }
            });
            
            if (isShowingMore) {
                button.innerHTML = '<span>Show More Regions</span><i class="las la-chevron-down transition-transform" id="regionToggleIcon"></i>';
            } else {
                button.innerHTML = '<span>Show Less Regions</span><i class="las la-chevron-up transition-transform" id="regionToggleIcon"></i>';
            }
        }
    </script>
@endsection