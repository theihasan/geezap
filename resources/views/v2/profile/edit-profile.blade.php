@extends('v2.layouts.app')
@section('content')
    <!-- Header Section -->
    <div class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                <!-- Header Info -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                    <!-- Profile Image -->
                    <div class="relative group">
                        <img src="{{asset('assets/images/profile.jpg')}}" alt="{{ auth()->user()->name }}"
                             class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl object-cover border-3 border-white dark:border-gray-700 shadow-lg">
                        <div class="absolute inset-0 bg-black/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button class="text-white hover:text-blue-400 transition-colors">
                                <i class="las la-camera text-2xl"></i>
                            </button>
                        </div>
                        <!-- Upload indicator -->
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center shadow-lg">
                            <i class="las la-edit text-sm"></i>
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="text-center sm:text-left">
                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-2 font-oxanium-bold">
                            Edit Profile
                        </h1>
                        <p class="text-lg text-gray-600 dark:text-gray-400">
                            Update your personal information and professional details
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:border-blue-500 dark:hover:border-pink-500 transition-all">
                        <i class="las la-eye text-lg"></i>
                        <span class="hidden sm:inline">View Profile</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        @if (session('status'))
            <div class="mb-8 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 text-emerald-700 dark:text-emerald-400 px-6 py-4 rounded-2xl">
                <div class="flex items-center gap-3">
                    <i class="las la-check-circle text-xl"></i>
                    <span>{{ session('status') }}</span>
                </div>
            </div>
        @endif

        <!-- Navigation Tabs -->
        <div class="mb-8">
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-2 border border-gray-200 dark:border-gray-700">
                <nav class="flex space-x-1" x-data="{ activeTab: 'personal' }">
                    <button @click="activeTab = 'personal'" 
                            :class="activeTab === 'personal' ? 'bg-blue-500 text-white shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                            class="flex-1 py-3 px-4 rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                        <i class="las la-user"></i>
                        <span class="hidden sm:inline">Personal Info</span>
                    </button>
                    <button @click="activeTab = 'professional'" 
                            :class="activeTab === 'professional' ? 'bg-blue-500 text-white shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                            class="flex-1 py-3 px-4 rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                        <i class="las la-briefcase"></i>
                        <span class="hidden sm:inline">Professional</span>
                    </button>
                    <button @click="activeTab = 'social'" 
                            :class="activeTab === 'social' ? 'bg-blue-500 text-white shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                            class="flex-1 py-3 px-4 rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                        <i class="las la-share-alt"></i>
                        <span class="hidden sm:inline">Social</span>
                    </button>
                    <button @click="activeTab = 'security'" 
                            :class="activeTab === 'security' ? 'bg-blue-500 text-white shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                            class="flex-1 py-3 px-4 rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                        <i class="las la-lock"></i>
                        <span class="hidden sm:inline">Security</span>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        <div x-data="{ activeTab: 'personal' }">
            <!-- Personal Information Tab -->
            <div x-show="activeTab === 'personal'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Form -->
                    <div class="lg:col-span-2">
                        <form action="{{ route('personal-info.update') }}" method="POST" class="bg-white dark:bg-gray-900 rounded-3xl p-8 border border-gray-200 dark:border-gray-700 shadow-sm">
                            @csrf
                            <div class="flex items-center gap-3 mb-8">
                                <div class="w-12 h-12 bg-blue-500/10 dark:bg-blue-500/20 rounded-xl flex items-center justify-center">
                                    <i class="las la-user-edit text-blue-500 text-xl"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Personal Information</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Full Name -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ auth()->user()->name }}"
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all">
                                    @error('name') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                                </div>

                                <!-- Email -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" value="{{ auth()->user()->email }}" disabled
                                           class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-500 dark:text-gray-400 cursor-not-allowed">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Email cannot be changed for security reasons</p>
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Phone Number</label>
                                    <input type="tel" name="phone" value="{{ auth()->user()->phone }}"
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all">
                                    @error('phone') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                                </div>

                                <!-- Date of Birth -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Date of Birth</label>
                                    <input type="date" name="dob" value="{{ auth()->user()->dob?->format('Y-m-d') }}"
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all">
                                    @error('dob') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                                </div>

                                <!-- Occupation -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Current Occupation</label>
                                    <input type="text" name="occupation" value="{{ auth()->user()->occupation }}"
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all"
                                           placeholder="e.g. Software Developer, Marketing Manager">
                                    @error('occupation') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                                </div>

                                <!-- Location -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">State/Province</label>
                                    <input type="text" name="state" value="{{ auth()->user()->state }}"
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all">
                                    @error('state') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Country</label>
                                    <input type="text" name="country" value="{{ auth()->user()->country }}"
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all">
                                    @error('country') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                                </div>

                                <!-- Address -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Full Address</label>
                                    <textarea name="address" rows="3"
                                              class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all resize-none"
                                              placeholder="Enter your complete address">{{ auth()->user()->address }}</textarea>
                                    @error('address') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                                </div>

                                <!-- Timezone -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Timezone</label>
                                    <select name="timezone"
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all">
                                        @foreach($timezones as $timezone)
                                            <option value="{{ $timezone->value }}"
                                                    @if(auth()->user()->timezone === $timezone->value) selected @endif>
                                                {{ $timezone->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('timezone') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                                </div>

                                <!-- Bio -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Bio</label>
                                    <textarea name="bio" rows="4"
                                              class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all resize-none"
                                              placeholder="Tell us about yourself, your interests, and professional background">{{ auth()->user()->bio }}</textarea>
                                    @error('bio') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <button type="submit"
                                        class="w-full bg-gradient-to-r from-blue-500 to-purple-600 dark:from-pink-500 dark:to-purple-600 text-white py-3 px-6 rounded-xl font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                                    Update Personal Information
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Profile Tips Sidebar -->
                    <div class="space-y-6">
                        <!-- Profile Completion -->
                        <div class="bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-700/50">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i class="las la-chart-pie text-blue-500"></i>
                                Profile Strength
                            </h3>
                            @php
                                $completedFields = 0;
                                $totalFields = 8;
                                if(auth()->user()->name) $completedFields++;
                                if(auth()->user()->email) $completedFields++;
                                if(auth()->user()->phone) $completedFields++;
                                if(auth()->user()->occupation) $completedFields++;
                                if(auth()->user()->bio) $completedFields++;
                                if(auth()->user()->address) $completedFields++;
                                if(auth()->user()->experience) $completedFields++;
                                if(auth()->user()->website || auth()->user()->github || auth()->user()->linkedin) $completedFields++;
                                $completionPercentage = round(($completedFields / $totalFields) * 100);
                            @endphp
                            <div class="mb-4">
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $completionPercentage }}% Complete</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $completedFields }}/{{ $totalFields }}</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-700"
                                         style="width: {{ $completionPercentage }}%"></div>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                A complete profile increases your visibility to potential employers by up to 40%
                            </p>
                        </div>

                        <!-- Tips -->
                        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i class="las la-lightbulb text-yellow-500"></i>
                                Profile Tips
                            </h3>
                            <div class="space-y-4">
                                <div class="flex gap-3">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Use a professional photo for better first impressions</p>
                                </div>
                                <div class="flex gap-3">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Write a compelling bio that highlights your strengths</p>
                                </div>
                                <div class="flex gap-3">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Keep your contact information up to date</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Information Tab -->
            <div x-show="activeTab === 'professional'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Experience Form -->
                    <form action="{{ route('experience.update') }}" method="POST" class="bg-white dark:bg-gray-900 rounded-3xl p-8 border border-gray-200 dark:border-gray-700 shadow-sm">
                        @csrf
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-purple-500/10 dark:bg-purple-500/20 rounded-xl flex items-center justify-center">
                                    <i class="las la-briefcase text-purple-500 text-xl"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Work Experience</h2>
                            </div>
                            <button type="button" id="add-experience" 
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500/10 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 rounded-xl hover:bg-blue-500/20 dark:hover:bg-blue-500/30 transition-colors">
                                <i class="las la-plus"></i>
                                Add Experience
                            </button>
                        </div>

                        <div id="experience-container" class="space-y-6">
                            @if(!empty($experiences['position']))
                                @foreach($experiences['position'] as $index => $position)
                                    <div class="experience-form bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-600 rounded-2xl p-6 relative">
                                        <button type="button" class="remove-experience absolute -top-3 -right-3 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors shadow-lg">
                                            <i class="las la-times"></i>
                                        </button>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                                    Position <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="position[]" value="{{ $position }}"
                                                       class="w-full px-4 py-3 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                                    Company <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="company_name[]" value="{{ $experiences['company_name'][$index] }}"
                                                       class="w-full px-4 py-3 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all">
                                            </div>
                                        </div>

                                        <div class="mb-6">
                                            <label class="flex items-center gap-3 p-4 bg-white dark:bg-gray-700/50 rounded-xl cursor-pointer">
                                                <input type="checkbox" name="currently_working[]" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                                    {{ isset($experiences['currently_working'][$index]) && $experiences['currently_working'][$index] ? 'checked' : '' }}>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Currently working here</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">Check if this is your current position</div>
                                                </div>
                                            </label>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Start Date</label>
                                                <input type="date" name="start_date[]" value="{{ $experiences['start_date'][$index] ?? '' }}"
                                                       class="w-full px-4 py-3 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">End Date</label>
                                                <input type="date" name="end_date[]" value="{{ $experiences['end_date'][$index] ?? '' }}"
                                                       class="w-full px-4 py-3 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all"
                                                    {{ isset($experiences['currently_working'][$index]) && $experiences['currently_working'][$index] ? 'disabled' : '' }}>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Description</label>
                                            <textarea name="description[]" rows="3"
                                                      class="w-full px-4 py-3 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all resize-none"
                                                      placeholder="Describe your role, responsibilities, and achievements">{{ $experiences['description'][$index] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-purple-500 to-purple-600 dark:from-purple-500 dark:to-purple-600 text-white py-3 px-6 rounded-xl font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                                Update Experience
                            </button>
                        </div>
                    </form>

                    <!-- Skills Form -->
                    <form action="{{ route('skill.update') }}" method="POST" class="bg-white dark:bg-gray-900 rounded-3xl p-8 border border-gray-200 dark:border-gray-700 shadow-sm h-fit">
                        @csrf
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-green-500/10 dark:bg-green-500/20 rounded-xl flex items-center justify-center">
                                    <i class="las la-tools text-green-500 text-xl"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Skills</h2>
                            </div>
                            <button type="button" id="add-skill"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-500/10 dark:bg-green-500/20 text-green-600 dark:text-green-400 rounded-xl hover:bg-green-500/20 dark:hover:bg-green-500/30 transition-colors">
                                <i class="las la-plus"></i>
                                Add Skill
                            </button>
                        </div>

                        <div id="skills-container" class="space-y-6">
                            @if(!empty($skills['skill']))
                                @foreach($skills['skill'] as $index => $skill)
                                    <div class="skill-entry bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-600 rounded-2xl p-6 relative">
                                        <button type="button" class="remove-skill absolute -top-3 -right-3 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors shadow-lg">
                                            <i class="las la-times"></i>
                                        </button>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Skill Name</label>
                                                <input type="text" name="skill[]" value="{{ $skill }}"
                                                       class="w-full px-4 py-3 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all"
                                                       placeholder="e.g. JavaScript, Project Management">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Proficiency Level</label>
                                                <select name="skill_level[]"
                                                        class="w-full px-4 py-3 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all">
                                                    @foreach([
                                                        App\Enums\SkillProficiency::BEGINNER->value => 'Beginner',
                                                        App\Enums\SkillProficiency::INTERMEDIATE->value => 'Intermediate',
                                                        App\Enums\SkillProficiency::PROFICIENT->value => 'Proficient'
                                                    ] as $value => $label)
                                                        <option value="{{ $value }}"
                                                                @if(($skills['skill_level'][$index] ?? '') == $value) selected @endif>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-green-500 to-green-600 dark:from-green-500 dark:to-green-600 text-white py-3 px-6 rounded-xl font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                                Update Skills
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Social Media Tab -->
            <div x-show="activeTab === 'social'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                <div class="max-w-2xl mx-auto">
                    <form action="{{ route('social-media-info.update') }}" method="POST" class="bg-white dark:bg-gray-900 rounded-3xl p-8 border border-gray-200 dark:border-gray-700 shadow-sm">
                        @csrf
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-12 h-12 bg-pink-500/10 dark:bg-pink-500/20 rounded-xl flex items-center justify-center">
                                <i class="las la-share-alt text-pink-500 text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Social Media Links</h2>
                                <p class="text-gray-600 dark:text-gray-400">Connect your social profiles to build credibility</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            @foreach(\App\Enums\SocialProvider::cases() as $provider)
                                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 capitalize">
                                        {{ $provider->value }}
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="lab la-{{ $provider->value }} text-xl 
                                                @if($provider->value === 'github') text-gray-800 dark:text-gray-300
                                                @elseif($provider->value === 'linkedin') text-blue-600
                                                @elseif($provider->value === 'twitter') text-blue-400
                                                @elseif($provider->value === 'facebook') text-blue-500
                                                @else text-gray-600 dark:text-gray-400 @endif"></i>
                                        </div>
                                        <input type="text" name="{{ $provider->value }}" value="{{ auth()->user()->{$provider->value} }}"
                                               class="w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all"
                                               placeholder="Enter your {{ $provider->value }} profile URL">
                                        @error($provider->value)
                                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-pink-500 to-pink-600 dark:from-pink-500 dark:to-pink-600 text-white py-3 px-6 rounded-xl font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                                Update Social Links
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security Tab -->
            <div x-show="activeTab === 'security'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                <div class="max-w-2xl mx-auto">
                    <form action="{{ route('userpassword.update') }}" method="POST" class="bg-white dark:bg-gray-900 rounded-3xl p-8 border border-gray-200 dark:border-gray-700 shadow-sm">
                        @csrf
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-12 h-12 bg-red-500/10 dark:bg-red-500/20 rounded-xl flex items-center justify-center">
                                <i class="las la-shield-alt text-red-500 text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Security Settings</h2>
                                <p class="text-gray-600 dark:text-gray-400">Keep your account secure with a strong password</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <!-- Current Password -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    Current Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" name="current_password" id="current_password"
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all">
                                    <button type="button" onclick="togglePassword('current_password')" 
                                            class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                        <i class="las la-eye text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"></i>
                                    </button>
                                </div>
                                @error('current_password') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    New Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" name="password" id="password"
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all">
                                    <button type="button" onclick="togglePassword('password')"
                                            class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                        <i class="las la-eye text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"></i>
                                    </button>
                                </div>
                                @error('password') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    Confirm New Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all">
                                    <button type="button" onclick="togglePassword('password_confirmation')"
                                            class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                        <i class="las la-eye text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Password Requirements -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl p-6">
                                <h4 class="text-sm font-medium text-blue-900 dark:text-blue-300 mb-3">Password Requirements</h4>
                                <div class="space-y-2 text-sm text-blue-700 dark:text-blue-400">
                                    <div class="flex items-center gap-2">
                                        <i class="las la-check-circle text-green-500"></i>
                                        <span>At least 8 characters long</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="las la-check-circle text-green-500"></i>
                                        <span>Include uppercase and lowercase letters</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="las la-check-circle text-green-500"></i>
                                        <span>Include at least one number</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-red-500 to-red-600 dark:from-red-500 dark:to-red-600 text-white py-3 px-6 rounded-xl font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('extra-js')
    <script>
        // Experience Management
        document.getElementById('add-experience').addEventListener('click', function() {
            const container = document.getElementById('experience-container');
            const template = `
                <div class="experience-form bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-600 rounded-2xl p-6 relative">
                    <button type="button" class="remove-experience absolute -top-3 -right-3 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors shadow-lg">
                        <i class="las la-times"></i>
                    </button>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Position <span class="text-red-500">*</span></label>
                            <input type="text" name="position[]" class="w-full px-4 py-3 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Company <span class="text-red-500">*</span></label>
                            <input type="text" name="company_name[]" class="w-full px-4 py-3 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center gap-3 p-4 bg-white dark:bg-gray-700/50 rounded-xl cursor-pointer">
                            <input type="checkbox" name="currently_working[]" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">Currently working here</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Check if this is your current position</div>
                            </div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Start Date</label>
                            <input type="date" name="start_date[]" class="w-full px-4 py-3 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">End Date</label>
                            <input type="date" name="end_date[]" class="w-full px-4 py-3 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Description</label>
                        <textarea name="description[]" rows="3" class="w-full px-4 py-3 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all resize-none" placeholder="Describe your role, responsibilities, and achievements"></textarea>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', template);
            setupEventListeners();
        });

        // Skills Management
        document.getElementById('add-skill').addEventListener('click', function() {
            const container = document.getElementById('skills-container');
            const template = `
                <div class="skill-entry bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-600 rounded-2xl p-6 relative">
                    <button type="button" class="remove-skill absolute -top-3 -right-3 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors shadow-lg">
                        <i class="las la-times"></i>
                    </button>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Skill Name</label>
                            <input type="text" name="skill[]" class="w-full px-4 py-3 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all" placeholder="e.g. JavaScript, Project Management">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Proficiency Level</label>
                            <select name="skill_level[]" class="w-full px-4 py-3 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-pink-500 focus:border-transparent transition-all">
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="proficient">Proficient</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', template);
            setupEventListeners();
        });

        // Setup event listeners
        function setupEventListeners() {
            // Remove experience buttons
            document.querySelectorAll('.remove-experience').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.experience-form').remove();
                });
            });

            // Remove skill buttons
            document.querySelectorAll('.remove-skill').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.skill-entry').remove();
                });
            });

            // Currently working checkboxes
            document.querySelectorAll('input[name="currently_working[]"]').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const endDateInput = this.closest('.experience-form').querySelector('input[name="end_date[]"]');
                    endDateInput.disabled = this.checked;
                    if (this.checked) {
                        endDateInput.value = '';
                    }
                });
            });
        }

        // Password toggle
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = event.target;
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('la-eye');
                icon.classList.add('la-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('la-eye-slash');
                icon.classList.add('la-eye');
            }
        }

        // Initialize event listeners
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
        });
    </script>
    @endpush
@endsection