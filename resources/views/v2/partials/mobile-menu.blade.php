<!-- Mobile Menu Backdrop -->
<div class="md:hidden fixed inset-0 bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300"
     id="menu-backdrop"></div>

<!-- Mobile Menu -->
<div class="md:hidden fixed inset-y-0 right-0 w-[300px] bg-white/95 dark:bg-[#12122b]/95 backdrop-blur-md z-50 transform transition-all duration-300 ease-in-out translate-x-full opacity-0 shadow-2xl border-l border-gray-200 dark:border-white/10"
     id="mobile-menu">
    <div class="flex flex-col h-full">
        <!-- Mobile Menu Header -->
        @if(auth()->check())
        <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-white/10">
            <div class="flex items-center gap-3">
                <img src="https://placehold.co/40x40" alt="Profile" class="w-10 h-10 rounded-lg object-cover">
                <div>
                    <div class="font-medium text-gray-900 dark:text-white">{{auth()->user()->name}}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">{{auth()->user()->email}}</div>
                </div>
            </div>
            <button onclick="toggleMobileMenu()" class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 transition-colors flex items-center justify-center text-gray-900 dark:text-white">
                <i class="las la-times text-xl"></i>
            </button>
        </div>
        @endif
        <!-- Mobile Menu Links -->
        <!-- Mobile Menu Links -->
        <div class="flex flex-col p-6 space-y-2">
            <a href="{{route('job.index')}}"
               class="text-gray-700 dark:text-gray-100 hover:text-blue-600 dark:hover:text-pink-500 py-4 transition-all duration-200 transform translate-x-4 opacity-0 mobile-menu-item group flex items-center justify-between rounded-xl {{ request()->routeIs('job.index') ? 'bg-gray-100 dark:bg-white/10 text-blue-600 dark:text-pink-500' : 'hover:bg-gray-100 dark:hover:bg-white/5' }} px-4">
            <span class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-500/10 dark:bg-pink-500/10 flex items-center justify-center group-hover:bg-blue-500/20 dark:group-hover:bg-pink-500/20 transition-colors">
                    <i class="las la-briefcase text-blue-500 dark:text-pink-500"></i>
                </div>
                Browse Jobs
            </span>
                <i class="las la-arrow-right opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all"></i>
            </a>
            <a href="{{route('job.categories')}}"
               class="text-gray-700 dark:text-gray-100 hover:text-blue-600 dark:hover:text-pink-500 py-4 transition-all duration-200 transform translate-x-4 opacity-0 mobile-menu-item group flex items-center justify-between rounded-xl {{ request()->routeIs('job.categories') ? 'bg-gray-100 dark:bg-white/10 text-blue-600 dark:text-pink-500' : 'hover:bg-gray-100 dark:hover:bg-white/5' }} px-4">
            <span class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-500/10 dark:bg-pink-500/10 flex items-center justify-center group-hover:bg-blue-500/20 dark:group-hover:bg-pink-500/20 transition-colors">
                    <i class="las la-layer-group text-blue-500 dark:text-pink-500"></i>
                </div>
                Categories
            </span>
                <i class="las la-arrow-right opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all"></i>
            </a>
            <a href="{{route('job.preferences')}}"
               class="text-gray-700 dark:text-gray-100 hover:text-blue-600 dark:hover:text-pink-500 py-4 transition-all duration-200 transform translate-x-4 opacity-0 mobile-menu-item group flex items-center justify-between rounded-xl {{ request()->routeIs('job.preferences') ? 'bg-gray-100 dark:bg-white/10 text-blue-600 dark:text-pink-500' : 'hover:bg-gray-100 dark:hover:bg-white/5' }} px-4">
            <span class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-500/10 dark:bg-pink-500/10 flex items-center justify-center group-hover:bg-blue-500/20 dark:group-hover:bg-pink-500/20 transition-colors">
                    <i class="las la-sliders-h text-blue-500 dark:text-pink-500"></i>
                </div>
                Job Preferences
            </span>
                <i class="las la-arrow-right opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all"></i>
            </a>

            <!-- Theme Switcher for Mobile -->
            <div class="flex items-center justify-between py-4 px-4 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                <span class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-500/10 dark:bg-pink-500/10 flex items-center justify-center">
                        <i class="las la-moon text-blue-500 dark:text-pink-500"></i>
                    </div>
                    <span class="text-gray-700 dark:text-gray-100">Theme</span>
                </span>
                <x-theme-switcher />
            </div>

            <!-- Mobile Menu Footer -->
        <div class="mt-auto p-6 space-y-4 border-t border-gray-200 dark:border-white/10">
            <a href="{{route('logout')}}" class="w-full bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-500 px-6 py-3 rounded-xl transition-colors flex items-center justify-center gap-2">
                <i class="las la-sign-out-alt"></i>
                Sign Out
            </a>
        </div>

    </div>
</div>
