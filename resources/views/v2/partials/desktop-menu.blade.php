<div class="hidden md:flex items-center space-x-1">
    <a href="{{route('job.index')}}" class="text-gray-700 dark:text-gray-100 hover:text-blue-600 dark:hover:text-pink-500 px-4 py-2 rounded-lg {{ request()->routeIs('job.index') ? 'bg-gray-100 dark:bg-white/10 text-blue-600 dark:text-pink-500' : 'hover:bg-gray-100 dark:hover:bg-white/5' }} transition-all flex items-center gap-2">
        <div class="w-8 h-8 rounded-lg bg-blue-500/10 dark:bg-pink-500/10 flex items-center justify-center">
            <i class="las la-briefcase text-blue-500 dark:text-pink-500"></i>
        </div>
        <span>Browse Jobs</span>
    </a>
    <a href="{{route('job.categories')}}" class="text-gray-700 dark:text-gray-100 hover:text-blue-600 dark:hover:text-pink-500 px-4 py-2 rounded-lg {{ request()->routeIs('job.categories') ? 'bg-gray-100 dark:bg-white/10 text-blue-600 dark:text-pink-500' : 'hover:bg-gray-100 dark:hover:bg-white/5' }} transition-all flex items-center gap-2">
        <div class="w-8 h-8 rounded-lg bg-blue-500/10 dark:bg-pink-500/10 flex items-center justify-center">
            <i class="las la-layer-group text-blue-500 dark:text-pink-500"></i>
        </div>
        <span>Categories</span>
    </a>
    <a href="{{route('job.preferences')}}" class="text-gray-700 dark:text-gray-100 hover:text-blue-600 dark:hover:text-pink-500 px-4 py-2 rounded-lg {{ request()->routeIs('job.preferences') ? 'bg-gray-100 dark:bg-white/10 text-blue-600 dark:text-pink-500' : 'hover:bg-gray-100 dark:hover:bg-white/5' }} transition-all flex items-center gap-2">
        <div class="w-8 h-8 rounded-lg bg-blue-500/10 dark:bg-pink-500/10 flex items-center justify-center">
            <i class="las la-sliders-h text-blue-500 dark:text-pink-500"></i>
        </div>
        <span>Preferences</span>
    </a>
    
    <!-- Theme Switcher -->
    <x-theme-switcher />
</div>
