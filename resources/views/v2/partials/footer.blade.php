<footer class="py-12 bg-gray-50 dark:bg-secondary border-t border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div>
                <h4 class="text-lg font-ubuntu-bold mb-4 text-gray-900 dark:text-white">For Developers</h4>
                <ul class="space-y-2 font-ubuntu-medium">
                    <li><a href="/jobs" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-pink-400 transition">Browse Jobs</a></li>
                    <li><a href="/categories" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-pink-400 transition">Categories</a></li>
                    <li><a href="/dashboard" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-pink-400 transition">My Profile</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-ubuntu-bold mb-4 text-gray-900 dark:text-white">Company</h4>
                <ul class="space-y-2 font-ubuntu-medium">
                    <li><a href="/" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-pink-400 transition">Home</a></li>
                    <li><a href="/about" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-pink-400 transition">About Us</a></li>
                    <li><a href="/contact" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-pink-400 transition">Contact</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-ubuntu-bold mb-4 text-gray-900 dark:text-white">Connect</h4>
                <ul class="space-y-2 font-ubuntu-medium">
                    <li><a href="https://facebook.com/geezap247" target="_blank" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-pink-400 transition">Facebook</a></li>
                    <li><a href="https://github.com/theihasan/geezap" target="_blank" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-pink-400 transition">GitHub</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-ubuntu-bold mb-4 text-gray-900 dark:text-white">Legal</h4>
                <ul class="space-y-2 font-ubuntu-medium">
                    <li><a href="/privacy-policy" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-pink-400 transition">Privacy Policy</a></li>
                    <li><a href="/terms" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-pink-400 transition">Terms of Service</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-800 text-center text-gray-600 dark:text-gray-300 font-ubuntu">
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</footer>

@livewireScripts

<script>
    window.addEventListener('load', () => {
        if ('requestIdleCallback' in window) {
            requestIdleCallback(() => {
                const analyticsScript = document.createElement('script');
                analyticsScript.src = 'https://scripts.simpleanalyticscdn.com/latest.js';
                analyticsScript.async = true;
                document.body.appendChild(analyticsScript);
            });
        } else {
            setTimeout(() => {
                const analyticsScript = document.createElement('script');
                analyticsScript.src = 'https://scripts.simpleanalyticscdn.com/latest.js';
                analyticsScript.async = true;
                document.body.appendChild(analyticsScript);
            }, 2000);
        }
    });
</script>
@stack('extra-js')

</body>
</html>
