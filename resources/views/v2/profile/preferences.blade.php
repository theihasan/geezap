@extends('v2.layouts.app')
@section('content')
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-[#12122b] dark:to-[#1a1a3e] border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-3 font-oxanium-bold">Job Preferences</h1>
                <p class="text-gray-600 dark:text-gray-300 text-lg font-ubuntu-regular">Customize your job recommendations and notification settings</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/20 text-green-600 dark:text-green-400 px-6 py-4 rounded-xl mb-8 flex items-center gap-3">
                <i class="las la-check-circle text-xl"></i>
                <span class="font-ubuntu-medium">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('profile.preferences.update') }}" method="POST">
            @csrf
            
            <!-- Notification Toggle at Top Right -->
            <div class="flex justify-end mb-8">
                <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-xl inline-flex items-center gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                            <i class="las la-bell text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-gray-900 dark:text-white font-ubuntu-bold text-sm">Email Notifications</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-xs">Receive job alerts</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer ml-4">
                        <input type="checkbox" name="email_notifications_enabled" value="1" 
                               {{ ($preferences->email_notifications_enabled ?? true) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-500 shadow-inner"></div>
                    </label>
                </div>
            </div>
            
            <!-- Email Settings Row -->
            <div class="bg-white dark:bg-[#12122b] rounded-2xl p-8 shadow-lg border border-gray-100 dark:border-gray-800 mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-blue-500 dark:bg-pink-500 rounded-xl flex items-center justify-center">
                        <i class="las la-envelope text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white font-oxanium-bold">Email Settings</h2>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Configure your notification preferences</p>
                    </div>
                </div>
                
                <!-- Email Frequency and Number of Emails Side by Side -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Email Frequency -->
                    <div>
                        <label class="block text-sm font-ubuntu-bold text-gray-700 dark:text-gray-300 mb-3">Email Frequency</label>
                        <div class="relative">
                            <select name="email_frequency" class="w-full p-4 bg-gray-50 dark:bg-white/5 border-2 border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-white font-ubuntu-medium focus:border-blue-500 dark:focus:border-pink-500 focus:bg-white dark:focus:bg-white/10 transition-all appearance-none">
                                <option value="daily" {{ ($preferences->email_frequency ?? 'weekly') == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ ($preferences->email_frequency ?? 'weekly') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ ($preferences->email_frequency ?? 'weekly') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            </select>
                            <i class="las la-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                    
                    <!-- Number of Emails per Period -->
                    <div>
                        <label class="block text-sm font-ubuntu-bold text-gray-700 dark:text-gray-300 mb-3">
                            Emails per Period
                            <span class="ml-2 px-2 py-1 text-xs bg-blue-500 dark:bg-pink-500 text-white font-bold rounded-full animate-pulse">Coming Soon</span>
                        </label>
                        <div class="relative">
                            <select name="emails_per_frequency" class="w-full p-4 bg-gray-50 dark:bg-white/5 border-2 border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-white font-ubuntu-medium focus:border-blue-500 dark:focus:border-pink-500 focus:bg-white dark:focus:bg-white/10 transition-all appearance-none" disabled>
                                <option value="1" {{ ($preferences->emails_per_frequency ?? 1) == 1 ? 'selected' : '' }}>1 email</option>
                                <option value="2" {{ ($preferences->emails_per_frequency ?? 1) == 2 ? 'selected' : '' }}>2 emails</option>
                                <option value="5" {{ ($preferences->emails_per_frequency ?? 1) == 5 ? 'selected' : '' }}>5 emails</option>
                            </select>
                            <i class="las la-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Job Preferences Row (3 columns) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Job Categories -->
                <div class="bg-white dark:bg-[#12122b] rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-800">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-blue-500 dark:bg-pink-500 rounded-xl flex items-center justify-center">
                            <i class="las la-briefcase text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white font-oxanium-bold">Job Categories</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-xs">Select preferred categories</p>
                        </div>
                    </div>
                    
                    <!-- Search Box for Categories -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" 
                                   id="categorySearch" 
                                   placeholder="Search categories..." 
                                   class="w-full p-3 bg-gray-50 dark:bg-white/5 border-2 border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-white font-ubuntu-medium focus:border-blue-500 dark:focus:border-pink-500 focus:bg-white dark:focus:bg-white/10 transition-all pl-10"
                                   oninput="filterCategories()">
                            <i class="las la-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
                        @php
                            $selectedCategories = $preferences->preferred_job_categories_id ?? [];
                        @endphp
                        
                        @foreach($jobCategories as $index => $category)
                            <label class="group cursor-pointer block category-item" 
                                   data-category-name="{{ strtolower($category->name) }}">
                                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-white/5 hover:bg-blue-50 dark:hover:bg-blue-900/10 border-2 border-gray-200 dark:border-gray-700 rounded-xl transition-all duration-200 group-hover:shadow-md">
                                    <input type="checkbox" 
                                           name="preferred_job_categories_id[]" 
                                           value="{{ $category->id }}"
                                           {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-500 border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                    <span class="text-gray-700 dark:text-gray-300 text-sm font-ubuntu-medium group-hover:text-blue-600 transition-colors">{{ $category->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Job Types -->
                <div class="bg-white dark:bg-[#12122b] rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-800">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-purple-500 dark:bg-pink-500 rounded-xl flex items-center justify-center">
                            <i class="las la-clock text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white font-oxanium-bold">Job Types</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-xs">Choose employment types</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
                        @foreach(['full-time' => 'Full Time', 'part-time' => 'Part Time', 'contract' => 'Contract', 'freelance' => 'Freelance'] as $value => $label)
                            <label class="group cursor-pointer block">
                                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-white/5 hover:bg-purple-50 dark:hover:bg-purple-900/10 border-2 border-gray-200 dark:border-gray-700 rounded-xl transition-all duration-200 group-hover:shadow-md">
                                    <input type="checkbox" name="preferred_job_types[]" value="{{ $value }}" 
                                           {{ in_array($value, $preferences->preferred_job_types ?? []) ? 'checked' : '' }}
                                           class="w-4 h-4 text-purple-500 border-2 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                                    <span class="text-gray-700 dark:text-gray-300 text-sm font-ubuntu-medium group-hover:text-purple-600 transition-colors">{{ $label }}</span>
                                </div>
                            </label>
                        @endforeach
                        
                        <!-- Remote Only Toggle -->
                        <label class="group cursor-pointer block mt-3">
                            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-xl">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-green-500 dark:bg-emerald-500 rounded-lg flex items-center justify-center">
                                        <i class="las la-home text-white text-sm"></i>
                                    </div>
                                    <span class="text-gray-900 dark:text-white font-ubuntu-bold text-sm">Remote Only</span>
                                </div>
                                <input type="checkbox" name="remote_only" value="1" 
                                       {{ ($preferences->remote_only ?? false) ? 'checked' : '' }}
                                       class="w-4 h-4 text-green-500 border-2 border-gray-300 rounded focus:ring-green-500 focus:ring-2">
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Preferred Regions -->
                <div class="bg-white dark:bg-[#12122b] rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-800">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-orange-500 dark:bg-pink-500 rounded-xl flex items-center justify-center">
                            <i class="las la-globe text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white font-oxanium-bold">Regions</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-xs">Select work locations</p>
                        </div>
                    </div>
                    
                    <!-- Search Box for Regions -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" 
                                   id="regionSearch" 
                                   placeholder="Search regions..." 
                                   class="w-full p-3 bg-gray-50 dark:bg-white/5 border-2 border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-white font-ubuntu-medium focus:border-orange-500 dark:focus:border-pink-500 focus:bg-white dark:focus:bg-white/10 transition-all pl-10"
                                   oninput="filterRegions()">
                            <i class="las la-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
                        @php
                            $selectedRegions = $preferences->preferred_regions_id ?? [];
                        @endphp
                        
                        @foreach($countries as $index => $country)
                            <label class="group cursor-pointer block region-item"
                                   data-region-name="{{ strtolower($country->name) }}">
                                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-white/5 hover:bg-orange-50 dark:hover:bg-orange-900/10 border-2 border-gray-200 dark:border-gray-700 rounded-xl transition-all duration-200 group-hover:shadow-md">
                                    <input type="checkbox" 
                                           name="preferred_regions_id[]" 
                                           value="{{ $country->id }}"
                                           {{ in_array($country->id, $selectedRegions) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-500 border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                    <span class="text-gray-700 dark:text-gray-300 text-sm font-ubuntu-medium group-hover:text-orange-600 transition-colors">{{ $country->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Save Button -->
            <div class="flex justify-end mt-8">
                <button type="submit" class="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-ubuntu-bold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] hover:shadow-xl flex items-center justify-center gap-2">
                    <i class="las la-save"></i>
                    <span>Save Preferences</span>
                </button>
            </div>
        </form>
    </div>

    <!-- JavaScript for search functionality -->
    <script>
        function filterCategories() {
            const searchText = document.getElementById('categorySearch').value.toLowerCase();
            const items = document.querySelectorAll('.category-item');
            
            items.forEach(item => {
                const categoryName = item.getAttribute('data-category-name');
                
                if (searchText === '' || categoryName.includes(searchText)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
        
        function filterRegions() {
            const searchText = document.getElementById('regionSearch').value.toLowerCase();
            const items = document.querySelectorAll('.region-item');
            
            items.forEach(item => {
                const regionName = item.getAttribute('data-region-name');
                
                if (searchText === '' || regionName.includes(searchText)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
@endsection