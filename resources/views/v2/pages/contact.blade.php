@extends('v2.layouts.app')
@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-[#12122b] py-20">
        <div class="relative mx-auto max-w-7xl px-6">
            <div class="text-center mb-12">
                <h1 class="font-oxanium-bold text-5xl leading-tight text-gray-900 dark:text-white md:text-6xl mb-6">
                    Get in <span class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-500 bg-clip-text text-transparent">Touch</span>
                </h1>
                <p class="font-ubuntu-light text-xl leading-relaxed text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    We'd love to hear from you. Reach out to us through any of the channels below, and we'll get back to you as soon as possible.
                </p>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Contact Information Cards -->
    <section class="bg-white dark:bg-[#0A0A1B] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Email Card -->
                <div class="bg-white dark:bg-[#12122b] rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-800 group">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-pink-500/20 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="las la-envelope text-2xl text-blue-600 dark:text-pink-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Email</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                        Send us an email for detailed inquiries. We typically respond within 24 hours.
                    </p>
                    <a href="mailto:contact@geezap.com" class="inline-flex items-center text-blue-600 dark:text-pink-500 font-semibold hover:text-blue-700 dark:hover:text-pink-400 transition-colors">
                        <i class="las la-envelope mr-2"></i>
                        contact@geezap.com
                    </a>
                </div>

                <!-- Location Card -->
                <div class="bg-white dark:bg-[#12122b] rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-800 group">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-pink-500/20 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="las la-map-marker-alt text-2xl text-blue-600 dark:text-pink-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Location</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                        Remote-first company currently based in Dhaka, Bangladesh.
                    </p>
                    <div class="inline-flex items-center text-blue-600 dark:text-pink-500 font-semibold">
                        <i class="las la-map-marker-alt mr-2"></i>
                        Dhaka, Bangladesh
                    </div>
                </div>

                <!-- GitHub Card -->
                <div class="bg-white dark:bg-[#12122b] rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-800 group">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-pink-500/20 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="lab la-github text-2xl text-blue-600 dark:text-pink-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">GitHub</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                        Check out our open source projects and contribute to the Geezap ecosystem.
                    </p>
                    <a href="https://github.com/theihasan/geezap" target="_blank" class="inline-flex items-center text-blue-600 dark:text-pink-500 font-semibold hover:text-blue-700 dark:hover:text-pink-400 transition-colors">
                        <i class="lab la-github mr-2"></i>
                        View on GitHub
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="bg-gray-50 dark:bg-[#12122b] py-20">
        <div class="mx-auto max-w-4xl px-6">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Frequently Asked Questions</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300">Find answers to common questions about Geezap</p>
            </div>

            <div class="bg-white dark:bg-[#12122b] rounded-2xl p-8 border border-gray-200 dark:border-gray-800" x-data="{selected:null}">
                <div class="space-y-6">
                    <div class="border-b border-gray-200 dark:border-gray-800 pb-6">
                        <button @click="selected !== 1 ? selected = 1 : selected = null"
                                class="flex justify-between items-center w-full text-left group">
                            <span class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-pink-500 transition-colors">How do I apply for jobs on Geezap?</span>
                            <i class="las text-xl transition-all duration-300" :class="selected == 1 ? 'la-minus text-blue-600 dark:text-pink-500' : 'la-plus text-gray-400 group-hover:text-blue-600 dark:group-hover:text-pink-500'"></i>
                        </button>
                        <div x-show="selected == 1" x-transition class="mt-4 text-gray-700 dark:text-gray-300 leading-relaxed">
                            When you find a job you're interested in, simply click the "Apply" button. You'll be redirected to the original job posting where you can complete your application directly with the employer.
                        </div>
                    </div>

                    <div class="border-b border-gray-200 dark:border-gray-800 pb-6">
                        <button @click="selected !== 2 ? selected = 2 : selected = null"
                                class="flex justify-between items-center w-full text-left group">
                            <span class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-pink-500 transition-colors">Is Geezap free to use?</span>
                            <i class="las text-xl transition-all duration-300" :class="selected == 2 ? 'la-minus text-blue-600 dark:text-pink-500' : 'la-plus text-gray-400 group-hover:text-blue-600 dark:group-hover:text-pink-500'"></i>
                        </button>
                        <div x-show="selected == 2" x-transition class="mt-4 text-gray-700 dark:text-gray-300 leading-relaxed">
                            Yes, Geezap is completely free for job seekers. We believe in connecting tech professionals with great opportunities without any barriers.
                        </div>
                    </div>

                    <div class="border-b border-gray-200 dark:border-gray-800 pb-6">
                        <button @click="selected !== 3 ? selected = 3 : selected = null"
                                class="flex justify-between items-center w-full text-left group">
                            <span class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-pink-500 transition-colors">How can I post a job on Geezap?</span>
                            <i class="las text-xl transition-all duration-300" :class="selected == 3 ? 'la-minus text-blue-600 dark:text-pink-500' : 'la-plus text-gray-400 group-hover:text-blue-600 dark:group-hover:text-pink-500'"></i>
                        </button>
                        <div x-show="selected == 3" x-transition class="mt-4 text-gray-700 dark:text-gray-300 leading-relaxed">
                            If you're an employer looking to post a job, please contact us directly at contact@geezap.com for more information about our job posting process.
                        </div>
                    </div>

                    <div class="pb-6">
                        <button @click="selected !== 4 ? selected = 4 : selected = null"
                                class="flex justify-between items-center w-full text-left group">
                            <span class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-pink-500 transition-colors">What types of jobs are available on Geezap?</span>
                            <i class="las text-xl transition-all duration-300" :class="selected == 4 ? 'la-minus text-blue-600 dark:text-pink-500' : 'la-plus text-gray-400 group-hover:text-blue-600 dark:group-hover:text-pink-500'"></i>
                        </button>
                        <div x-show="selected == 4" x-transition class="mt-4 text-gray-700 dark:text-gray-300 leading-relaxed">
                            Geezap focuses on tech jobs including software development, data science, DevOps, UI/UX design, product management, and other technology-related positions from startups to Fortune 500 companies.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Connect Section -->
    <section class="bg-white dark:bg-[#0A0A1B] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Connect With Us</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Follow us on social media to stay updated with the latest tech job opportunities and news.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
                <a href="https://github.com/theihasan/geezap" target="_blank"
                   class="group flex flex-col items-center justify-center rounded-2xl bg-gray-50 dark:bg-[#1a1a3a] p-8 border border-gray-200 dark:border-gray-800 hover:border-blue-500/50 dark:hover:border-pink-500/50 hover:shadow-lg transition-all duration-300">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-500/10 dark:bg-blue-500/10 group-hover:bg-blue-500/20 dark:group-hover:bg-blue-500/20 group-hover:scale-110 transition-all duration-300">
                        <i class="lab la-github text-3xl text-blue-600 dark:text-pink-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-pink-500 transition-colors">GitHub</h3>
                </a>

                <a href="https://facebook.com/geezap247" target="_blank"
                   class="group flex flex-col items-center justify-center rounded-2xl bg-gray-50 dark:bg-[#1a1a3a] p-8 border border-gray-200 dark:border-gray-800 hover:border-blue-500/50 dark:hover:border-pink-500/50 hover:shadow-lg transition-all duration-300">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-500/10 dark:bg-blue-500/10 group-hover:bg-blue-500/20 dark:group-hover:bg-blue-500/20 group-hover:scale-110 transition-all duration-300">
                        <i class="lab la-facebook text-3xl text-blue-600 dark:text-pink-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-pink-500 transition-colors">Facebook</h3>
                </a>

                <a href="https://linkedin.com/in/theihasan" target="_blank"
                   class="group flex flex-col items-center justify-center rounded-2xl bg-gray-50 dark:bg-[#1a1a3a] p-8 border border-gray-200 dark:border-gray-800 hover:border-blue-500/50 dark:hover:border-pink-500/50 hover:shadow-lg transition-all duration-300">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-500/10 dark:bg-blue-500/10 group-hover:bg-blue-500/20 dark:group-hover:bg-blue-500/20 group-hover:scale-110 transition-all duration-300">
                        <i class="lab la-linkedin text-3xl text-blue-600 dark:text-pink-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-pink-500 transition-colors">LinkedIn</h3>
                </a>

                <a href="mailto:contact@geezap.com"
                   class="group flex flex-col items-center justify-center rounded-2xl bg-gray-50 dark:bg-[#1a1a3a] p-8 border border-gray-200 dark:border-gray-800 hover:border-blue-500/50 dark:hover:border-pink-500/50 hover:shadow-lg transition-all duration-300">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-500/10 dark:bg-blue-500/10 group-hover:bg-blue-500/20 dark:group-hover:bg-blue-500/20 group-hover:scale-110 transition-all duration-300">
                        <i class="las la-envelope text-3xl text-blue-600 dark:text-pink-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-pink-500 transition-colors">Email</h3>
                </a>
            </div>
        </div>
    </section>
@endsection
