<footer class="py-12 bg-secondary border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div>
                <h4 class="text-lg font-ubuntu-bold mb-4 text-white">For Developers</h4>
                <ul class="space-y-2 font-ubuntu-medium">
                    <li><a href="#" class="text-gray-300 hover:text-pink-400 transition">Browse Jobs</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-pink-400 transition">Companies</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-pink-400 transition">Salary Guide</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-ubuntu-bold mb-4 text-white">For Employers</h4>
                <ul class="space-y-2 font-ubuntu-medium">
                    <li><a href="#" class="text-gray-300 hover:text-pink-400 transition">Post a Job</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-pink-400 transition">Pricing</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-pink-400 transition">Employer Resources</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-ubuntu-bold mb-4 text-white">Company</h4>
                <ul class="space-y-2 font-ubuntu-medium">
                    <li><a href="#" class="text-gray-300 hover:text-pink-400 transition">About Us</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-pink-400 transition">Contact</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-pink-400 transition">Privacy Policy</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-ubuntu-bold mb-4 text-white">Connect</h4>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-300 hover:text-pink-400 transition text-2xl">
                        <i class="lab la-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-pink-400 transition text-2xl">
                        <i class="lab la-linkedin"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-pink-400 transition text-2xl">
                        <i class="lab la-github"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-gray-800 text-center text-gray-300 font-ubuntu">
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</footer>

@livewireScripts
@stack('extra-js')
</body>
</html>
