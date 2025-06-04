<div class="hidden md:flex items-center space-x-1">
    <a href="{{route('job.index')}}" class="text-gray-100 hover:text-pink-500 px-4 py-2 rounded-lg {{ request()->routeIs('job.index') ? 'bg-white/10 text-pink-500' : 'hover:bg-white/5' }} transition-all flex items-center gap-2">
        <div class="w-8 h-8 rounded-lg bg-pink-500/10 flex items-center justify-center">
            <i class="las la-briefcase text-pink-500"></i>
        </div>
        <span>Browse Jobs</span>
    </a>
    <a href="{{route('job.categories')}}" class="text-gray-100 hover:text-pink-500 px-4 py-2 rounded-lg {{ request()->routeIs('job.categories') ? 'bg-white/10 text-pink-500' : 'hover:bg-white/5' }} transition-all flex items-center gap-2">
        <div class="w-8 h-8 rounded-lg bg-pink-500/10 flex items-center justify-center">
            <i class="las la-layer-group text-pink-500"></i>
        </div>
        <span>Categories</span>
    </a>
</div>
