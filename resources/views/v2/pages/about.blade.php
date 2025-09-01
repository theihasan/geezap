@extends('v2.layouts.app')
@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gray-50 dark:bg-[#12122b] py-20">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10"
             style="background-image: url('https://placehold.co/1920x800/2a2a4a/FFFFFF'); background-size: cover;"></div>

        <div class="relative mx-auto max-w-7xl px-6">
            <div class="text-center mb-12">
                <h1 class="font-oxanium-bold text-5xl leading-tight text-gray-900 dark:text-white md:text-6xl mb-6">
                    About <span class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-500 bg-clip-text text-transparent">Geezap</span>
                </h1>
                <p class="font-ubuntu-light text-xl leading-relaxed text-gray-600 dark:text-gray-100 max-w-3xl mx-auto">
                    Empowering technology professionals by making the job search smarter, faster, and more personal.
                </p>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Our Mission Section -->
    <section class="bg-white dark:bg-[#0A0A1B] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Mission</h2>
                    <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed mb-6">
                        At Geezap, I'm on a mission to empower technology professionals by making the job search smarter, faster, and more personal. Everyone working in tech—from developers and data scientists to designers and CAD engineers—deserves access to the right opportunity.
                    </p>
                </div>
                <div class="relative">
                    <img src="https://placehold.co/600x400/2a2a4a/FFFFFF" alt="Our Mission" class="rounded-2xl shadow-2xl" loading="lazy">
                    <div class="absolute -left-6 -top-6 rounded-xl border border-gray-200 dark:border-white/10 bg-white/90 dark:bg-[#1a1a3a]/90 p-4 backdrop-blur-sm">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500/20 dark:bg-pink-500/20">
                                <i class="las la-rocket text-blue-600 dark:text-pink-500"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">Our Mission</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Tech-Professional-First</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Who I Am Section -->
    <section class="bg-gray-50 dark:bg-[#12122b] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="order-2 md:order-1 relative">
                    <img src="https://avatars.githubusercontent.com/u/142471724?v=4" alt="Md. Abul Hassan" class="rounded-2xl shadow-2xl" loading="lazy">
                    <div class="absolute -right-6 -bottom-6 rounded-xl border border-gray-200 dark:border-white/10 bg-white/90 dark:bg-[#1a1a3a]/90 p-4 backdrop-blur-sm">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500/20 dark:bg-purple-500/20">
                                <i class="las la-code text-blue-600 dark:text-purple-500"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">Software Engineer</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Open-Source Enthusiast</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-1 md:order-2">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Who I Am</h2>
                    <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed mb-6">
                        I'm Md. Abul Hassan —software engineer, open‑source enthusiast, and sole creator of Geezap. Frustrated by fragmented job boards and endless keyword searches, I built Geezap to centralize tech‑role listings in one clean, curated experience.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500/20 dark:bg-pink-500/20">
                                <i class="las la-map-marker text-blue-600 dark:text-pink-500"></i>
                            </div>
                            <div class="text-gray-600 dark:text-gray-300">
                                <span class="font-medium text-gray-900 dark:text-white">Location:</span> Remote‑first (currently based in Dhaka)
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500/20 dark:bg-pink-500/20">
                                <i class="lab la-github text-blue-600 dark:text-pink-500"></i>
                            </div>
                            <div class="text-gray-600 dark:text-gray-300">
                                <span class="font-medium text-gray-900 dark:text-white">GitHub:</span>
                                <a href="https://github.com/theihasan" target="_blank" class="text-blue-600 dark:text-pink-400 hover:text-blue-700 dark:hover:text-pink-500 transition">github.com/theihasan</a>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500/20 dark:bg-pink-500/20">
                                <i class="lab la-linkedin text-blue-600 dark:text-pink-500"></i>
                            </div>
                            <div class="text-gray-600 dark:text-gray-300">
                                <span class="font-medium text-gray-900 dark:text-white">LinkedIn:</span>
                                <a href="https://linkedin.com/in/theihasan" target="_blank" class="text-blue-600 dark:text-pink-400 hover:text-blue-700 dark:hover:text-pink-500 transition">linkedin.com/in/theihasan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- What Geezap Does Section -->
    <section class="bg-white dark:bg-[#0A0A1B] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-12 text-center">What Geezap Does</h2>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="group rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-[#1a1a3a] p-8 transition hover:border-blue-500/50 dark:hover:border-pink-500/50">
                    <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-xl bg-blue-500/10 dark:bg-pink-500/10 group-hover:bg-blue-500/20 dark:group-hover:bg-pink-500/20">
                        <i class="las la-search text-3xl text-blue-600 dark:text-pink-500"></i>
                    </div>
                    <h3 class="mb-4 text-2xl font-semibold text-gray-900 dark:text-white">Aggregate</h3>
                    <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                        <li class="flex items-start gap-2">
                            <i class="las la-check-circle text-blue-600 dark:text-pink-500 mt-1"></i>
                            <span>Crawl hundreds of sources in real‑time so you never miss a newly posted role.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="las la-check-circle text-blue-600 dark:text-pink-500 mt-1"></i>
                            <span>Deduplicate identical listings while preserving each original apply link.</span>
                        </li>
                    </ul>
                </div>

                <div class="group rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-[#1a1a3a] p-8 transition hover:border-blue-500/50 dark:hover:border-pink-500/50">
                    <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-xl bg-blue-500/10 dark:bg-pink-500/10 group-hover:bg-blue-500/20 dark:group-hover:bg-pink-500/20">
                        <i class="las la-tags text-3xl text-blue-600 dark:text-pink-500"></i>
                    </div>
                    <h3 class="mb-4 text-2xl font-semibold text-gray-900 dark:text-white">Enrich</h3>
                    <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                        <li class="flex items-start gap-2">
                            <i class="las la-check-circle text-blue-600 dark:text-pink-500 mt-1"></i>
                            <span>Tag every job with core and adjacent skills (e.g., React, Docker, GraphQL).</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="las la-check-circle text-blue-600 dark:text-pink-500 mt-1"></i>
                            <span>(Coming soon) "For You" Feed: AI‑driven recommendations that learn from your clicks, saves, and applications.</span>
                        </li>
                    </ul>
                </div>

                <div class="group rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-[#1a1a3a] p-8 transition hover:border-blue-500/50 dark:hover:border-pink-500/50">
                    <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-xl bg-blue-500/10 dark:bg-pink-500/10 group-hover:bg-blue-500/20 dark:group-hover:bg-pink-500/20">
                        <i class="las la-paper-plane text-3xl text-blue-600 dark:text-pink-500"></i>
                    </div>
                    <h3 class="mb-4 text-2xl font-semibold text-gray-900 dark:text-white">Apply</h3>
                    <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                        <li class="flex items-start gap-2">
                            <i class="las la-check-circle text-blue-600 dark:text-pink-500 mt-1"></i>
                            <span>One‑click "Apply" buttons redirect you to the original job board where the role was posted.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="las la-check-circle text-blue-600 dark:text-pink-500 mt-1"></i>
                            <span>No résumé uploads, no middlemen—direct connection to employers.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- My Values Section -->
    <section class="bg-gray-50 dark:bg-[#12122b] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-12 text-center">Geezap Values</h2>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="rounded-2xl bg-white dark:bg-[#1a1a3a] p-8 border border-gray-200 dark:border-gray-700 hover:border-blue-500/50 dark:hover:border-pink-500/50 transition">
                    <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-xl bg-blue-500/10 dark:bg-pink-500/10">
                        <i class="las la-users text-3xl text-blue-600 dark:text-pink-500"></i>
                    </div>
                    <h3 class="mb-4 text-2xl font-semibold text-gray-900 dark:text-white">Tech-Professional-First</h3>
                    <p class="text-gray-700 dark:text-gray-300">
                        Every feature and design choice is driven by what benefits all technology professionals—developers, data scientists, designers, CAD engineers, and more.
                    </p>
                </div>

                <div class="rounded-2xl bg-white dark:bg-[#1a1a3a] p-8 border border-gray-200 dark:border-gray-700 hover:border-blue-500/50 dark:hover:border-pink-500/50 transition">
                    <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-xl bg-blue-500/10 dark:bg-pink-500/10">
                        <i class="las la-feather text-3xl text-blue-600 dark:text-pink-500"></i>
                    </div>
                    <h3 class="mb-4 text-2xl font-semibold text-gray-900 dark:text-white">Simplicity</h3>
                    <p class="text-gray-700 dark:text-gray-300">
                        A clean interface and straightforward workflows—no clutter, no distractions. Focus on what matters most: finding your next opportunity.
                    </p>
                </div>

                <div class="rounded-2xl bg-white dark:bg-[#1a1a3a] p-8 border border-gray-200 dark:border-gray-700 hover:border-blue-500/50 dark:hover:border-pink-500/50 transition">
                    <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-xl bg-blue-500/10 dark:bg-pink-500/10">
                        <i class="las la-shield-alt text-3xl text-blue-600 dark:text-pink-500"></i>
                    </div>
                    <h3 class="mb-4 text-2xl font-semibold text-gray-900 dark:text-white">Privacy</h3>
                    <p class="text-gray-700 dark:text-gray-300">
                        Your data is yours. Geezap never sells personal information and uses only privacy‑first analytics to improve the platform.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Get In Touch Section -->
    <section class="bg-white dark:bg-[#0A0A1B] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Get In Touch</h2>
                <p class="text-gray-600 dark:text-gray-300 text-lg max-w-2xl mx-auto">
                    I'm always open to feedback, suggestions, or just a chat about all things tech.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div class="rounded-2xl bg-gray-50 dark:bg-[#12122b] p-8 border border-gray-200 dark:border-gray-700 hover:border-blue-500/50 dark:hover:border-pink-500/50 transition flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-blue-500/10 dark:bg-pink-500/10">
                        <i class="las la-envelope text-2xl text-blue-600 dark:text-pink-500"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">Email</h3>
                        <a href="mailto:contact@geezap.com" class="text-blue-600 dark:text-pink-400 hover:text-blue-700 dark:hover:text-pink-500 transition">contact@geezap.com</a>
                    </div>
                </div>

                <div class="rounded-2xl bg-gray-50 dark:bg-[#12122b] p-8 border border-gray-200 dark:border-gray-700 hover:border-blue-500/50 dark:hover:border-pink-500/50 transition flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-blue-500/10 dark:bg-pink-500/10">
                        <i class="lab la-github text-2xl text-blue-600 dark:text-pink-500"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">GitHub Issues</h3>
                        <a href="https://github.com/theihasan/geezap" target="_blank" class="text-blue-600 dark:text-pink-400 hover:text-blue-700 dark:hover:text-pink-500 transition">github.com/theihasan/geezap</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-gray-50 dark:bg-[#12122b] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="rounded-2xl bg-gradient-to-r from-blue-500/20 dark:from-pink-500/20 to-blue-600/20 dark:to-purple-600/20 p-12 border border-gray-200 dark:border-white/10 relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10"
                     style="background-image: url('https://placehold.co/1920x800/2a2a4a/FFFFFF'); background-size: cover;"></div>

                <div class="relative text-center max-w-3xl mx-auto">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Ready to Find Your Next Tech Role?</h2>
                    <p class="text-gray-600 dark:text-gray-300 text-lg mb-8">
                        Join thousands of tech professionals who have found their perfect roles through Geezap.
                    </p>
                    <a href="{{ route('job.index') }}"
                       class="font-ubuntu-regular inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 px-8 py-4 text-lg font-medium text-white transition-opacity hover:opacity-90">
                        Browse Jobs
                        <i class="las la-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
