<footer class="py-12 bg-[#12122b] border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div>
                <h4 class="text-lg font-ubuntu-bold mb-4 text-white">For Developers</h4>
                <ul class="space-y-2 font-ubuntu-medium">
                    <li><a href="#" class="text-gray-300 hover:text-pink-400">Browse Jobs</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-pink-400">Companies</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-pink-400">Salary Guide</a></li>
                </ul>
            </div>
            <!-- More footer sections... -->
        </div>
        <div class="mt-12 pt-8 border-t border-gray-800 text-center text-gray-300 font-ubuntu">
            <p>&copy; 2024 DevJobs. All rights reserved.</p>
        </div>
    </div>
</footer>

<script>
    let isMenuOpen = false;

    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        const menuBackdrop = document.getElementById('menu-backdrop');
        const menuItems = document.querySelectorAll('.mobile-menu-item');
        const menuIcon = document.getElementById('menu-toggle').querySelector('i');

        isMenuOpen = !isMenuOpen;

        if (isMenuOpen) {
            menuBackdrop.classList.remove('opacity-0', 'pointer-events-none');
            mobileMenu.classList.remove('translate-x-full', 'opacity-0');
            document.body.style.overflow = 'hidden';

            menuItems.forEach((item, index) => {
                setTimeout(() => {
                    item.classList.remove('translate-x-4', 'opacity-0');
                }, 200 + (index * 100));
            });

            menuIcon.style.transform = 'rotate(180deg)';
            menuIcon.classList.remove('la-bars');
            menuIcon.classList.add('la-times');
        } else {
            menuBackdrop.classList.add('opacity-0', 'pointer-events-none');
            mobileMenu.classList.add('translate-x-full', 'opacity-0');
            document.body.style.overflow = '';

            menuItems.forEach(item => {
                item.classList.add('translate-x-4', 'opacity-0');
            });

            menuIcon.style.transform = 'rotate(0deg)';
            menuIcon.classList.remove('la-times');
            menuIcon.classList.add('la-bars');
        }
    }
</script>
@stack('extra-js')
