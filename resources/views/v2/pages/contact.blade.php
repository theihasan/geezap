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
                    Contact <span class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-500 bg-clip-text text-transparent">Us</span>
                </h1>
                <p class="font-ubuntu-light text-xl leading-relaxed text-gray-600 dark:text-gray-100 max-w-3xl mx-auto">
                    Have questions, feedback, or just want to say hello? We'd love to hear from you.
                </p>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Contact Form Section -->
    <section class="bg-white dark:bg-[#0A0A1B] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid md:grid-cols-2 gap-12">
                <!-- Left: Contact Form -->
                <div class="bg-white dark:bg-[#12122b] rounded-2xl p-8 border border-gray-200 dark:border-gray-800">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Send Us a Message</h2>

                    <form action="#" method="POST" class="space-y-6">
                        @csrf

                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-gray-700 dark:text-gray-300 mb-2">Your Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <i class="las la-user text-blue-600 dark:text-pink-500"></i>
                                </div>
                                <input type="text" id="name" name="name"
                                       class="w-full bg-gray-50 dark:bg-[#1a1a3a] border border-gray-300 dark:border-gray-700 rounded-xl py-3 pl-12 pr-4 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:border-blue-500 dark:focus:border-pink-500 transition"
                                       placeholder="Enter your name" required>
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-gray-700 dark:text-gray-300 mb-2">Your Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <i class="las la-envelope text-blue-600 dark:text-pink-500"></i>
                                </div>
                                <input type="email" id="email" name="email"
                                       class="w-full bg-gray-50 dark:bg-[#1a1a3a] border border-gray-300 dark:border-gray-700 rounded-xl py-3 pl-12 pr-4 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:border-blue-500 dark:focus:border-pink-500 transition"
                                       placeholder="Enter your email" required>
                            </div>
                        </div>

                        <!-- Subject Field -->
                        <div>
                            <label for="subject" class="block text-gray-700 dark:text-gray-300 mb-2">Subject</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <i class="las la-heading text-blue-600 dark:text-pink-500"></i>
                                </div>
                                <input type="text" id="subject" name="subject"
                                       class="w-full bg-gray-50 dark:bg-[#1a1a3a] border border-gray-300 dark:border-gray-700 rounded-xl py-3 pl-12 pr-4 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:border-blue-500 dark:focus:border-pink-500 transition"
                                       placeholder="Enter subject">
                            </div>
                        </div>

                        <!-- Message Field -->
                        <div>
                            <label for="message" class="block text-gray-700 dark:text-gray-300 mb-2">Your Message</label>
                            <div class="relative">
                                <div class="absolute top-3 left-0 flex items-start pl-4 pointer-events-none">
                                    <i class="las la-comment text-blue-600 dark:text-pink-500"></i>
                                </div>
                                <textarea id="message" name="message" rows="5"
                                          class="w-full bg-gray-50 dark:bg-[#1a1a3a] border border-gray-300 dark:border-gray-700 rounded-xl py-3 pl-12 pr-4 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:border-blue-500 dark:focus:border-pink-500 transition"
                                          placeholder="Enter your message" required></textarea>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white rounded-xl py-3 hover:opacity-90 transition-opacity">
                            Send Message
                        </button>
                    </form>
                </div>

                <!-- Right: Contact Information -->
                <div class="space-y-8">
                    <!-- Contact Info Card -->
                    <div class="bg-white dark:bg-[#12122b] rounded-2xl p-8 border border-gray-200 dark:border-gray-800">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Contact Information</h2>

                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500/10 dark:bg-blue-500/10">
                                    <i class="las la-envelope text-2xl text-blue-600 dark:text-pink-500"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Email</h3>
                                    <a href="mailto:contact@geezap.com" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-pink-400 transition">contact@geezap.com</a>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500/10 dark:bg-blue-500/10">
                                    <i class="las la-map-marker text-2xl text-blue-600 dark:text-pink-500"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Location</h3>
                                    <p class="text-gray-700 dark:text-gray-300">Remote-first (currently based in Dhaka)</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500/10 dark:bg-blue-500/10">
                                    <i class="lab la-github text-2xl text-blue-600 dark:text-pink-500"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">GitHub</h3>
                                    <a href="https://github.com/theihasan/geezap" target="_blank" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-pink-400 transition">github.com/theihasan/geezap</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Card -->
                    <div class="bg-white dark:bg-[#12122b] rounded-2xl p-8 border border-gray-200 dark:border-gray-800">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Frequently Asked Questions</h2>

                        <div class="space-y-6" x-data="{selected:null}">
                            <div class="border-b border-gray-200 dark:border-gray-800 pb-4">
                                <button @click="selected !== 1 ? selected = 1 : selected = null"
                                        class="flex justify-between items-center w-full text-left">
                                    <span class="text-lg font-medium text-gray-900 dark:text-white">How do I apply for jobs on Geezap?</span>
                                    <i class="las" :class="selected == 1 ? 'la-minus text-blue-600 dark:text-pink-500' : 'la-plus text-gray-400'"></i>
                                </button>
                                <div x-show="selected == 1" class="mt-3 text-gray-700 dark:text-gray-300">
                                    When you find a job you're interested in, simply click the "Apply" button. You'll be redirected to the original job posting where you can complete your application directly with the employer.
                                </div>
                            </div>

                            <div class="border-b border-gray-200 dark:border-gray-800 pb-4">
                                <button @click="selected !== 2 ? selected = 2 : selected = null"
                                        class="flex justify-between items-center w-full text-left">
                                    <span class="text-lg font-medium text-gray-900 dark:text-white">Is Geezap free to use?</span>
                                    <i class="las" :class="selected == 2 ? 'la-minus text-blue-600 dark:text-pink-500' : 'la-plus text-gray-400'"></i>
                                </button>
                                <div x-show="selected == 2" class="mt-3 text-gray-700 dark:text-gray-300">
                                    Yes, Geezap is completely free for job seekers. We believe in connecting tech professionals with great opportunities without any barriers.
                                </div>
                            </div>

                            <div class="border-b border-gray-200 dark:border-gray-800 pb-4">
                                <button @click="selected !== 3 ? selected = 3 : selected = null"
                                        class="flex justify-between items-center w-full text-left">
                                    <span class="text-lg font-medium text-gray-900 dark:text-white">How can I post a job on Geezap?</span>
                                    <i class="las" :class="selected == 3 ? 'la-minus text-blue-600 dark:text-pink-500' : 'la-plus text-gray-400'"></i>
                                </button>
                                <div x-show="selected == 3" class="mt-3 text-gray-700 dark:text-gray-300">
                                    If you're an employer looking to post a job, please contact us directly at contact@geezap.com for more information about our job posting process.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Connect Section -->
    <section class="bg-gray-50 dark:bg-[#12122b] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Connect With Us</h2>
                <p class="text-gray-700 dark:text-gray-300 text-lg max-w-2xl mx-auto">
                    Follow us on social media to stay updated with the latest tech job opportunities and news.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
                <a href="https://github.com/theihasan/geezap" target="_blank"
                   class="group flex flex-col items-center justify-center rounded-2xl bg-gray-100 dark:bg-[#1a1a3a] p-8 border border-gray-200 dark:border-gray-800 hover:border-blue-500/50 dark:hover:border-pink-500/50 transition">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-500/10 dark:bg-blue-500/10 group-hover:bg-blue-500/20 dark:group-hover:bg-blue-500/20">
                        <i class="lab la-github text-3xl text-blue-600 dark:text-pink-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">GitHub</h3>
                </a>

                <a href="https://facebook.com/geezap247" target="_blank"
                   class="group flex flex-col items-center justify-center rounded-2xl bg-gray-100 dark:bg-[#1a1a3a] p-8 border border-gray-200 dark:border-gray-800 hover:border-blue-500/50 dark:hover:border-pink-500/50 transition">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-500/10 dark:bg-blue-500/10 group-hover:bg-blue-500/20 dark:group-hover:bg-blue-500/20">
                        <i class="lab la-facebook text-3xl text-blue-600 dark:text-pink-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Facebook</h3>
                </a>

                <a href="https://linkedin.com/in/theihasan" target="_blank"
                   class="group flex flex-col items-center justify-center rounded-2xl bg-gray-100 dark:bg-[#1a1a3a] p-8 border border-gray-200 dark:border-gray-800 hover:border-blue-500/50 dark:hover:border-pink-500/50 transition">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-500/10 dark:bg-blue-500/10 group-hover:bg-blue-500/20 dark:group-hover:bg-blue-500/20">
                        <i class="lab la-linkedin text-3xl text-blue-600 dark:text-pink-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">LinkedIn</h3>
                </a>

                <a href="mailto:contact@geezap.com"
                   class="group flex flex-col items-center justify-center rounded-2xl bg-gray-100 dark:bg-[#1a1a3a] p-8 border border-gray-200 dark:border-gray-800 hover:border-blue-500/50 dark:hover:border-pink-500/50 transition">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-500/10 dark:bg-blue-500/10 group-hover:bg-blue-500/20 dark:group-hover:bg-blue-500/20">
                        <i class="las la-envelope text-3xl text-blue-600 dark:text-pink-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Email</h3>
                </a>
            </div>
        </div>
    </section>
@endsection
