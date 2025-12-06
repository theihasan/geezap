@php use App\Enums\SkillProficiency; @endphp
@extends('v2.layouts.app')
@section('content')
    <!-- Hero Section with Profile Banner -->
    <div class="relative bg-gradient-to-br from-blue-600/5 via-purple-600/5 to-pink-600/5 dark:from-blue-500/10 dark:via-purple-500/10 dark:to-pink-500/10">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
        
        <!-- Profile Hero Content -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 pt-8 pb-16">
            <!-- Profile Card -->
            <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-3xl p-8 shadow-2xl">
                <div class="flex flex-col lg:flex-row items-center lg:items-start gap-8">
                    <!-- Profile Image with Status -->
                    <div class="relative group">
                        <div class="relative">
                            <img src="{{asset('assets/images/profile.jpg')}}" alt="{{ auth()->user()->name }}" 
                                 class="w-32 h-32 lg:w-40 lg:h-40 rounded-3xl object-cover border-4 border-white dark:border-gray-800 shadow-lg">
                            <!-- Online Status -->
                            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 border-4 border-white dark:border-gray-800 rounded-full animate-pulse"></div>
                        </div>
                        <!-- Verification Badge -->
                        <div class="absolute -top-2 -right-2 bg-blue-500 text-white rounded-full p-2 shadow-lg">
                            <i class="las la-check text-sm"></i>
                        </div>
                    </div>

                    <!-- Profile Info -->
                    <div class="flex-1 text-center lg:text-left">
                        <!-- Name and Title -->
                        <div class="mb-4">
                            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-2 font-oxanium-bold">
                                {{ auth()->user()->name }}
                            </h1>
                            @if(auth()->user()->occupation)
                                <p class="text-xl text-gray-600 dark:text-gray-300 font-medium">{{ auth()->user()->occupation }}</p>
                            @endif
                        </div>

                        <!-- Quick Info -->
                        <div class="flex flex-wrap justify-center lg:justify-start gap-4 mb-6">
                            @if(auth()->user()->location || auth()->user()->country)
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500/10 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 rounded-full text-sm font-medium">
                                    <i class="las la-map-marker"></i>
                                    {{ auth()->user()->location ?? auth()->user()->country }}
                                </span>
                            @endif
                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-500/10 dark:bg-green-500/20 text-green-600 dark:text-green-400 rounded-full text-sm font-medium">
                                <i class="las la-calendar"></i>
                                Member since {{ auth()->user()->created_at->format('M Y') }}
                            </span>
                            @if(auth()->user()->jobs()->count() > 0)
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-purple-500/10 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400 rounded-full text-sm font-medium">
                                    <i class="las la-briefcase"></i>
                                    {{ auth()->user()->jobs()->count() }} Applications
                                </span>
                            @endif
                        </div>

                        <!-- Bio -->
                        @if(auth()->user()->bio)
                            <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed mb-6 max-w-2xl">
                                {{ auth()->user()->bio }}
                            </p>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                            <a href="{{ route('profile.update') }}" 
                               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 dark:from-pink-500 dark:to-purple-600 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 font-medium">
                                <i class="las la-edit text-lg"></i>
                                Edit Profile
                            </a>
                            <a href="{{ route('applications') }}" 
                               class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:border-blue-500 dark:hover:border-pink-500 hover:text-blue-500 dark:hover:text-pink-500 transition-all duration-200 font-medium">
                                <i class="las la-briefcase text-lg"></i>
                                My Applications
                            </a>
                        </div>
                    </div>

                    <!-- Stats Card -->
                    <div class="lg:max-w-sm w-full">
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800/50 dark:to-gray-700/50 rounded-2xl p-6 border border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i class="las la-chart-line text-blue-500"></i>
                                Profile Stats
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

                            <!-- Profile Completion -->
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Profile Complete</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $completionPercentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-700"
                                         style="width: {{ $completionPercentage }}%"></div>
                                </div>
                            </div>

                            <!-- Quick Stats -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->jobs()->count() }}</div>
                                    <div class="text-xs text-gray-500">Applications</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ round(auth()->user()->created_at->diffInDays()) }}</div>
                                    <div class="text-xs text-gray-500">Days Active</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- About Section -->
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 border border-gray-200 dark:border-gray-700 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-500/10 dark:bg-blue-500/20 rounded-xl flex items-center justify-center">
                            <i class="las la-user text-blue-500 text-lg"></i>
                        </div>
                        About Me
                    </h2>
                    @if(auth()->user()->bio)
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-lg">
                            {{ auth()->user()->bio }}
                        </p>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <i class="las la-user-edit text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">No bio added yet</p>
                            <a href="{{ route('profile.update') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                <i class="las la-plus"></i>
                                Add Bio
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Experience Section -->
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-500/10 dark:bg-purple-500/20 rounded-xl flex items-center justify-center">
                                <i class="las la-briefcase text-purple-500 text-lg"></i>
                            </div>
                            Work Experience
                        </h2>
                        <a href="{{ route('profile.update') }}" 
                           class="text-blue-500 hover:text-blue-600 dark:text-pink-500 dark:hover:text-pink-400 text-sm font-medium flex items-center gap-1">
                            <i class="las la-edit"></i>
                            Edit
                        </a>
                    </div>

                    @php
                        $experience = auth()->user()->experience ? json_decode(auth()->user()->experience, true) : null;
                    @endphp

                    @if($experience && isset($experience['position']) && count($experience['position']) > 0)
                        <div class="space-y-6">
                            @foreach($experience['position'] as $index => $position)
                                @if(!empty($position))
                                    <div class="relative pl-8 pb-6 border-l-2 border-gray-200 dark:border-gray-700 last:border-l-0 last:pb-0">
                                        <!-- Timeline dot -->
                                        <div class="absolute -left-2.5 top-0 w-5 h-5 bg-blue-500 rounded-full border-2 border-white dark:border-gray-900"></div>
                                        
                                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-6">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $position }}</h3>
                                                @if(isset($experience['currently_working'][$index]) && $experience['currently_working'][$index])
                                                    <span class="inline-flex items-center px-3 py-1 bg-green-500/10 text-green-600 dark:text-green-400 rounded-full text-sm font-medium mt-2 sm:mt-0">
                                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                                        Current Position
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <div class="text-blue-600 dark:text-blue-400 font-medium mb-2">
                                                {{ $experience['company_name'][$index] ?? 'Company Name' }}
                                            </div>
                                            
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                                @if(isset($experience['start_date'][$index]))
                                                    {{ date('M Y', strtotime($experience['start_date'][$index])) }}
                                                @endif
                                                -
                                                @if(isset($experience['currently_working'][$index]) && $experience['currently_working'][$index])
                                                    Present
                                                @elseif(isset($experience['end_date'][$index]))
                                                    {{ date('M Y', strtotime($experience['end_date'][$index])) }}
                                                @else
                                                    Present
                                                @endif
                                            </div>
                                            
                                            @if(!empty($experience['description'][$index]))
                                                <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                                    {{ $experience['description'][$index] }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <i class="las la-briefcase text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">No work experience added yet</p>
                            <a href="{{ route('profile.update') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                <i class="las la-plus"></i>
                                Add Experience
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Skills Section -->
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-500/10 dark:bg-green-500/20 rounded-xl flex items-center justify-center">
                                <i class="las la-tools text-green-500 text-lg"></i>
                            </div>
                            Skills & Expertise
                        </h2>
                        <a href="{{ route('profile.update') }}" 
                           class="text-blue-500 hover:text-blue-600 dark:text-pink-500 dark:hover:text-pink-400 text-sm font-medium flex items-center gap-1">
                            <i class="las la-edit"></i>
                            Edit
                        </a>
                    </div>

                    @php
                        $skills = auth()->user()->skills ? json_decode(auth()->user()->skills, true) : null;
                    @endphp

                    @if($skills && isset($skills['skill']) && count($skills['skill']) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($skills['skill'] as $index => $skill)
                                @if(!empty($skill))
                                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $skill }}</span>
                                            <span class="text-xs px-2 py-1 rounded-full
                                                @if(($skills['skill_level'][$index] ?? '') === SkillProficiency::PROFICIENT->value) bg-green-500/10 text-green-600 dark:text-green-400
                                                @elseif(($skills['skill_level'][$index] ?? '') === SkillProficiency::INTERMEDIATE->value) bg-yellow-500/10 text-yellow-600 dark:text-yellow-400
                                                @else bg-blue-500/10 text-blue-600 dark:text-blue-400 @endif">
                                                {{ ucfirst($skills['skill_level'][$index] ?? 'beginner') }}
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="h-2 rounded-full transition-all duration-700
                                                @if(($skills['skill_level'][$index] ?? '') === SkillProficiency::PROFICIENT->value) bg-green-500 w-5/6
                                                @elseif(($skills['skill_level'][$index] ?? '') === SkillProficiency::INTERMEDIATE->value) bg-yellow-500 w-3/5
                                                @else bg-blue-500 w-2/5 @endif">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <i class="las la-tools text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">No skills added yet</p>
                            <a href="{{ route('profile.update') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                <i class="las la-plus"></i>
                                Add Skills
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Contact Information -->
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 border border-gray-200 dark:border-gray-700 shadow-sm">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                        <div class="w-8 h-8 bg-orange-500/10 dark:bg-orange-500/20 rounded-lg flex items-center justify-center">
                            <i class="las la-address-card text-orange-500"></i>
                        </div>
                        Contact Info
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                            <i class="las la-envelope text-blue-500 text-lg"></i>
                            <div class="min-w-0 flex-1">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Email</div>
                                <div class="text-gray-900 dark:text-white truncate">{{ auth()->user()->email }}</div>
                            </div>
                        </div>

                        @if(auth()->user()->phone)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                                <i class="las la-phone text-green-500 text-lg"></i>
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Phone</div>
                                    <div class="text-gray-900 dark:text-white">{{ auth()->user()->phone }}</div>
                                </div>
                            </div>
                        @endif

                        @if(auth()->user()->address)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                                <i class="las la-map-marker text-red-500 text-lg"></i>
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Address</div>
                                    <div class="text-gray-900 dark:text-white">{{ auth()->user()->address }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Social Links -->
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 border border-gray-200 dark:border-gray-700 shadow-sm">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                        <div class="w-8 h-8 bg-pink-500/10 dark:bg-pink-500/20 rounded-lg flex items-center justify-center">
                            <i class="las la-share-alt text-pink-500"></i>
                        </div>
                        Social Links
                    </h3>

                    @php
                        $socialLinks = collect(['website', 'github', 'linkedin', 'twitter', 'facebook'])
                            ->filter(fn($platform) => auth()->user()->$platform);
                    @endphp

                    @if($socialLinks->count() > 0)
                        <div class="space-y-3">
                            @foreach($socialLinks as $platform)
                                <a href="{{ auth()->user()->$platform }}" target="_blank"
                                   class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors group">
                                    <i class="lab la-{{ $platform }} text-lg 
                                        @if($platform === 'website') text-gray-600 dark:text-gray-400
                                        @elseif($platform === 'github') text-gray-800 dark:text-gray-300
                                        @elseif($platform === 'linkedin') text-blue-600
                                        @elseif($platform === 'twitter') text-blue-400
                                        @elseif($platform === 'facebook') text-blue-500
                                        @endif"></i>
                                    <div class="flex-1">
                                        <div class="text-sm text-gray-500 dark:text-gray-400 capitalize">{{ $platform }}</div>
                                        <div class="text-gray-900 dark:text-white group-hover:text-blue-500 dark:group-hover:text-pink-500 transition-colors">
                                            {{ Str::limit(auth()->user()->$platform, 30) }}
                                        </div>
                                    </div>
                                    <i class="las la-external-link-alt text-gray-400 group-hover:text-blue-500 dark:group-hover:text-pink-500 transition-colors"></i>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <i class="las la-link text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">No social links added yet</p>
                            <a href="{{ route('profile.update') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm">
                                <i class="las la-plus"></i>
                                Add Social Links
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-3xl p-8 border border-blue-200 dark:border-blue-700/50">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('profile.update') }}" 
                           class="flex items-center gap-3 p-4 bg-white/80 dark:bg-gray-800/80 rounded-xl hover:bg-white dark:hover:bg-gray-800 transition-colors shadow-sm">
                            <div class="w-10 h-10 bg-blue-500/10 rounded-lg flex items-center justify-center">
                                <i class="las la-edit text-blue-500"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">Edit Profile</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Update your information</div>
                            </div>
                        </a>
                        
                        <a href="{{ route('applications') }}" 
                           class="flex items-center gap-3 p-4 bg-white/80 dark:bg-gray-800/80 rounded-xl hover:bg-white dark:hover:bg-gray-800 transition-colors shadow-sm">
                            <div class="w-10 h-10 bg-purple-500/10 rounded-lg flex items-center justify-center">
                                <i class="las la-briefcase text-purple-500"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">My Applications</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">View job applications</div>
                            </div>
                        </a>
                        
                        <a href="{{ route('profile.preferences') }}" 
                           class="flex items-center gap-3 p-4 bg-white/80 dark:bg-gray-800/80 rounded-xl hover:bg-white dark:hover:bg-gray-800 transition-colors shadow-sm">
                            <div class="w-10 h-10 bg-green-500/10 rounded-lg flex items-center justify-center">
                                <i class="las la-cog text-green-500"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">Preferences</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Manage settings</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-grid-pattern {
            background-image: radial-gradient(circle at 1px 1px, rgb(0 0 0 / 0.15) 1px, transparent 0);
            background-size: 20px 20px;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection