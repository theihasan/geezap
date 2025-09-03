@extends('v2.layouts.app')
@section('content')
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-[#12122b] dark:via-[#1a1a3e] dark:to-[#2a1a4a] border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-12 sm:py-16">
            <div class="text-center">
                <!-- Coming Soon Badge -->
                <div class="inline-flex items-center gap-3 bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 px-6 py-3 rounded-full mb-6 border-2 border-blue-200 dark:border-purple-700">
                    <i class="las la-rocket text-2xl text-blue-600 dark:text-purple-400"></i>
                    <span class="text-blue-700 dark:text-purple-300 font-ubuntu-bold">Premium Job Alerts</span>
                    <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white text-xs px-3 py-1 rounded-full font-ubuntu-bold animate-pulse">COMING SOON</div>
                </div>
                
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 dark:text-white mb-4 font-oxanium-bold">
                    All Jobs, One Place, Zero Hassle
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300 font-ubuntu-regular max-w-3xl mx-auto leading-relaxed mb-6">
                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent font-ubuntu-bold">Coming Soon:</span> Stop checking LinkedIn, Indeed, ZipRecruiter and dozens of other sites. Get all relevant jobs from every major platform delivered to your inbox, perfectly filtered to your preferences.
                </p>
                
                <!-- Waitlist Social Proof -->
                <div class="flex items-center justify-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex -space-x-2">
                        <img class="w-8 h-8 rounded-full border-2 border-white" src="https://images.unsplash.com/photo-1494790108755-2616c95e155d?w=40&h=40&fit=crop&crop=face" alt="">
                        <img class="w-8 h-8 rounded-full border-2 border-white" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=40&h=40&fit=crop&crop=face" alt="">
                        <img class="w-8 h-8 rounded-full border-2 border-white" src="https://images.unsplash.com/photo-1517841905240-472988babdf9?w=40&h=40&fit=crop&crop=face" alt="">
                    </div>
                    <span class="font-ubuntu-medium">1,234+ professionals already on the early access waitlist</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
        
        <!-- Step 1: What kind of work excites you? -->
        <div class="mb-12" x-data="{ selectedCategories: ['software-development'] }">
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-2 text-sm text-blue-600 dark:text-pink-400 font-ubuntu-bold mb-3">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-pink-900/30 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 dark:text-pink-400">1</span>
                    </div>
                    STEP ONE
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white font-oxanium-bold mb-2">
                    What kind of work excites you?
                </h2>
                <p class="text-gray-600 dark:text-gray-400 font-ubuntu-regular">
                    Choose the fields that match your interests and skills
                </p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @php
                    $categories = [
                        ['id' => 'software-development', 'name' => 'Software Development', 'icon' => 'code', 'color' => 'blue'],
                        ['id' => 'data-science', 'name' => 'Data Science', 'icon' => 'chart-line', 'color' => 'green'],
                        ['id' => 'design', 'name' => 'Design', 'icon' => 'paint-brush', 'color' => 'purple'],
                        ['id' => 'marketing', 'name' => 'Marketing', 'icon' => 'bullhorn', 'color' => 'orange'],
                        ['id' => 'sales', 'name' => 'Sales', 'icon' => 'handshake', 'color' => 'red'],
                        ['id' => 'product', 'name' => 'Product', 'icon' => 'cube', 'color' => 'indigo'],
                        ['id' => 'finance', 'name' => 'Finance', 'icon' => 'calculator', 'color' => 'emerald'],
                        ['id' => 'hr', 'name' => 'Human Resources', 'icon' => 'users', 'color' => 'pink'],
                    ];
                @endphp
                
                @foreach($categories as $category)
                    <button @click="selectedCategories.includes('{{ $category['id'] }}') ? selectedCategories = selectedCategories.filter(c => c !== '{{ $category['id'] }}') : selectedCategories.push('{{ $category['id'] }}')"
                            :class="selectedCategories.includes('{{ $category['id'] }}') ? 'ring-2 ring-{{ $category['color'] }}-500 bg-{{ $category['color'] }}-50 dark:bg-{{ $category['color'] }}-900/20 border-{{ $category['color'] }}-200 dark:border-{{ $category['color'] }}-700' : 'bg-white dark:bg-[#12122b] border-gray-200 dark:border-gray-700'"
                            class="p-4 rounded-xl border-2 hover:border-{{ $category['color'] }}-300 dark:hover:border-{{ $category['color'] }}-600 transition-all duration-200 text-center group hover:shadow-md">
                        <div :class="selectedCategories.includes('{{ $category['id'] }}') ? 'bg-{{ $category['color'] }}-500 text-white' : 'bg-gray-100 dark:bg-white/5 text-{{ $category['color'] }}-500'"
                             class="w-12 h-12 rounded-lg mx-auto mb-3 flex items-center justify-center transition-all duration-200">
                            <i class="las la-{{ $category['icon'] }} text-xl"></i>
                        </div>
                        <h3 :class="selectedCategories.includes('{{ $category['id'] }}') ? 'text-{{ $category['color'] }}-700 dark:text-{{ $category['color'] }}-300' : 'text-gray-700 dark:text-gray-300'"
                            class="text-sm font-ubuntu-bold transition-all duration-200">
                            {{ $category['name'] }}
                        </h3>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Step 2: How do you prefer to work? -->
        <div class="mb-12" x-data="{ workStyle: 'hybrid' }">
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-2 text-sm text-blue-600 dark:text-pink-400 font-ubuntu-bold mb-3">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-pink-900/30 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 dark:text-pink-400">2</span>
                    </div>
                    STEP TWO
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white font-oxanium-bold mb-2">
                    How do you prefer to work?
                </h2>
                <p class="text-gray-600 dark:text-gray-400 font-ubuntu-regular">
                    Choose your ideal work arrangement
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $workStyles = [
                        ['id' => 'remote', 'name' => 'Fully Remote', 'desc' => 'Work from anywhere', 'icon' => 'home', 'color' => 'green'],
                        ['id' => 'hybrid', 'name' => 'Hybrid', 'desc' => 'Mix of home & office', 'icon' => 'balance-scale', 'color' => 'blue'],
                        ['id' => 'office', 'name' => 'In Office', 'desc' => 'Traditional office setting', 'icon' => 'building', 'color' => 'purple'],
                    ];
                @endphp
                
                @foreach($workStyles as $style)
                    <button @click="workStyle = '{{ $style['id'] }}'"
                            :class="workStyle === '{{ $style['id'] }}' ? 'ring-2 ring-{{ $style['color'] }}-500 bg-{{ $style['color'] }}-50 dark:bg-{{ $style['color'] }}-900/20 border-{{ $style['color'] }}-200 dark:border-{{ $style['color'] }}-700' : 'bg-white dark:bg-[#12122b] border-gray-200 dark:border-gray-700'"
                            class="p-6 rounded-xl border-2 hover:border-{{ $style['color'] }}-300 dark:hover:border-{{ $style['color'] }}-600 transition-all duration-200 text-center hover:shadow-md">
                        <div :class="workStyle === '{{ $style['id'] }}' ? 'bg-{{ $style['color'] }}-500 text-white' : 'bg-gray-100 dark:bg-white/5 text-{{ $style['color'] }}-500'"
                             class="w-16 h-16 rounded-xl mx-auto mb-4 flex items-center justify-center transition-all duration-200">
                            <i class="las la-{{ $style['icon'] }} text-2xl"></i>
                        </div>
                        <h3 :class="workStyle === '{{ $style['id'] }}' ? 'text-{{ $style['color'] }}-700 dark:text-{{ $style['color'] }}-300' : 'text-gray-900 dark:text-white'"
                            class="text-lg font-ubuntu-bold mb-1 transition-all duration-200">
                            {{ $style['name'] }}
                        </h3>
                        <p :class="workStyle === '{{ $style['id'] }}' ? 'text-{{ $style['color'] }}-600 dark:text-{{ $style['color'] }}-400' : 'text-gray-500 dark:text-gray-400'"
                           class="text-sm transition-all duration-200">
                            {{ $style['desc'] }}
                        </p>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Step 3: What's your ideal salary? -->
        <div class="mb-12" x-data="{ salaryRange: '50k-75k' }">
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-2 text-sm text-blue-600 dark:text-pink-400 font-ubuntu-bold mb-3">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-pink-900/30 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 dark:text-pink-400">3</span>
                    </div>
                    STEP THREE
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white font-oxanium-bold mb-2">
                    What's your ideal salary?
                </h2>
                <p class="text-gray-600 dark:text-gray-400 font-ubuntu-regular">
                    Select your expected compensation range
                </p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @php
                    $salaryRanges = [
                        ['id' => 'under-30k', 'label' => 'Under $30k', 'desc' => 'Entry level'],
                        ['id' => '30k-50k', 'label' => '$30k - $50k', 'desc' => 'Junior level'],
                        ['id' => '50k-75k', 'label' => '$50k - $75k', 'desc' => 'Mid level'],
                        ['id' => '75k-100k', 'label' => '$75k - $100k', 'desc' => 'Senior level'],
                        ['id' => '100k-150k', 'label' => '$100k - $150k', 'desc' => 'Lead level'],
                        ['id' => '150k+', 'label' => '$150k+', 'desc' => 'Executive'],
                    ];
                @endphp
                
                @foreach($salaryRanges as $range)
                    <button @click="salaryRange = '{{ $range['id'] }}'"
                            :class="salaryRange === '{{ $range['id'] }}' ? 'ring-2 ring-green-500 bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-700' : 'bg-white dark:bg-[#12122b] border-gray-200 dark:border-gray-700'"
                            class="p-4 rounded-xl border-2 hover:border-green-300 dark:hover:border-green-600 transition-all duration-200 text-center hover:shadow-md">
                        <div class="mb-2">
                            <div :class="salaryRange === '{{ $range['id'] }}' ? 'text-green-600 dark:text-green-400' : 'text-gray-900 dark:text-white'"
                                 class="font-ubuntu-bold text-lg transition-all duration-200">
                                {{ $range['label'] }}
                            </div>
                            <div :class="salaryRange === '{{ $range['id'] }}' ? 'text-green-500 dark:text-green-500' : 'text-gray-500 dark:text-gray-400'"
                                 class="text-sm transition-all duration-200">
                                {{ $range['desc'] }}
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Step 4: Where would you like to work? -->
        <div class="mb-12" x-data="{ preferredLocation: 'anywhere' }">
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-2 text-sm text-blue-600 dark:text-pink-400 font-ubuntu-bold mb-3">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-pink-900/30 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 dark:text-pink-400">4</span>
                    </div>
                    STEP FOUR
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white font-oxanium-bold mb-2">
                    Where would you like to work?
                </h2>
                <p class="text-gray-600 dark:text-gray-400 font-ubuntu-regular">
                    Choose your preferred work location
                </p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @php
                    $locations = [
                        ['id' => 'anywhere', 'name' => 'Anywhere', 'flag' => '🌍', 'color' => 'blue'],
                        ['id' => 'us', 'name' => 'United States', 'flag' => '🇺🇸', 'color' => 'red'],
                        ['id' => 'uk', 'name' => 'United Kingdom', 'flag' => '🇬🇧', 'color' => 'indigo'],
                        ['id' => 'ca', 'name' => 'Canada', 'flag' => '🇨🇦', 'color' => 'red'],
                        ['id' => 'de', 'name' => 'Germany', 'flag' => '🇩🇪', 'color' => 'yellow'],
                        ['id' => 'fr', 'name' => 'France', 'flag' => '🇫🇷', 'color' => 'blue'],
                        ['id' => 'au', 'name' => 'Australia', 'flag' => '🇦🇺', 'color' => 'green'],
                        ['id' => 'nl', 'name' => 'Netherlands', 'flag' => '🇳🇱', 'color' => 'orange'],
                    ];
                @endphp
                
                @foreach($locations as $location)
                    <button @click="preferredLocation = '{{ $location['id'] }}'"
                            :class="preferredLocation === '{{ $location['id'] }}' ? 'ring-2 ring-{{ $location['color'] }}-500 bg-{{ $location['color'] }}-50 dark:bg-{{ $location['color'] }}-900/20 border-{{ $location['color'] }}-200 dark:border-{{ $location['color'] }}-700' : 'bg-white dark:bg-[#12122b] border-gray-200 dark:border-gray-700'"
                            class="p-4 rounded-xl border-2 hover:border-{{ $location['color'] }}-300 dark:hover:border-{{ $location['color'] }}-600 transition-all duration-200 text-center hover:shadow-md">
                        <div class="text-3xl mb-2">{{ $location['flag'] }}</div>
                        <div :class="preferredLocation === '{{ $location['id'] }}' ? 'text-{{ $location['color'] }}-700 dark:text-{{ $location['color'] }}-300' : 'text-gray-700 dark:text-gray-300'"
                             class="text-sm font-ubuntu-bold transition-all duration-200">
                            {{ $location['name'] }}
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Early Access Email Frequency Selection -->
        <div class="mb-12">
            <div class="text-center mb-8">
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white font-oxanium-bold mb-4">
                    Choose Your Email Frequency
                </h3>
                <p class="text-lg text-gray-600 dark:text-gray-400 font-ubuntu-regular max-w-2xl mx-auto">
                    Select how often you'd like to receive job alerts when our premium service launches.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto" x-data="{ selectedFrequency: 'daily' }">
                
                <!-- Weekly Digest -->
                <div @click="selectedFrequency = 'weekly'" 
                     :class="selectedFrequency === 'weekly' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-700' : 'bg-white dark:bg-[#12122b] border-gray-200 dark:border-gray-700'"
                     class="rounded-2xl p-6 border-2 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-200 cursor-pointer">
                    <div class="text-center mb-6">
                        <div :class="selectedFrequency === 'weekly' ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-white/5 text-blue-500'"
                             class="w-16 h-16 rounded-xl mx-auto mb-4 flex items-center justify-center transition-all duration-200">
                            <i class="las la-calendar-week text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white font-oxanium-bold mb-2">Weekly Digest</h4>
                        <p :class="selectedFrequency === 'weekly' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400'"
                           class="text-sm transition-all duration-200">Perfect for casual job seekers</p>
                    </div>
                    
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center gap-3">
                            <i class="las la-check text-green-500"></i>
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Jobs from 3 major sources</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="las la-check text-green-500"></i>
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Weekly summary every Sunday</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="las la-check text-green-500"></i>
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Basic filtering</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="las la-check text-green-500"></i>
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Up to 50 jobs per email</span>
                        </li>
                    </ul>
                    
                    <div class="text-center">
                        <div :class="selectedFrequency === 'weekly' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-900 dark:text-white'"
                             class="text-2xl font-bold font-oxanium-bold transition-all duration-200">Free</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Always free</div>
                    </div>
                </div>

                <!-- Daily Digest (Most Popular) -->
                <div @click="selectedFrequency = 'daily'" 
                     :class="selectedFrequency === 'daily' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-700' : 'bg-white dark:bg-[#12122b] border-gray-200 dark:border-gray-700'"
                     class="rounded-2xl p-6 border-2 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-200 cursor-pointer relative transform scale-105">
                    
                    <!-- Popular Badge -->
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-1 rounded-full text-xs font-ubuntu-bold">
                            🔥 MOST POPULAR
                        </div>
                    </div>
                    
                    <div class="text-center mb-6 mt-2">
                        <div :class="selectedFrequency === 'daily' ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-white/5 text-blue-500'"
                             class="w-16 h-16 rounded-xl mx-auto mb-4 flex items-center justify-center transition-all duration-200">
                            <i class="las la-calendar-day text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white font-oxanium-bold mb-2">Daily Digest</h4>
                        <p :class="selectedFrequency === 'daily' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400'"
                           class="text-sm transition-all duration-200">Perfect for active job seekers</p>
                    </div>
                    
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center gap-3">
                            <i class="las la-check text-green-500"></i>
                            <span class="text-gray-700 dark:text-gray-300 text-sm">All 15+ job sources</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="las la-check text-green-500"></i>
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Daily digest every morning</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="las la-check text-green-500"></i>
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Smart duplicate removal</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="las la-check text-green-500"></i>
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Advanced filtering</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="las la-check text-green-500"></i>
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Up to 100 jobs per email</span>
                        </li>
                    </ul>
                    
                    <div class="text-center">
                        <div :class="selectedFrequency === 'daily' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-900 dark:text-white'"
                             class="text-2xl font-bold font-oxanium-bold transition-all duration-200">$19<span class="text-lg text-gray-500">/month</span></div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Launch price</div>
                    </div>
                </div>

                <!-- Real-time Alerts -->
                <div @click="selectedFrequency = 'realtime'" 
                     :class="selectedFrequency === 'realtime' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-700' : 'bg-white dark:bg-[#12122b] border-gray-200 dark:border-gray-700'"
                     class="rounded-2xl p-6 border-2 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-200 cursor-pointer">
                    <div class="text-center mb-6">
                        <div :class="selectedFrequency === 'realtime' ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-white/5 text-blue-500'"
                             class="w-16 h-16 rounded-xl mx-auto mb-4 flex items-center justify-center transition-all duration-200">
                            <i class="las la-bolt text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white font-oxanium-bold mb-2">Real-time Alerts</h4>
                        <p :class="selectedFrequency === 'realtime' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400'"
                           class="text-sm transition-all duration-200">For urgent job hunting</p>
                    </div>
                    
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center gap-3">
                            <i class="las la-check text-green-500"></i>
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Everything in Daily</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="las la-check text-green-500"></i>
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Instant alerts as collected</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="las la-check text-green-500"></i>
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Multiple saved searches</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="las la-check text-green-500"></i>
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Priority collection</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="las la-check text-green-500"></i>
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Unlimited jobs per email</span>
                        </li>
                    </ul>
                    
                    <div class="text-center">
                        <div :class="selectedFrequency === 'realtime' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-900 dark:text-white'"
                             class="text-2xl font-bold font-oxanium-bold transition-all duration-200">$49<span class="text-lg text-gray-500">/month</span></div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Early bird price</div>
                    </div>
                </div>
            </div>
            
            <!-- Early Access Info -->
            <div class="text-center mt-8">
                <div class="inline-flex items-center gap-2 bg-blue-50 dark:bg-blue-900/20 px-4 py-2 rounded-full border border-blue-200 dark:border-blue-800">
                    <i class="las la-info-circle text-blue-600 dark:text-blue-400"></i>
                    <span class="text-blue-700 dark:text-blue-300 text-sm font-ubuntu-medium">Join the waitlist to get early access and launch discounts</span>
                </div>
            </div>
        </div>

        <!-- How Job Aggregation Works -->
        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 rounded-2xl p-8 mb-8 border border-indigo-200 dark:border-indigo-800">
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white font-oxanium-bold mb-2">
                    How Geezap Works
                </h3>
                <p class="text-gray-600 dark:text-gray-400">We're a job aggregator - we collect jobs from multiple platforms and deliver them to you in one place.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-4">1</div>
                    <h4 class="font-ubuntu-bold text-gray-900 dark:text-white mb-2">We Scan</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Our system continuously monitors LinkedIn, Indeed, ZipRecruiter, Facebook Groups, and more</p>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-4">2</div>
                    <h4 class="font-ubuntu-bold text-gray-900 dark:text-white mb-2">We Filter</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Remove duplicates, apply your preferences, and organize by relevance</p>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-pink-500 to-red-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-4">3</div>
                    <h4 class="font-ubuntu-bold text-gray-900 dark:text-white mb-2">We Collect</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Jobs are collected throughout the day - some instantly, others within hours</p>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-4">4</div>
                    <h4 class="font-ubuntu-bold text-gray-900 dark:text-white mb-2">You Receive</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Get a curated digest of all relevant jobs from all sources, delivered to your inbox</p>
                </div>
            </div>
        </div>

        <!-- Value Proposition -->
        <div class="bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-800/50 dark:to-blue-900/20 rounded-2xl p-8 mb-12 border border-gray-200 dark:border-gray-700">
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white font-oxanium-bold mb-2">
                    Why Geezap Premium Saves You Time
                </h3>
                <p class="text-gray-600 dark:text-gray-400">Stop checking 15+ job sites daily. We collect from everywhere so you don't have to.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="las la-layer-group text-2xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white font-oxanium-bold mb-2">Complete Coverage</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">We scan LinkedIn, Indeed, ZipRecruiter, AngelList, and 11+ more sources daily.</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="las la-filter text-2xl text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white font-oxanium-bold mb-2">Smart Filtering</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Remove duplicates, filter by your exact criteria, get only relevant jobs.</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="las la-clock text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white font-oxanium-bold mb-2">Time Savings</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Save 5+ hours per week. Get comprehensive job coverage in one organized email.</p>
                </div>
            </div>
        </div>

        <!-- Action Section -->
        <div class="text-center bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-2xl p-8 border border-blue-100 dark:border-blue-800">
            <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white font-oxanium-bold mb-2">
                    Ready to be first in line?
                </h3>
                <p class="text-gray-600 dark:text-gray-400 font-ubuntu-regular">
                    Join the waitlist and get exclusive early access when premium job alerts launch
                </p>
            </div>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8" x-data="{ email: '', selectedFrequency: 'daily' }">
                <!-- Email Signup Form -->
                <div class="bg-white dark:bg-[#12122b] rounded-xl p-6 border border-gray-200 dark:border-gray-700 max-w-md w-full">
                    <div class="text-center mb-4">
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white font-oxanium-bold mb-2">Get Early Access</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Be first to know when premium alerts launch</p>
                    </div>
                    
                    <form class="space-y-4">
                        <div>
                            <input x-model="email" type="email" placeholder="Enter your email address" 
                                   class="w-full p-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-700 dark:text-white font-ubuntu-medium focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-white/10 transition-all">
                        </div>
                        
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-ubuntu-bold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-[1.02] hover:shadow-lg">
                            <i class="las la-bell mr-2"></i>Join Waitlist
                        </button>
                        
                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                            <span x-text="selectedFrequency === 'weekly' ? 'Weekly' : selectedFrequency === 'daily' ? 'Daily' : 'Real-time'"></span> plan selected • No spam, unsubscribe anytime
                        </p>
                    </form>
                </div>
                
                <div class="text-center">
                    <div class="text-gray-400 dark:text-gray-500 text-sm mb-2">Or</div>
                    <a href="{{ route('job.index') }}?remote=1&category=1" 
                       class="inline-flex items-center gap-3 bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-700 dark:text-gray-300 font-ubuntu-bold py-3 px-6 rounded-xl transition-all duration-200 border border-gray-200 dark:border-gray-700">
                        <i class="las la-search text-xl"></i>
                        <span>Browse Jobs Now (Free)</span>
                    </a>
                </div>
            </div>
            
            <!-- Beta Tester Testimonials -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 pt-8 border-t border-blue-200 dark:border-blue-800">
                <div class="bg-white dark:bg-[#12122b] rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-3">
                        <img class="w-12 h-12 rounded-full" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=50&h=50&fit=crop&crop=face" alt="">
                        <div>
                            <div class="font-ubuntu-bold text-gray-900 dark:text-white">Mike Chen</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Beta Tester</div>
                        </div>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 text-sm italic">"Been testing the beta version - it's amazing how many opportunities I was missing by only checking LinkedIn and Indeed!"</p>
                </div>
                
                <div class="bg-white dark:bg-[#12122b] rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-3">
                        <img class="w-12 h-12 rounded-full" src="https://images.unsplash.com/photo-1494790108755-2616c95e155d?w=50&h=50&fit=crop&crop=face" alt="">
                        <div>
                            <div class="font-ubuntu-bold text-gray-900 dark:text-white">Sarah Johnson</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Early Access User</div>
                        </div>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 text-sm italic">"Can't wait for the full launch! The daily digest prototype saved me hours each week of manual searching."</p>
                </div>
            </div>
            
            <!-- Trust Indicators -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-8 border-t border-blue-200 dark:border-blue-800">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 font-oxanium-bold">15+</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Job sources covered</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400 font-oxanium-bold">5.2x</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">More jobs than single sites</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400 font-oxanium-bold">5hrs</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Weekly time saved</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-600 dark:text-orange-400 font-oxanium-bold">94%</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Coverage accuracy</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js for interactivity -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection