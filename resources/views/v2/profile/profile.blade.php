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
                    <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-4 text-sm font-ubuntu-light">
                        @if(auth()->user()->state && auth()->user()->country)
                            <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                <i class="las la-map-marker"></i>
                                {{ auth()->user()->state }}, {{ auth()->user()->country }}
                            </span>
                        @endif
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                            <i class="las la-envelope"></i>
                            <span class="hidden sm:inline">{{ auth()->user()->email }}</span>
                            <span class="sm:hidden">Email</span>
                        </span>
                        @if(auth()->user()->phone)
                            <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                <i class="las la-phone"></i>
                                <span class="hidden sm:inline">{{ auth()->user()->phone }}</span>
                                <span class="sm:hidden">Phone</span>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3 mt-4 sm:mt-0">
                    <button class="bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-900 dark:text-white px-4 py-2 rounded-xl transition-colors flex items-center gap-2 font-ubuntu-medium text-sm sm:text-base relative cursor-not-allowed opacity-80">
                        <i class="las la-download"></i>
                        <span class="hidden sm:inline">Download CV</span>
                        <span class="sm:hidden">CV</span>
                        <span class="absolute -top-2 -right-2 bg-blue-500 dark:bg-pink-500 text-white text-xs py-1 px-2 rounded-full">Soon</span>
                    </button>
                    <a href="{{ route('profile.update') }}"
                       class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 hover:opacity-90 text-white px-4 sm:px-6 py-2 rounded-xl transition-all flex items-center gap-2 font-oxanium-semibold text-sm sm:text-base">
                        <i class="las la-edit"></i>
                        <span class="hidden sm:inline">Edit Profile</span>
                        <span class="sm:hidden">Edit</span>
                    </a>
                </div>


            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            <!-- Left Column -->
            <div class="space-y-6 sm:space-y-8">
                <!-- Personal Information -->
                <div class="bg-white dark:bg-[#12122b] rounded-2xl p-4 sm:p-6 border border-gray-200 dark:border-gray-800">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-user-circle text-blue-600 dark:text-pink-500"></i>
                            Personal Information
                        </h2>
                        <button onclick="openModal('editProfileModal')" class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-pink-500 hover:bg-blue-50 dark:hover:bg-pink-500/10 rounded-lg transition-all">
                            <i class="las la-edit text-lg"></i>
                        </button>
                    </div>
                    <div class="space-y-4 font-ubuntu-light">
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400 block mb-1">Full Name</label>
                            <div class="text-gray-900 dark:text-white font-ubuntu-regular">{{ auth()->user()->name }}</div>
                        </div>
                        @if(auth()->user()->dob)
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400 block mb-1">Date of Birth</label>
                                <div class="text-gray-900 dark:text-white font-ubuntu-regular">{{ Carbon\Carbon::parse(auth()->user()->dob)->format('F d, Y') }}</div>
                            </div>
                        @endif
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-400 block mb-1">Email</label>
                            <div class="text-gray-900 dark:text-white font-ubuntu-regular break-all">{{ auth()->user()->email }}</div>
                        </div>
                        @if(auth()->user()->phone)
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400 block mb-1">Phone</label>
                                <div class="text-gray-900 dark:text-white font-ubuntu-regular">{{ auth()->user()->phone }}</div>
                            </div>
                        @endif
                        @if(auth()->user()->address)
                            <div>
                                <label class="text-sm text-gray-600 dark:text-gray-400 block mb-1">Address</label>
                                <div class="text-gray-900 dark:text-white font-ubuntu-regular">{{ auth()->user()->address }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Social Links -->
                <div class="bg-white dark:bg-[#12122b] rounded-2xl p-4 sm:p-6 border border-gray-200 dark:border-gray-800">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-share-alt text-blue-600 dark:text-pink-500"></i>
                            Social Links
                        </h2>
                        <button onclick="openModal('editSocialModal')" class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-pink-500 hover:bg-blue-50 dark:hover:bg-pink-500/10 rounded-lg transition-all">
                            <i class="las la-edit text-lg"></i>
                        </button>
                    </div>
                    <div class="space-y-4">
                        @foreach(['website', 'github', 'linkedin', 'twitter', 'facebook'] as $platform)
                            @if(auth()->user()->$platform)
                                <a href="{{ auth()->user()->$platform }}" target="_blank"
                                   class="flex items-center gap-3 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-pink-500 transition-colors">
                                    <i class="las la-{{ $platform }} text-2xl"></i>
                                    <span class="font-ubuntu-regular">{{ ucfirst($platform) }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Right Column -->
            <div class="lg:col-span-2 space-y-6 sm:space-y-8">
                @if(auth()->user()->bio)
                    <!-- Bio -->
                    <div class="bg-white dark:bg-[#12122b] rounded-2xl p-4 sm:p-6 border border-gray-200 dark:border-gray-800">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-user text-blue-600 dark:text-pink-500"></i>
                            About Me
                        </h2>
                        <p class="text-gray-700 dark:text-gray-300 font-ubuntu-light">
                            {{ auth()->user()->bio }}
                        </p>
                    </div>
                @endif

                <!-- Experience -->
                @if(auth()->user()->experience)
                    <div class="bg-white dark:bg-[#12122b] rounded-2xl p-4 sm:p-6 border border-gray-200 dark:border-gray-800">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2 font-oxanium-semibold">
                                <i class="las la-briefcase text-blue-600 dark:text-pink-500"></i>
                                Work Experience
                            </h2>
                            <button onclick="openModal('editExperienceModal')" class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-pink-500 hover:bg-blue-50 dark:hover:bg-pink-500/10 rounded-lg transition-all">
                                <i class="las la-edit text-lg"></i>
                            </button>
                        </div>
                        <div class="space-y-6 font-ubuntu-light">
                            @php
                                $experienceData = json_decode(auth()->user()->experience, true);
                            @endphp
                            @if(isset($experienceData['position']))
                                @foreach($experienceData['position'] as $index => $position)
                                    @php
                                        $startDate = !empty($experienceData['start_date'][$index]) ?
                                            \Carbon\Carbon::parse($experienceData['start_date'][$index]) : null;
                                        $endDate = !empty($experienceData['end_date'][$index]) ?
                                            \Carbon\Carbon::parse($experienceData['end_date'][$index]) : null;
                                        $isCurrentlyWorking = isset($experienceData['currently_working'][$index]) &&
                                            ($experienceData['currently_working'][$index] === 'on' ||
                                             $experienceData['currently_working'][$index] === true);
                                    @endphp

                                    <div class="border-l-2 border-blue-500/20 dark:border-pink-500/20 pl-4 sm:pl-6 {{ !$loop->last ? 'pb-6' : '' }} relative">
                                        <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full {{ $loop->first ? 'bg-blue-500 dark:bg-pink-500' : 'bg-blue-500/50 dark:bg-pink-500/50' }}"></div>
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 sm:gap-0">
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-white font-ubuntu-medium">{{ $position }}</h3>
                                                <p class="text-blue-600 dark:text-pink-400">{{ $experienceData['company_name'][$index] ?? '' }}</p>
                                            </div>

                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $startDate ? $startDate->format('M Y') : '' }}
                                                {{ $isCurrentlyWorking ? '- Present' : ($endDate ? '- ' . $endDate->format('M Y') : '') }}
                                            </span>
                                        </div>
                                        @if(!empty($experienceData['description'][$index]))
                                            <p class="text-gray-700 dark:text-gray-300 mt-2 font-ubuntu-regular text-sm sm:text-base">
                                                {{ $experienceData['description'][$index] }}
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-600 dark:text-gray-400">No experience added yet.</p>
                            @endif
                        </div>
                    </div>
                @endif
                <!-- Recommended Jobs Section -->
                @if($recommendedJobs && $recommendedJobs->count() > 0)
                    <div class="bg-white dark:bg-[#12122b] rounded-2xl p-6 border border-gray-200 dark:border-gray-800">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2 font-oxanium-semibold">
                                <i class="las la-star text-blue-600 dark:text-pink-500"></i>
                                Recommended Jobs for You
                            </h2>
                            <a href="{{ route('profile.preferences') }}" 
                               class="text-blue-600 dark:text-pink-500 hover:text-blue-700 dark:hover:text-pink-400 text-sm font-ubuntu-medium flex items-center gap-1">
                                <i class="las la-cog"></i>
                                Customize
                            </a>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($recommendedJobs as $job)
                                <div class="bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 rounded-xl p-4 transition-all border border-gray-200 dark:border-gray-700 hover:border-blue-500/30 dark:hover:border-pink-500/30">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h3 class="text-gray-900 dark:text-white font-ubuntu-medium text-sm mb-1 line-clamp-2">
                                                <a href="{{ route('job.show', $job->slug) }}" class="hover:text-blue-600 dark:hover:text-pink-500 transition-colors">
                                                    {{ $job->title }}
                                                </a>
                                            </h3>
                                            <p class="text-gray-600 dark:text-gray-400 text-xs font-ubuntu-light">{{ $job->company }}</p>
                                        </div>
                                        @if($job->is_remote)
                                            <span class="bg-green-500/10 text-green-600 dark:text-green-400 px-2 py-1 rounded-lg text-xs font-ubuntu-medium">
                                                Remote
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                            @if($job->country)
                                                <span class="flex items-center gap-1">
                                                    <i class="las la-map-marker"></i>
                                                    {{ $job->country }}
                                                </span>
                                            @endif
                                            @if($job->category)
                                                <span class="bg-blue-500/10 dark:bg-pink-500/10 text-blue-600 dark:text-pink-400 px-2 py-1 rounded">
                                                    {{ $job->category->name }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-gray-500 font-ubuntu-light">
                                            {{ $job->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 text-center">
                            <a href="{{ route('job.index') }}?recommended=1" 
                               class="text-blue-600 dark:text-pink-500 hover:text-blue-700 dark:hover:text-pink-400 text-sm font-ubuntu-medium inline-flex items-center gap-1">
                                View All Recommended Jobs
                                <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endif
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
