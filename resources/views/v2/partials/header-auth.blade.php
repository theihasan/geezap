<div class="hidden md:flex items-center space-x-4">
    <!-- Login Button -->
    <a href="login.html" class="bg-white/5 hover:bg-white/10 text-white px-4 py-2 rounded-xl transition-colors flex items-center gap-2">
        <i class="las la-sign-in-alt"></i>
        Login
    </a>


    <!-- Profile Button with Image -->
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" @click.away="open = false"
                class="flex items-center gap-2 text-gray-100 hover:text-pink-500 px-2 py-2 rounded-lg hover:bg-white/5 transition-all">
            <img src="https://placehold.co/32x32" alt="Profile" class="w-8 h-8 rounded-lg object-cover">
            <span>John Doe</span>
            <i class="las la-angle-down transition-transform" :class="{ 'rotate-180': open }"></i>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute right-0 mt-2 w-48 rounded-xl bg-[#1a1a3a] border border-gray-700 shadow-xl z-50">
            <div class="p-2">
                <a href="view-profile.html" class="flex items-center gap-2 px-4 py-2 text-gray-100 hover:text-pink-500 rounded-lg hover:bg-white/5 transition-all">
                    <i class="las la-user-circle"></i>
                    View Profile
                </a>
                <a href="my-applications.html" class="flex items-center gap-2 px-4 py-2 text-gray-100 hover:text-pink-500 rounded-lg hover:bg-white/5 transition-all">
                    <i class="las la-briefcase"></i>
                    My Applications
                </a>
                <div class="border-t border-gray-700 my-2"></div>
                <a href="#" class="flex items-center gap-2 px-4 py-2 text-red-400 hover:text-red-500 rounded-lg hover:bg-white/5 transition-all">
                    <i class="las la-sign-out-alt"></i>
                    Sign Out
                </a>
            </div>
        </div>
    </div>
</div>
