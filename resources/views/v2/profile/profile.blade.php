@extends('v2.layouts.app')
@section('content')
    <!-- Profile Header -->
    <div class="bg-[#12122b] border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-6">
                <!-- Profile Image -->
                <div class="relative group">
                    <img src="{{asset('assets/images/profile.jpg')}}" alt="{{ auth()->user()->name }}"
                         class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover border-2 border-pink-500/20">
                    <div class="absolute inset-0 bg-black/50 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <button class="text-white hover:text-pink-500 transition-colors">
                            <i class="las la-camera text-2xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="flex-1 text-center sm:text-left">
                    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2 font-oxanium-bold">{{ auth()->user()->name }}</h1>
                    <p class="text-gray-400 mb-4 font-ubuntu-regular">{{ auth()->user()->occupation }}</p>
                    <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-4 text-sm font-ubuntu-light">
                        @if(auth()->user()->state && auth()->user()->country)
                            <span class="flex items-center gap-2 text-gray-400">
                            <i class="las la-map-marker"></i>
                            {{ auth()->user()->state }}, {{ auth()->user()->country }}
                        </span>
                        @endif
                        <span class="flex items-center gap-2 text-gray-400">
                        <i class="las la-envelope"></i>
                        <span class="hidden sm:inline">{{ auth()->user()->email }}</span>
                        <span class="sm:hidden">Email</span>
                    </span>
                        @if(auth()->user()->phone)
                            <span class="flex items-center gap-2 text-gray-400">
                            <i class="las la-phone"></i>
                            <span class="hidden sm:inline">{{ auth()->user()->phone }}</span>
                            <span class="sm:hidden">Phone</span>
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3 mt-4 sm:mt-0">
                    <button class="bg-white/5 hover:bg-white/10 text-white px-4 py-2 rounded-xl transition-colors flex items-center gap-2 font-ubuntu-medium text-sm sm:text-base">
                        <i class="las la-download"></i>
                        <span class="hidden sm:inline">Download CV</span>
                        <span class="sm:hidden">CV</span>
                    </button>
                    <a href="{{ route('profile.update') }}" class="bg-gradient-to-r from-pink-500 to-purple-600 hover:opacity-90 text-white px-4 sm:px-6 py-2 rounded-xl transition-all flex items-center gap-2 font-oxanium-semibold text-sm sm:text-base">
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
                <div class="bg-[#12122b] rounded-2xl p-4 sm:p-6 border border-gray-800">
                    <h2 class="text-xl font-semibold text-white mb-6 flex items-center gap-2 font-oxanium-semibold">
                        <i class="las la-user-circle text-pink-500"></i>
                        Personal Information
                    </h2>
                    <div class="space-y-4 font-ubuntu-light">
                        <div>
                            <label class="text-sm text-gray-400 block mb-1">Full Name</label>
                            <div class="text-white font-ubuntu-regular">{{ auth()->user()->name }}</div>
                        </div>
                        @if(auth()->user()->dob)
                            <div>
                                <label class="text-sm text-gray-400 block mb-1">Date of Birth</label>
                                <div class="text-white font-ubuntu-regular">{{ Carbon\Carbon::parse(auth()->user()->dob)->format('F d, Y') }}</div>
                            </div>
                        @endif
                        <div>
                            <label class="text-sm text-gray-400 block mb-1">Email</label>
                            <div class="text-white font-ubuntu-regular break-all">{{ auth()->user()->email }}</div>
                        </div>
                        @if(auth()->user()->phone)
                            <div>
                                <label class="text-sm text-gray-400 block mb-1">Phone</label>
                                <div class="text-white font-ubuntu-regular">{{ auth()->user()->phone }}</div>
                            </div>
                        @endif
                        @if(auth()->user()->address)
                            <div>
                                <label class="text-sm text-gray-400 block mb-1">Address</label>
                                <div class="text-white font-ubuntu-regular">{{ auth()->user()->address }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Skills -->
                <div class="bg-[#12122b] rounded-2xl p-4 sm:p-6 border border-gray-800">
                    <h2 class="text-xl font-semibold text-white mb-6 flex items-center gap-2 font-oxanium-semibold">
                        <i class="las la-tools text-pink-500"></i>
                        Skills
                    </h2>
                    <div class="flex flex-wrap gap-2 font-ubuntu-medium">
                        @if(auth()->user()->skills)
                            @foreach(json_decode(auth()->user()->skills, true)['skill'] ?? [] as $index => $skill)
                                @php
                                    $skillLevel = json_decode(auth()->user()->skills,true)['skill_level'][$index];
                                    $percentage = match ($skillLevel) {
                                        App\Enums\SkillProficiency::BEGINNER->value => 'bg-pink-500/30',
                                        App\Enums\SkillProficiency::INTERMEDIATE->value => 'bg-pink-500/50',
                                        App\Enums\SkillProficiency::PROFICIENT->value => 'bg-pink-500/70',
                                        default => 'bg-pink-500/10'
                                    };
                                @endphp
                                <span class="px-3 sm:px-4 py-2 {{ $percentage }} text-pink-300 rounded-xl text-sm">
                                {{ $skill }}
                            </span>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Social Links -->
                <div class="bg-[#12122b] rounded-2xl p-4 sm:p-6 border border-gray-800">
                    <h2 class="text-xl font-semibold text-white mb-6 flex items-center gap-2 font-oxanium-semibold">
                        <i class="las la-share-alt text-pink-500"></i>
                        Social Links
                    </h2>
                    <div class="space-y-4">
                        @foreach(['website', 'github', 'linkedin', 'twitter', 'facebook'] as $platform)
                            @if(auth()->user()->$platform)
                                <a href="{{ auth()->user()->$platform }}" target="_blank"
                                   class="flex items-center gap-3 text-gray-400 hover:text-pink-500 transition-colors">
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
                    <div class="bg-[#12122b] rounded-2xl p-4 sm:p-6 border border-gray-800">
                        <h2 class="text-xl font-semibold text-white mb-6 flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-user text-pink-500"></i>
                            About Me
                        </h2>
                        <p class="text-gray-300 font-ubuntu-light">
                            {{ auth()->user()->bio }}
                        </p>
                    </div>
                @endif

                <!-- Experience -->
                @if(auth()->user()->experience)
                        <div class="bg-[#12122b] rounded-2xl p-4 sm:p-6 border border-gray-800">
                            <h2 class="text-xl font-semibold text-white mb-6 flex items-center gap-2 font-oxanium-semibold">
                                <i class="las la-briefcase text-pink-500"></i>
                                Work Experience
                            </h2>
                            <div class="space-y-6 font-ubuntu-light">
                                @foreach(json_decode(auth()->user()->experience, true)['position'] ?? [] as $index => $position)
                                    @php
                                        $experience = json_decode(auth()->user()->experience, true);
                                        $startDate = isset($experience['start_date'][$index]) ? \Carbon\Carbon::parse($experience['start_date'][$index]) : null;
                                        $endDate = isset($experience['end_date'][$index]) ? \Carbon\Carbon::parse($experience['end_date'][$index]) : null;
                                        $isCurrentlyWorking = isset($experience['currently_working'][$index]) && $experience['currently_working'][$index];
                                    @endphp

                                    <div class="border-l-2 border-pink-500/20 pl-4 sm:pl-6 {{ !$loop->last ? 'pb-6' : '' }} relative">
                                        <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full {{ $loop->first ? 'bg-pink-500' : 'bg-pink-500/50' }}"></div>
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 sm:gap-0">
                                            <div>
                                                <h3 class="text-lg font-medium text-white font-ubuntu-medium">{{ $position }}</h3>
                                                <p class="text-pink-400">{{ $experience['company_name'][$index] }}</p>
                                            </div>

                                            <span class="text-sm text-gray-400">{{ $startDate ? $startDate->format('M Y') : '' }} -
                                                    {{ $isCurrentlyWorking ? 'Present' : ($endDate ? $endDate->format('M Y') : '') }}
                                            </span>
                                        </div>
                                        @if(isset($experience['description'][$index]))
                                            <p class="text-gray-300 mt-2 font-ubuntu-regular text-sm sm:text-base">
                                                {{ $experience['description'][$index] }}
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
            </div>
        </div>
    </div>
@endsection
