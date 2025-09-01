@extends('v2.layouts.app')
@section('content')
    <section class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-[#12122b] relative py-20">
        <div class="absolute inset-0 opacity-10" style="background-image: url('https://placehold.co/1920x800/2a2a4a/FFFFFF'); background-size: cover;"></div>

        <div class="max-w-4xl mx-auto px-6 relative">
            <div class="text-center space-y-12">
                <div class="relative">
                    <svg class="w-96 h-48 mx-auto" viewBox="0 0 400 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M80 20H20V50C20 50 20 50 50 50C80 50 80 80 50 80C20 80 20 80 20 80"
                              stroke="url(#gradient)" stroke-width="4"/>
                        <circle cx="150" cy="50" r="30" stroke="url(#gradient)" stroke-width="4"/>
                        <path d="M280 25C280 25 280 25 250 25C220 25 220 50 250 50C220 50 220 75 250 75C280 75 280 75 280 75"
                              stroke="url(#gradient)" stroke-width="4"/>
                        <path d="M340 30L360 70M360 30L340 70M350 30L350 70"
                              stroke="url(#gradient)" stroke-width="4"/>
                        <defs>
                            <linearGradient id="gradient" x1="0" y1="0" x2="400" y2="100">
                                <stop offset="0%" stop-color="#3B82F6"/>
                                <stop offset="100%" stop-color="#1D4ED8"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>

                <div class="space-y-4">
                    <h2 class="text-4xl font-oxanium-bold text-gray-900 dark:text-white">
                        Under Maintenance
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 font-ubuntu-light max-w-2xl mx-auto">
                        We're currently improving our services to bring you a better experience.
                        We'll be back shortly!
                    </p>
                </div>

                <div class="bg-gray-100 dark:bg-[#1a1a3a]/50 p-6 rounded-xl max-w-xl mx-auto">
                    <div class="flex items-center justify-center gap-2 mb-4">
                        <div class="w-3 h-3 bg-blue-500 dark:bg-pink-500 rounded-full animate-pulse"></div>
                        <span class="text-blue-600 dark:text-pink-300 font-ubuntu-medium">Maintenance in Progress</span>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-white dark:bg-[#12122b] p-4 rounded-lg">
                            <h4 class="text-gray-900 dark:text-white font-semibold mb-2">Estimated Duration</h4>
                            <p class="text-gray-600 dark:text-gray-300 text-sm">
                                Expected completion at {{ now()->addHours(2)->format('h:i A') }}
                            </p>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-white dark:bg-[#12122b] p-4 rounded-lg">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="las la-tools text-blue-600 dark:text-pink-500"></i>
                                    <h4 class="text-gray-900 dark:text-white font-semibold">What's Happening</h4>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">
                                    System upgrades and performance improvements
                                </p>
                            </div>

                            <div class="bg-white dark:bg-[#12122b] p-4 rounded-lg">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="las la-bell text-blue-600 dark:text-pink-500"></i>
                                    <h4 class="text-gray-900 dark:text-white font-semibold">Get Notified</h4>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">
                                    Follow our status page for updates
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-gray-600 dark:text-gray-400 text-sm">
                    This page will automatically refresh every 30 seconds to check for updates.
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            setTimeout(function() {
                window.location.reload();
            }, 30000);
        </script>
    @endpush
@endsection
