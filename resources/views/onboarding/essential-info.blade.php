@extends('v2.auth.app')
@section('title') Essential Info - Onboarding @endsection
@section('content')
    <div class="min-h-screen flex items-center justify-center py-6 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-lg mx-auto">
            <!-- Progress Bar -->
            <div class="mb-6">
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">Step 2 of 3</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Profile</span>
                </div>
                <div class="flex space-x-2">
                    <div class="flex-1 h-2 bg-emerald-500 rounded-full"></div>
                    <div class="flex-1 h-2 bg-indigo-600 rounded-full"></div>
                    <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 p-6 sm:p-8">
                <!-- Header -->
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Essential Information</h1>
                    <p class="text-gray-600 dark:text-gray-400">Help us match you with relevant opportunities</p>
                </div>

                <!-- Form -->
                <form action="{{ route('onboarding.essential-info.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <!-- Occupation -->
                    <div>
                        <label for="occupation" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            What's your occupation? <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="occupation"
                            name="occupation"
                            value="{{ old('occupation', $user->occupation) }}"
                            placeholder="e.g., Software Developer, Marketing Manager"
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 border-2 border-gray-200 dark:border-gray-700 focus:border-indigo-500 dark:focus:border-indigo-400 focus:outline-none transition-all duration-200 @error('occupation') border-red-500 focus:border-red-500 @enderror"
                            required
                        >
                        @error('occupation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Country -->
                    <div>
                        <label for="country_id" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Which country are you in? <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select
                                id="country_id"
                                name="country_id"
                                class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border-2 border-gray-200 dark:border-gray-700 focus:border-indigo-500 dark:focus:border-indigo-400 focus:outline-none transition-all duration-200 appearance-none @error('country_id') border-red-500 focus:border-red-500 @enderror"
                                required
                            >
                                <option value="">Select your country</option>
                                @foreach(\App\Models\Country::where('is_active', true)->orderBy('name')->get() as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        @error('country_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bio (Optional) -->
                    <div>
                        <label for="bio" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Brief bio <span class="text-gray-500 font-normal">(optional)</span>
                        </label>
                        <textarea
                            id="bio"
                            name="bio"
                            rows="3"
                            placeholder="Tell us about your professional background..."
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 border-2 border-gray-200 dark:border-gray-700 focus:border-indigo-500 dark:focus:border-indigo-400 focus:outline-none transition-all duration-200 resize-none @error('bio') border-red-500 focus:border-red-500 @enderror"
                            maxlength="500"
                        >{{ old('bio', $user->bio) }}</textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Max 500 characters</p>
                        @error('bio')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3 pt-4">
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center space-x-2"
                        >
                            <span>Continue to Preferences</span>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                        
                        <a href="{{ route('onboarding.skip') }}" 
                           class="w-full text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 px-6 py-3 text-center transition-colors duration-200 block hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl">
                            Skip for now
                        </a>
                    </div>
                </form>
            </div>

            <!-- Navigation -->
            <div class="flex justify-between items-center mt-4">
                <a href="{{ route('onboarding.welcome') }}" class="flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back
                </a>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    Need help? <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:underline">Contact support</a>
                </div>
            </div>
        </div>
    </div>
@endsection