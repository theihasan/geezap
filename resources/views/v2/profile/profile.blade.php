@php use App\Enums\SkillProficiency; @endphp
@extends('v2.layouts.app')
@section('content')
    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white dark:bg-[#12122b] rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Profile</h3>
                    <button onclick="closeModal('editProfileModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="las la-times text-2xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Profile Image -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profile Image</label>
                        <div class="flex items-center gap-4">
                            <img id="profilePreview" src="{{asset('assets/images/profile.jpg')}}" alt="Profile" class="w-16 h-16 rounded-xl object-cover">
                            <input type="file" name="profile_image" id="profileImage" accept="image/*" class="hidden" onchange="previewImage(this)">
                            <button type="button" onclick="document.getElementById('profileImage').click()" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                <i class="las la-camera mr-2"></i>Change Photo
                            </button>
                        </div>
                    </div>

                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ auth()->user()->name }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Occupation</label>
                            <input type="text" name="occupation" value="{{ auth()->user()->occupation }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                            <input type="tel" name="phone" value="{{ auth()->user()->phone }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Birth</label>
                            <input type="date" name="dob" value="{{ auth()->user()->dob }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address</label>
                        <textarea name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ auth()->user()->address }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bio</label>
                        <textarea name="bio" rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ auth()->user()->bio }}</textarea>
                    </div>

                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="closeModal('editProfileModal')" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white rounded-lg hover:opacity-90 transition-opacity">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Social Links Modal -->
    <div id="editSocialModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white dark:bg-[#12122b] rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Social Links</h3>
                    <button onclick="closeModal('editSocialModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="las la-times text-2xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        @foreach(['website', 'github', 'linkedin', 'twitter', 'facebook'] as $platform)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 capitalize">{{ $platform }} URL</label>
                                <input type="url" name="{{ $platform }}" value="{{ auth()->user()->$platform }}" placeholder="https://{{ $platform }}.com/yourusername" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        @endforeach
                    </div>

                    <div class="flex gap-3 justify-end mt-6">
                        <button type="button" onclick="closeModal('editSocialModal')" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white rounded-lg hover:opacity-90 transition-opacity">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Experience Modal -->
    <div id="editExperienceModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-2 sm:p-4">
        <div class="bg-white dark:bg-[#12122b] rounded-2xl max-w-4xl w-full h-[95vh] sm:h-[90vh] flex flex-col">
            <!-- Fixed Header -->
            <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-800 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">Edit Work Experience</h3>
                    <button onclick="closeModal('editExperienceModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="las la-times text-xl sm:text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto min-h-0 max-h-0">
                <div class="p-4 sm:p-6">
                    <form id="experienceForm" action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div id="experienceContainer" class="space-y-3 sm:space-y-4">
                            <!-- Experience entries will be added here -->
                        </div>
                        
                        <button type="button" onclick="addExperienceEntry()" class="w-full py-2 sm:py-3 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg text-gray-600 dark:text-gray-400 hover:border-blue-500 dark:hover:border-pink-500 hover:text-blue-600 dark:hover:text-pink-500 transition-colors mb-4 sm:mb-6 text-sm sm:text-base">
                            <i class="las la-plus mr-2"></i>Add Experience
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Fixed Footer -->
            <div class="p-4 sm:p-6 border-t border-gray-200 dark:border-gray-800 flex-shrink-0 bg-white dark:bg-[#12122b]">
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 justify-end">
                    <button type="button" onclick="closeModal('editExperienceModal')" class="w-full sm:w-auto px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors text-sm sm:text-base">
                        Cancel
                    </button>
                    <button type="submit" form="experienceForm" class="w-full sm:w-auto px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white rounded-lg hover:opacity-90 transition-opacity text-sm sm:text-base">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard Header -->
    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-[#12122b] dark:to-[#1a1a3a] border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
            <div class="text-center">
                <!-- Welcome Message -->
                <div class="mb-6">
                    <img src="{{asset('assets/images/profile.jpg')}}" alt="{{ auth()->user()->name }}" 
                         class="w-20 h-20 sm:w-24 sm:h-24 rounded-full object-cover mx-auto mb-4 border-4 border-blue-500/20 dark:border-pink-500/20 shadow-lg">
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-2 font-oxanium-bold">
                        Welcome back, {{ explode(' ', auth()->user()->name)[0] }}! 👋
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400 font-sans">
                        Your career hub - manage your profile, track applications, and discover opportunities
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
        <!-- Quick Actions Grid -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6 font-oxanium-semibold">Quick Actions</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <!-- Edit Profile Card -->
                <div class="bg-white dark:bg-[#12122b] rounded-2xl p-6 border border-gray-200 dark:border-gray-800 hover:border-blue-500/30 dark:hover:border-pink-500/30 transition-all group hover:shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-500/10 dark:bg-pink-500/10 rounded-xl group-hover:bg-blue-500/20 dark:group-hover:bg-pink-500/20 transition-colors">
                            <i class="las la-user-edit text-2xl text-blue-600 dark:text-pink-500"></i>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 font-oxanium-semibold">Edit Profile</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-sans mb-4">Update your personal information, bio, and experience</p>
                    <button onclick="openModal('editProfileModal')" 
                            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white py-2 px-4 rounded-lg hover:opacity-90 transition-all font-sans">
                        Edit Profile
                    </button>
                </div>

                <!-- My Applications Card -->
                <div class="bg-white dark:bg-[#12122b] rounded-2xl p-6 border border-gray-200 dark:border-gray-800 hover:border-blue-500/30 dark:hover:border-pink-500/30 transition-all group hover:shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-purple-500/10 dark:bg-indigo-500/10 rounded-xl group-hover:bg-purple-500/20 dark:group-hover:bg-indigo-500/20 transition-colors">
                            <i class="las la-briefcase text-2xl text-purple-600 dark:text-indigo-400"></i>
                        </div>
                        @php
                            $applicationCount = auth()->user()->jobs()->count() ?? 0;
                        @endphp
                        <span class="bg-purple-100 dark:bg-indigo-100/10 text-purple-700 dark:text-indigo-400 px-2 py-1 rounded-lg text-sm font-semibold">
                            {{ auth()->user()->jobs()->count() }}
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 font-oxanium-semibold">My Applications</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-sans mb-4">Track your job applications and saved opportunities</p>
                    <a href="{{ route('applications') }}" 
                       class="w-full bg-gradient-to-r from-purple-500 to-purple-600 dark:from-indigo-500 dark:to-indigo-600 text-white py-2 px-4 rounded-lg hover:opacity-90 transition-all font-sans block text-center">
                        View Applications
                    </a>
                </div>

                <!-- Preferences Card -->
                <div class="bg-white dark:bg-[#12122b] rounded-2xl p-6 border border-gray-200 dark:border-gray-800 hover:border-blue-500/30 dark:hover:border-pink-500/30 transition-all group hover:shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-green-500/10 dark:bg-emerald-500/10 rounded-xl group-hover:bg-green-500/20 dark:group-hover:bg-emerald-500/20 transition-colors">
                            <i class="las la-cog text-2xl text-green-600 dark:text-emerald-500"></i>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 font-oxanium-semibold">Preferences</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-sans mb-4">Set your job preferences and notification settings</p>
                    <a href="{{ route('profile.preferences') }}" 
                       class="w-full bg-gradient-to-r from-green-500 to-green-600 dark:from-emerald-500 dark:to-emerald-600 text-white py-2 px-4 rounded-lg hover:opacity-90 transition-all font-sans block text-center">
                        Manage Settings
                    </a>
                </div>

                <!-- Browse Jobs Card -->
                <div class="bg-white dark:bg-[#12122b] rounded-2xl p-6 border border-gray-200 dark:border-gray-800 hover:border-blue-500/30 dark:hover:border-pink-500/30 transition-all group hover:shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-orange-500/10 dark:bg-amber-500/10 rounded-xl group-hover:bg-orange-500/20 dark:group-hover:bg-amber-500/20 transition-colors">
                            <i class="las la-search text-2xl text-orange-600 dark:text-amber-500"></i>
                        </div>
                        <span class="bg-orange-100 dark:bg-amber-100/10 text-orange-700 dark:text-amber-400 px-2 py-1 rounded-lg text-sm font-semibold">
                            New
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 font-oxanium-semibold">Browse Jobs</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-sans mb-4">Discover new job opportunities that match your skills</p>
                    <a href="{{ route('job.index') }}" 
                       class="w-full bg-gradient-to-r from-orange-500 to-orange-600 dark:from-amber-500 dark:to-amber-600 text-white py-2 px-4 rounded-lg hover:opacity-90 transition-all font-sans block text-center">
                        Find Jobs
                    </a>
                </div>
            </div>
        </div>

        <!-- Activity Stats Section - Full Width -->
        <div class="bg-white dark:bg-[#12122b] rounded-2xl p-6 border border-gray-200 dark:border-gray-800 mb-6 sm:mb-8">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 font-oxanium-semibold flex items-center gap-2">
                <i class="las la-chart-line text-blue-600 dark:text-pink-500"></i>
                Your Activity Overview
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
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-blue-50 dark:bg-blue-500/10 rounded-xl">
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 font-oxanium-bold mb-1">{{ auth()->user()->jobs()->count() ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 font-sans">Job Applications</div>
                </div>
                <div class="text-center p-4 bg-green-50 dark:bg-green-500/10 rounded-xl">
                    <div class="text-3xl font-bold text-green-600 dark:text-green-400 font-oxanium-bold mb-1">{{ $recommendedJobs ? $recommendedJobs->count() : 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 font-sans">Recommended Jobs</div>
                </div>
                <div class="text-center p-4 bg-purple-50 dark:bg-purple-500/10 rounded-xl">
                    <div class="text-3xl font-bold text-purple-600 dark:text-purple-400 font-oxanium-bold mb-1">{{ $completionPercentage }}%</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 font-sans">Profile Complete</div>
                </div>
                <div class="text-center p-4 bg-orange-50 dark:bg-orange-500/10 rounded-xl">
                    <div class="text-3xl font-bold text-orange-600 dark:text-orange-400 font-oxanium-bold mb-1">{{ round(auth()->user()->created_at->diffInDays()) ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 font-sans">Days Active</div>
                </div>
            </div>
        </div>

        <!-- Main Content Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            <!-- Main Content Area - Job Recommendations -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Recommended Jobs Section -->
                <div class="bg-white dark:bg-[#12122b] rounded-2xl p-6 border border-gray-200 dark:border-gray-800">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3 font-oxanium-bold">
                            <i class="las la-star text-blue-600 dark:text-pink-500"></i>
                            Recommended For You
                        </h3>
                        <a href="{{ route('job.index') }}" 
                           class="text-blue-600 dark:text-pink-500 hover:text-blue-700 dark:hover:text-pink-400 text-sm font-sans flex items-center gap-1 bg-blue-50 dark:bg-blue-500/10 px-3 py-2 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-all">
                            Browse All Jobs
                            <i class="las la-arrow-right"></i>
                        </a>
                    </div>
                    
                    @if($recommendedJobs && $recommendedJobs->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            @foreach($recommendedJobs->take(6) as $job)
                                <div class="bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 rounded-xl p-5 transition-all border border-gray-200 dark:border-gray-700 hover:border-blue-500/30 dark:hover:border-pink-500/30 hover:shadow-lg">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h4 class="text-gray-900 dark:text-white font-semibold text-base mb-2 line-clamp-2 font-sans leading-relaxed">
                                                <a href="{{ route('job.show', $job->slug) }}" class="hover:text-blue-600 dark:hover:text-pink-500 transition-colors">
                                                    {{ $job->job_title }}
                                                </a>
                                            </h4>
                                            <p class="text-gray-600 dark:text-gray-400 text-sm font-sans font-medium">{{ $job->employer_name }}</p>
                                        </div>
                                        @if($job->is_remote)
                                            <span class="bg-green-500/10 text-green-600 dark:text-green-400 px-3 py-1 rounded-full text-xs font-medium font-sans">
                                                Remote
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-3 text-gray-600 dark:text-gray-400">
                                            @if($job->country)
                                                <span class="flex items-center gap-1">
                                                    <i class="las la-map-marker"></i>
                                                    {{ $job->country }}
                                                </span>
                                            @endif
                                            @if($job->category)
                                                <span class="bg-blue-500/10 dark:bg-pink-500/10 text-blue-600 dark:text-pink-400 px-2 py-1 rounded-full text-xs font-medium">
                                                    {{ $job->category->name }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-gray-500 font-sans">
                                            {{ $job->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($recommendedJobs->count() > 6)
                            <div class="text-center mt-6">
                                <a href="{{ route('job.index') }}" 
                                   class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white px-6 py-3 rounded-lg hover:opacity-90 transition-opacity font-sans font-medium">
                                    <i class="las la-eye"></i>
                                    View All {{ $recommendedJobs->count() }} Recommendations
                                </a>
                            </div>
                        @endif
                    @else
                        <!-- No recommendations fallback -->
                        <div class="text-center py-12">
                            <div class="mb-6">
                                <i class="las la-lightbulb text-8xl text-gray-300 dark:text-gray-600"></i>
                            </div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-3 font-oxanium-bold">
                                No Recommendations Yet
                            </h4>
                            <p class="text-gray-600 dark:text-gray-400 mb-8 font-sans max-w-md mx-auto text-base leading-relaxed">
                                Set up your job preferences to get personalized job recommendations that match your interests and skills.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="{{ route('profile.preferences') }}" 
                                   class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white px-6 py-3 rounded-lg hover:opacity-90 transition-all font-sans flex items-center gap-2 justify-center font-medium">
                                    <i class="las la-cog"></i>
                                    Set Preferences
                                </a>
                                <a href="{{ route('job.index') }}" 
                                   class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-300 px-6 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-sans flex items-center gap-2 justify-center font-medium">
                                    <i class="las la-search"></i>
                                    Browse All Jobs
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Sidebar - Profile Summary -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Profile Completion Card -->
                <div class="bg-white dark:bg-[#12122b] rounded-2xl p-6 border border-gray-200 dark:border-gray-800">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 font-oxanium-semibold flex items-center gap-2">
                        <i class="las la-chart-pie text-blue-600 dark:text-pink-500"></i>
                        Profile Completion
                    </h3>
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $completionPercentage }}% Complete</span>
                            <span class="text-sm text-gray-500">{{ $completedFields }}/{{ $totalFields }}</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 h-3 rounded-full transition-all duration-700"
                                 style="width: {{ $completionPercentage }}%"></div>
                        </div>
                    </div>
                    @if($completionPercentage < 100)
                        <div class="bg-blue-50 dark:bg-blue-500/10 p-4 rounded-lg">
                            <p class="text-sm text-gray-700 dark:text-gray-300 font-sans">
                                <i class="las la-info-circle text-blue-600 dark:text-blue-400 mr-1"></i>
                                Complete your profile to increase your chances of being discovered by employers.
                            </p>
                        </div>
                    @else
                        <div class="bg-green-50 dark:bg-green-500/10 p-4 rounded-lg">
                            <p class="text-sm text-gray-700 dark:text-gray-300 font-sans">
                                <i class="las la-check-circle text-green-600 dark:text-green-400 mr-1"></i>
                                Great! Your profile is complete and ready for employers.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Quick Profile Info -->
                <div class="bg-white dark:bg-[#12122b] rounded-2xl p-6 border border-gray-200 dark:border-gray-800">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 font-oxanium-semibold flex items-center gap-2">
                        <i class="las la-user text-blue-600 dark:text-pink-500"></i>
                        Profile Overview
                    </h3>
                    <div class="space-y-4 font-sans">
                        <div class="flex items-center gap-3">
                            <i class="las la-id-card text-blue-600 dark:text-pink-500"></i>
                            <span class="text-gray-900 dark:text-white font-medium">{{ auth()->user()->name }}</span>
                        </div>
                        @if(auth()->user()->occupation)
                            <div class="flex items-center gap-3">
                                <i class="las la-briefcase text-blue-600 dark:text-pink-500"></i>
                                <span class="text-gray-600 dark:text-gray-400">{{ auth()->user()->occupation }}</span>
                            </div>
                        @endif
                        @if(auth()->user()->location || auth()->user()->country)
                            <div class="flex items-center gap-3">
                                <i class="las la-map-marker text-blue-600 dark:text-pink-500"></i>
                                <span class="text-gray-600 dark:text-gray-400">{{ auth()->user()->location ?? auth()->user()->country }}</span>
                            </div>
                        @endif
                        @if(auth()->user()->website)
                            <div class="flex items-center gap-3">
                                <i class="las la-link text-blue-600 dark:text-pink-500"></i>
                                <a href="{{ auth()->user()->website }}" target="_blank" class="text-blue-600 dark:text-pink-500 hover:underline">{{ Str::limit(auth()->user()->website, 25) }}</a>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex gap-3 mt-6">
                        <button onclick="openModal('editProfileModal')" class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white px-4 py-2 rounded-lg hover:opacity-90 transition-opacity text-sm font-sans font-medium">
                            <i class="las la-edit mr-2"></i>Edit Profile
                        </button>
                        <button onclick="openModal('editSocialModal')" class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-sans">
                            <i class="las la-share-alt"></i>
                        </button>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="bg-white dark:bg-[#12122b] rounded-2xl p-6 border border-gray-200 dark:border-gray-800">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 font-oxanium-semibold flex items-center gap-2">
                        <i class="las la-bolt text-blue-600 dark:text-pink-500"></i>
                        Quick Actions
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('profile.preferences') }}" 
                           class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-white/5 rounded-lg hover:bg-gray-100 dark:hover:bg-white/10 transition-colors text-gray-900 dark:text-white font-sans">
                            <i class="las la-cog text-blue-600 dark:text-pink-500"></i>
                            <span>Job Preferences</span>
                        </a>
                        <a href="{{ route('job.index') }}" 
                           class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-white/5 rounded-lg hover:bg-gray-100 dark:hover:bg-white/10 transition-colors text-gray-900 dark:text-white font-sans">
                            <i class="las la-search text-blue-600 dark:text-pink-500"></i>
                            <span>Search Jobs</span>
                        </a>
                        <a href="{{ route('applications') }}" 
                           class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-white/5 rounded-lg hover:bg-gray-100 dark:hover:bg-white/10 transition-colors text-gray-900 dark:text-white font-sans">
                            <i class="las la-file-alt text-blue-600 dark:text-pink-500"></i>
                            <span>My Applications</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modals -->
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.getElementById(modalId).classList.add('flex');
            document.body.style.overflow = 'hidden';
            
            // Load existing experience data if opening experience modal
            if (modalId === 'editExperienceModal') {
                loadExistingExperience();
            }
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.getElementById(modalId).classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePreview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function addExperienceEntry() {
            addExperienceEntryWithData();
            
            // Scroll to the newly added entry
            const container = document.getElementById('experienceContainer');
            const lastEntry = container.lastElementChild;
            if (lastEntry) {
                lastEntry.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        function toggleEndDate(checkbox) {
            const endDateInput = checkbox.closest('.border').querySelector('input[name="experience[end_date][]"]');
            if (checkbox.checked) {
                endDateInput.disabled = true;
                endDateInput.value = '';
            } else {
                endDateInput.disabled = false;
            }
        }

        function removeExperienceEntry(button) {
            button.closest('.border').remove();
        }

        function loadExistingExperience() {
            const container = document.getElementById('experienceContainer');
            container.innerHTML = ''; // Clear existing content
            
            // Get existing experience data from the page
            const experienceData = @json(auth()->user()->experience ? json_decode(auth()->user()->experience, true) : []);
            
            if (experienceData && experienceData.position && experienceData.position.length > 0) {
                // Load existing experience entries
                for (let i = 0; i < experienceData.position.length; i++) {
                    addExperienceEntryWithData(
                        experienceData.position[i] || '',
                        experienceData.company_name[i] || '',
                        experienceData.start_date[i] || '',
                        experienceData.end_date[i] || '',
                        experienceData.currently_working[i] === 'on' || experienceData.currently_working[i] === true,
                        experienceData.description[i] || ''
                    );
                }
            }
        }

        function addExperienceEntryWithData(position = '', company = '', startDate = '', endDate = '', currentlyWorking = false, description = '') {
            const container = document.getElementById('experienceContainer');
            const index = container.children.length;
            
            const experienceEntry = document.createElement('div');
            experienceEntry.className = 'border border-gray-200 dark:border-gray-700 rounded-lg p-3 sm:p-4 bg-gray-50 dark:bg-gray-800/50';
            experienceEntry.innerHTML = `
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <h4 class="font-medium text-gray-900 dark:text-white text-sm sm:text-base">Experience Entry ${index + 1}</h4>
                    <button type="button" onclick="removeExperienceEntry(this)" class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                        <i class="las la-trash text-sm sm:text-lg"></i>
                    </button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-3 sm:mb-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-2">Position *</label>
                        <input type="text" name="experience[position][]" value="${position}" required class="w-full px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-2">Company *</label>
                        <input type="text" name="experience[company_name][]" value="${company}" required class="w-full px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-3 sm:mb-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-2">Start Date *</label>
                        <input type="date" name="experience[start_date][]" value="${startDate}" required class="w-full px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-2">End Date</label>
                        <input type="date" name="experience[end_date][]" value="${endDate}" ${currentlyWorking ? 'disabled' : ''} class="w-full px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                    </div>
                </div>
                <div class="mb-3 sm:mb-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="experience[currently_working][]" value="on" ${currentlyWorking ? 'checked' : ''} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" onchange="toggleEndDate(this)">
                        <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-300">Currently working here</span>
                    </label>
                </div>
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-2">Description</label>
                    <textarea name="experience[description][]" rows="2" placeholder="Describe your role and achievements..." class="w-full px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">${description}</textarea>
                </div>
            `;
            
            container.appendChild(experienceEntry);
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
                const modalId = e.target.id;
                if (modalId) {
                    closeModal(modalId);
                }
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const openModals = document.querySelectorAll('.fixed.inset-0.flex');
                openModals.forEach(modal => {
                    closeModal(modal.id);
                });
            }
        });
    </script>
@endsection
