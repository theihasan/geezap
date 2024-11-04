<!-- Mobile Menu Backdrop -->
<div class="md:hidden fixed inset-0 bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300"
     id="menu-backdrop"></div>

<!-- Mobile Menu -->
<div class="md:hidden fixed inset-y-0 right-0 w-[300px] bg-[#12122b]/95 backdrop-blur-md z-50 transform transition-all duration-300 ease-in-out translate-x-full opacity-0 shadow-2xl border-l border-white/10"
     id="mobile-menu">
    <div class="flex flex-col h-full">
        <!-- Mobile Menu Header -->
        <div class="flex justify-between items-center p-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <img src="https://placehold.co/40x40" alt="Profile" class="w-10 h-10 rounded-lg object-cover">
                <div>
                    <div class="font-medium text-white">John Doe</div>
                    <div class="text-sm text-gray-400">john@example.com</div>
                </div>
            </div>
            <button onclick="toggleMobileMenu()" class="w-10 h-10 rounded-lg bg-white/5 hover:bg-white/10 transition-colors flex items-center justify-center text-white">
                <i class="las la-times text-xl"></i>
            </button>
        </div>

        <!-- Mobile Menu Links -->
        <div class="flex flex-col p-6 space-y-2">
            <a href="jobs.html"
               class="text-gray-100 hover:text-pink-500 py-4 transition-all duration-200 transform translate-x-4 opacity-0 mobile-menu-item group flex items-center justify-between rounded-xl hover:bg-white/5 px-4">
                    <span class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-pink-500/10 flex items-center justify-center group-hover:bg-pink-500/20 transition-colors">
                            <i class="las la-briefcase text-pink-500"></i>
                        </div>
                        Browse Jobs
                    </span>
                <i class="las la-arrow-right opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all"></i>
            </a>
            <a href="categories.html"
               class="text-gray-100 hover:text-pink-500 py-4 transition-all duration-200 transform translate-x-4 opacity-0 mobile-menu-item group flex items-center justify-between rounded-xl hover:bg-white/5 px-4">
                    <span class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-pink-500/10 flex items-center justify-center group-hover:bg-pink-500/20 transition-colors">
                            <i class="las la-layer-group text-pink-500"></i>
                        </div>
                        Categories
                    </span>
                <i class="las la-arrow-right opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all"></i>
            </a>

            <!-- Profile Links -->
            <div class="border-t border-white/10 mt-4 pt-4">
                <a href="view-profile.html"
                   class="text-gray-100 hover:text-pink-500 py-4 transition-all duration-200 transform translate-x-4 opacity-0 mobile-menu-item group flex items-center justify-between rounded-xl hover:bg-white/5 px-4">
                        <span class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-pink-500/10 flex items-center justify-center group-hover:bg-pink-500/20 transition-colors">
                                <i class="las la-user-circle text-pink-500"></i>
                            </div>
                            View Profile
                        </span>
                    <i class="las la-arrow-right opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all"></i>
                </a>
                <a href="my-applications.html"
                   class="text-gray-100 hover:text-pink-500 py-4 transition-all duration-200 transform translate-x-4 opacity-0 mobile-menu-item group flex items-center justify-between rounded-xl hover:bg-white/5 px-4">
                        <span class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-pink-500/10 flex items-center justify-center group-hover:bg-pink-500/20 transition-colors">
                                <i class="las la-user-circle text-pink-500"></i>
                            </div>
                           My Applications
                        </span>
                    <i class="las la-info opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all"></i>
                </a>
            </div>
        </div>

        <!-- Mobile Menu Footer -->
        <div class="mt-auto p-6 space-y-4 border-t border-white/10">
            <a href="#" class="w-full bg-white/5 hover:bg-white/10 text-red-400 hover:text-red-500 px-6 py-3 rounded-xl transition-colors flex items-center justify-center gap-2">
                <i class="las la-sign-out-alt"></i>
                Sign Out
            </a>
        </div>
    </div>
</div>
