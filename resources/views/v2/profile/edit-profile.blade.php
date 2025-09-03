@extends('v2.layouts.app')
@section('content')
    <!-- Profile Header -->
    <div class="bg-gray-50 dark:bg-[#12122b] border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-6">
                <!-- Profile Image -->
                <div class="relative group">
                    <img src="{{asset('assets/images/profile.jpg')}}" alt="{{ auth()->user()->name }}"
                         class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover border-2 border-blue-500/20 dark:border-pink-500/20">
                    <div class="absolute inset-0 bg-black/50 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <button class="text-white hover:text-blue-600 dark:hover:text-pink-500 transition-colors">
                            <i class="las la-camera text-2xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="flex-1 text-center sm:text-left">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2 font-oxanium-bold">{{ auth()->user()->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 font-ubuntu-regular">{{ auth()->user()->occupation }}</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3 mt-4 sm:mt-0">
                    <a href="{{ route('dashboard') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 hover:opacity-90 text-white px-4 sm:px-6 py-2 rounded-xl transition-all flex items-center gap-2 font-oxanium-semibold text-sm sm:text-base">
                        <i class="las la-eye"></i>
                        <span class="hidden sm:inline">View Profile</span>
                        <span class="sm:hidden">View</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-12">
        @if (session('status'))
            <div class="mb-8 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 px-4 py-3 rounded-xl">
                {{ session('status') }}
            </div>
        @endif

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Personal Info & Password -->
            <div class="space-y-8">
                <!-- Personal Information Form -->
                <form action="{{ route('personal-info.update') }}" method="POST" class="bg-white dark:bg-[#12122b] rounded-2xl p-6 border border-gray-200 dark:border-gray-800">
                    @csrf
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2 font-oxanium-semibold">
                        <i class="las la-user-circle text-blue-600 dark:text-pink-500"></i>
                        Personal Information
                    </h2>
                    <div class="space-y-4">
                        <!-- Full Name -->
                        <div>
                            <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">Full Name*</label>
                            <input type="text" name="name" value="{{ auth()->user()->name }}"
                                   class="w-full bg-gray-50 dark:bg-white/5 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">Email Address*</label>
                            <input type="email" value="{{ auth()->user()->email }}" disabled
                                   class="w-full bg-gray-50 dark:bg-white/5 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-500 dark:text-white/50">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">Date of Birth</label>
                            <input type="date" name="dob" value="{{ auth()->user()->dob?->format('Y-m-d') }}"
                                   class="w-full bg-gray-50 dark:bg-white/5 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500">
                            @error('dob') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">Phone Number</label>
                            <input type="tel" name="phone" value="{{ auth()->user()->phone }}"
                                   class="w-full bg-gray-50 dark:bg-white/5 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500">
                            @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Occupation -->
                        <div>
                            <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">Occupation</label>
                            <input type="text" name="occupation" value="{{ auth()->user()->occupation }}"
                                   class="w-full bg-gray-50 dark:bg-white/5 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500">
                            @error('occupation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">Address</label>
                            <textarea name="address"
                                      class="w-full bg-gray-50 dark:bg-white/5 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500 h-24 resize-none">{{ auth()->user()->address }}</textarea>
                            @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Location Details -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">State</label>
                                <input type="text" name="state" value="{{ auth()->user()->state }}"
                                       class="w-full bg-gray-50 dark:bg-[#1a1a3a] border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500">
                                @error('state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">Country</label>
                                <input type="text" name="country" value="{{ auth()->user()->country }}"
                                       class="w-full bg-gray-50 dark:bg-[#1a1a3a] border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500">
                                @error('country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Timezone -->
                        <div>
                            <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">Timezone</label>
                            <select name="timezone"
                                    class="w-full bg-gray-50 dark:bg-[#1a1a3a] border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500 appearance-none">
                                @foreach($timezones as $timezone)
                                    <option value="{{ $timezone->value }}"
                                            @if(auth()->user()->timezone === $timezone->value) selected @endif
                                            class="bg-[#1a1a3a] text-white">
                                        {{ $timezone->value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('timezone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Bio -->
                        <div>
                            <label class="text-sm text-gray-400 block mb-1">Bio</label>
                            <textarea name="bio"
                                      class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500 h-24 resize-none">{{ auth()->user()->bio }}</textarea>
                            @error('bio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit"
                                class="w-full bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl py-3 hover:opacity-90 transition-opacity">
                            Update Personal Information
                        </button>
                    </div>
                </form>

                <!-- Change Password -->
                <form action="{{ route('userpassword.update') }}" method="POST" class="bg-white dark:bg-[#12122b] rounded-2xl p-6 border border-gray-200 dark:border-gray-800">
                    @csrf
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2 font-oxanium-semibold">
                        <i class="las la-lock text-blue-600 dark:text-pink-500"></i>
                        Change Password
                    </h2>
                    <div class="space-y-4">
                        <!-- Current Password Field -->
                        <div>
                            <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">Current Password*</label>
                            <div class="relative">
                                <input type="password" name="current_password" id="currentPassword"
                                       class="w-full bg-gray-50 dark:bg-white/5 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500 pr-12">
                                <button type="button"
                                        onclick="togglePassword('currentPassword', this)"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                    <svg class="eye-open w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg class="eye-closed w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('current_password') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- New Password Field -->
                        <div>
                            <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">New Password*</label>
                            <div class="relative">
                                <input type="password" name="password" id="newPassword"
                                       class="w-full bg-gray-50 dark:bg-white/5 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500 pr-12">
                                <button type="button"
                                        onclick="togglePassword('newPassword', this)"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                    <svg class="eye-open w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.5235 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg class="eye-closed w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('password') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div>
                            <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">Confirm New Password*</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="confirmPassword"
                                       class="w-full bg-gray-50 dark:bg-white/5 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500 pr-12">
                                <button type="button"
                                        onclick="togglePassword('confirmPassword', this)"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                    <svg class="eye-open w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg class="eye-closed w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white rounded-xl py-3 hover:opacity-90 transition-opacity mt-6">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Column - Experience, Skills, Social -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Experience Form -->
                <form action="{{ route('experience.update') }}" method="POST" class="bg-white dark:bg-[#12122b] rounded-2xl p-6 border border-gray-200 dark:border-gray-800">
                    @csrf
                    <div class="flex justify-between items-center mb-6 font-oxanium-semibold">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="las la-briefcase text-blue-600 dark:text-pink-500"></i>
                            Work Experience
                        </h2>
                        <button type="button" id="add-experience" class="text-blue-600 dark:text-pink-500 hover:text-blue-700 dark:hover:text-pink-400 flex items-center gap-1">
                            <i class="las la-plus"></i>
                            Add Experience
                        </button>
                    </div>

                    <div id="experience-container" class="space-y-6">
                        @if(!empty($experiences['position']))
                            @foreach($experiences['position'] as $index => $position)
                                <div class="experience-form border border-gray-200 dark:border-gray-700 rounded-xl p-6 space-y-4 relative">
                                    <button type="button" class="remove-experience absolute -top-3 -right-3 bg-red-500/10 hover:bg-red-500/20 text-red-500 rounded-full p-1 transition-colors">
                                        <i class="las la-times text-lg"></i>
                                    </button>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">Position*</label>
                                            <input type="text" name="position[]" value="{{ $position }}"
                                                   class="w-full bg-gray-50 dark:bg-white/5 border @error('position.'.$index) border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500">
                                            @error('position.'.$index)
                                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">Company Name*</label>
                                            <input type="text" name="company_name[]" value="{{ $experiences['company_name'][$index] }}"
                                                   class="w-full bg-gray-50 dark:bg-white/5 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500">
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-white/5 rounded-xl">
                                        <div>
                                            <h3 class="text-gray-900 dark:text-white font-medium">Currently Working Here</h3>
                                            <p class="text-gray-600 dark:text-gray-400 text-sm">Toggle if this is your current job</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="currently_working[]" class="sr-only peer"
                                                {{ isset($experiences['currently_working'][$index]) && $experiences['currently_working'][$index] ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-blue-500/10 dark:bg-pink-500/10 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500/20 dark:peer-focus:ring-pink-500/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-2 after:border-blue-500/20 dark:after:border-pink-500/20 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500 dark:peer-checked:bg-pink-500 peer-checked:after:border-0"></div>
                                        </label>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">Start Date*</label>
                                            <input type="date" name="start_date[]" value="{{ $experiences['start_date'][$index] ?? '' }}"
                                                   class="w-full bg-gray-50 dark:bg-white/5 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500">
                                        </div>
                                        <div>
                                            <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">End Date</label>
                                            <input type="date" name="end_date[]" value="{{ $experiences['end_date'][$index] ?? '' }}"
                                                   class="w-full bg-gray-50 dark:bg-white/5 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500"
                                                {{ isset($experiences['currently_working'][$index]) && $experiences['currently_working'][$index] ? 'disabled' : '' }}>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">End date not required for current job</p>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">Description</label>
                                        <textarea name="description[]"
                                                  class="w-full bg-gray-50 dark:bg-white/5 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500 h-24 resize-none">{{ $experiences['description'][$index] ?? '' }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Default empty experience form -->
                            <div class="experience-form border border-gray-200 dark:border-gray-700 rounded-xl p-6 space-y-4 relative">
                                <!-- Same structure as above for empty form -->
                            </div>
                        @endif
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white rounded-xl py-3 hover:opacity-90 transition-opacity">
                            Update Experience
                        </button>
                    </div>
                </form>

                <!-- Skills and Social Media Grid -->
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Skills Form -->
                    <form id="skills-form" action="{{ route('skill.update') }}" method="POST" class="bg-white dark:bg-[#12122b] h-fit rounded-2xl p-6 border border-gray-200 dark:border-gray-800">
                        @csrf
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-tools text-blue-600 dark:text-pink-500"></i>
                            Skills
                        </h2>
                        <div id="skills-container" class="space-y-4">
                            @if(!empty($skills['skill']))
                                @foreach($skills['skill'] as $index => $skill)
                                    <div class="grid grid-cols-1 gap-4 skill-entry relative">
                                        <button type="button" class="remove-skill absolute -top-2 -right-2 bg-red-500/10 hover:bg-red-500/20 text-red-500 rounded-full p-1 transition-colors">
                                            <i class="las la-times text-lg"></i>
                                        </button>
                                        <div>
                                            <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">Skill Name</label>
                                            <input type="text" name="skill[]" value="{{ $skill }}"
                                                   class="w-full bg-gray-50 dark:bg-white/5 border @error('skill.'.$index) border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500">
                                            @error('skill.'.$index)
                                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">Skill Level</label>
                                            <select name="skill_level[]"
                                                    class="w-full bg-gray-50 dark:bg-white/5 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500">
                                                @foreach([
                                                    App\Enums\SkillProficiency::PROFICIENT->value => 'Proficient',
                                                    App\Enums\SkillProficiency::INTERMEDIATE->value => 'Intermediate',
                                                    App\Enums\SkillProficiency::BEGINNER->value => 'Beginner'
                                                ] as $value => $label)
                                                    <option value="{{ $value }}"
                                                            @if($skills['skill_level'][$index] == $value) selected @endif>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="mt-4 space-y-4">
                            <button type="button" id="add-skill"
                                    class="w-full bg-blue-500/10 dark:bg-pink-500/10 hover:bg-blue-500/20 dark:hover:bg-pink-500/20 text-blue-600 dark:text-pink-300 rounded-xl py-3 transition-colors">
                                <i class="las la-plus"></i> Add More Skills
                            </button>
                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white rounded-xl py-3 hover:opacity-90 transition-opacity">
                                Update Skills
                            </button>
                        </div>
                    </form>

                    <!-- Social Media Form -->
                    <form action="{{ route('social-media-info.update') }}" method="POST" class="bg-white dark:bg-[#12122b] h-fit rounded-2xl p-6 border border-gray-200 dark:border-gray-800">
                        @csrf
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-share-alt text-blue-600 dark:text-pink-500"></i>
                            Social Media
                        </h2>
                        <div class="space-y-4">
                            @foreach(\App\Enums\SocialProvider::cases() as $field => $label)
                                <div>
                                    <label class="text-sm text-gray-700 dark:text-gray-400 block mb-1">{{ $label }}</label>
                                    <div class="relative">
                                        <i class="las la-{{ $label->value }} absolute left-4 top-3.5 text-blue-600 dark:text-pink-500"></i>
                                        <input type="text" name="{{ $label->value }}" value="{{ auth()->user()->{$label->value} }}"
                                               class="w-full bg-gray-50 dark:bg-white/5 border border-gray-300 dark:border-gray-700 rounded-xl pl-10 pr-4 py-2.5 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500"
                                               placeholder="Enter {{$label->value}} username only">
                                        @error($label->value)
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white rounded-xl py-3 hover:opacity-90 transition-opacity">
                                Update Social Media
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('extra-js')
        <script>
            document.getElementById('add-experience').addEventListener('click', function() {
                const experienceContainer = document.getElementById('experience-container');
                const newExperienceTemplate = `
                    <div class="experience-form border border-gray-700 rounded-xl p-6 space-y-4 relative">
                        <button type="button" class="remove-experience absolute -top-3 -right-3 bg-red-500/10 hover:bg-red-500/20 text-red-500 rounded-full p-1 transition-colors">
                            <i class="las la-times text-lg"></i>
                        </button>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-400 block mb-1">Position*</label>
                                <input type="text" name="position[]"
                                       class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                            </div>
                            <div>
                                <label class="text-sm text-gray-400 block mb-1">Company Name*</label>
                                <input type="text" name="company_name[]"
                                       class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-white/5 rounded-xl">
                            <div>
                                <h3 class="text-white font-medium">Currently Working Here</h3>
                                <p class="text-gray-400 text-sm">Toggle if this is your current job</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="currently_working[]" value="1" class="sr-only peer">
                                <div class="w-11 h-6 bg-pink-500/10 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-pink-500/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-2 after:border-pink-500/20 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500 peer-checked:after:border-0"></div>
                            </label>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-400 block mb-1">Start Date*</label>
                                <input type="date" name="start_date[]"
                                       class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                            </div>
                            <div>
                                <label class="text-sm text-gray-400 block mb-1">End Date</label>
                                <input type="date" name="end_date[]"
                                       class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                                <p class="text-sm text-gray-400 mt-1">End date not required for current job</p>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm text-gray-400 block mb-1">Description</label>
                            <textarea name="description[]"
                                      class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500 h-24 resize-none"></textarea>
                        </div>
                    </div>
                `;

                experienceContainer.insertAdjacentHTML('beforeend', newExperienceTemplate);
                const newExperienceForm = experienceContainer.lastElementChild;

                setupRemoveExperienceButton(newExperienceForm);
                setupDateToggle(newExperienceForm);
            });

            function setupDateToggle(form) {
                const checkbox = form.querySelector('input[name="currently_working[]"]');
                const endDateInput = form.querySelector('input[name="end_date[]"]');

                checkbox.addEventListener('change', function() {
                    endDateInput.disabled = this.checked;
                    if (this.checked) {
                        endDateInput.value = '';
                    }
                });
            }

            function setupRemoveExperienceButton(experienceForm) {
                const removeButton = experienceForm.querySelector('.remove-experience');
                if (removeButton) {
                    removeButton.addEventListener('click', function() {
                        const allExperienceForms = document.querySelectorAll('.experience-form');
                        if (allExperienceForms.length > 1) {
                            experienceForm.remove();
                        } else {
                            const inputs = experienceForm.querySelectorAll('input, textarea');
                            inputs.forEach(input => {
                                if (input.type === 'checkbox') {
                                    input.checked = false;
                                } else {
                                    input.value = '';
                                    input.disabled = false;
                                }
                            });
                        }
                    });
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.experience-form').forEach(function(form) {
                    setupRemoveExperienceButton(form);
                    setupDateToggle(form);
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('add-skill').addEventListener('click', function() {
                    const skillsContainer = document.getElementById('skills-container');
                    const newSkillTemplate = `
                        <div class="grid grid-cols-1 gap-4 skill-entry relative">
                            <button type="button" class="remove-skill absolute -top-2 -right-2 bg-red-500/10 hover:bg-red-500/20 text-red-500 rounded-full p-1 transition-colors">
                                <i class="las la-times text-lg"></i>
                            </button>
                            <div>
                                <label class="text-sm text-gray-400 block mb-1">Skill Name</label>
                                <input type="text" name="skill[]"
                                       class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                            </div>
                            <div>
                                <label class="text-sm text-gray-400 block mb-1">Skill Level</label>
                                <select name="skill_level[]"
                                        class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                                    <option value="proficient">Proficient</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="beginner">Beginner</option>
                                </select>
                            </div>
                        </div>
                    `;

                    skillsContainer.insertAdjacentHTML('beforeend', newSkillTemplate);
                    setupRemoveSkillButton(skillsContainer.lastElementChild);
                });

                document.querySelectorAll('.skill-entry').forEach(setupRemoveSkillButton);

                function setupRemoveSkillButton(skillEntry) {
                    const removeButton = skillEntry.querySelector('.remove-skill');
                    if (removeButton) {
                        removeButton.addEventListener('click', function() {
                            const allSkillEntries = document.querySelectorAll('.skill-entry');
                            if (allSkillEntries.length > 1) {
                                skillEntry.remove();
                            } else {
                                const inputs = skillEntry.querySelectorAll('input, select');
                                inputs.forEach(input => input.value = '');
                            }
                        });
                    }
                }
            });

            function togglePassword(inputId, button) {
                const passwordInput = document.getElementById(inputId);
                const eyeOpen = button.querySelector('.eye-open');
                const eyeClosed = button.querySelector('.eye-closed');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeOpen.classList.add('hidden');
                    eyeClosed.classList.remove('hidden');
                } else {
                    passwordInput.type = 'password';
                    eyeOpen.classList.remove('hidden');
                    eyeClosed.classList.add('hidden');
                }
            }
        </script>
    @endpush
@endsection
