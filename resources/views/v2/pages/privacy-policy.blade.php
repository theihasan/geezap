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
                    Privacy <span class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-500 bg-clip-text text-transparent">Policy</span>
                </h1>
                <p class="font-ubuntu-light text-xl leading-relaxed text-gray-600 dark:text-gray-100 max-w-3xl mx-auto">
                    Your privacy is important to us. This Privacy Policy outlines what data we collect, how we use it, and your rights regarding that data.
                </p>
                <div class="mt-6 text-gray-600 dark:text-gray-300">
                    <span class="inline-flex items-center gap-2">
                        <i class="las la-calendar text-blue-600 dark:text-pink-500"></i>
                        Effective Date: April 16, 2025
                    </span>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Privacy Policy Content -->
    <section class="bg-white dark:bg-[#0A0A1B] py-20">
        <div class="mx-auto max-w-4xl px-6">
            <div class="bg-white dark:bg-[#12122b] rounded-2xl p-8 md:p-12 border border-gray-200 dark:border-gray-800">
                <!-- Introduction -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">1</span>
                        </div>
                        Introduction
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            Welcome to Geezap. Your privacy is important to us. This Privacy Policy outlines what data we collect, how we use it, and your rights regarding that data. Geezap is a job aggregator platform that redirects users to external job boards—we do not host or process job applications directly.
                        </p>
                    </div>
                </div>

                <!-- Information We Collect -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">2</span>
                        </div>
                        Information We Collect
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            When you use the Geezap platform, we may collect the following data:
                        </p>

                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mt-6 mb-3">Personal Information</h3>
                        <p>Provided by you through your profile page:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Full Name</li>
                            <li>Email Address</li>
                            <li>Date of Birth</li>
                            <li>Phone Number</li>
                            <li>Occupation</li>
                            <li>Location (City, Country)</li>
                            <li>Timezone</li>
                            <li>Brief Bio or Summary</li>
                        </ul>

                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mt-6 mb-3">Work Experience</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Job Titles</li>
                            <li>Company Names</li>
                            <li>Employment Dates</li>
                            <li>Job Descriptions</li>
                        </ul>

                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mt-6 mb-3">Skills</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Technical or professional skills with optional proficiency levels</li>
                        </ul>

                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mt-6 mb-3">Social Media Links</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>GitHub</li>
                            <li>Facebook</li>
                            <li>Twitter/X</li>
                            <li>Personal Website or Portfolio (optional)</li>
                        </ul>

                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mt-6 mb-3">Account Credentials</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Password (hashed and encrypted; never stored in plain text)</li>
                        </ul>
                    </div>
                </div>

                <!-- How We Use Your Information -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">3</span>
                        </div>
                        How We Use Your Information
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            We use your data to:
                        </p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Display your profile information to you securely</li>
                            <li>Allow you to manage and update your resume-like profile</li>
                            <li>Help you keep track of your job experience, skills, and portfolio links</li>
                            <li>(In future) provide personalized job feeds based on your profile data</li>
                        </ul>
                        <p class="mt-4">
                            We do not share your personal data with employers or other users at this time.
                        </p>
                    </div>
                </div>

                <!-- Data Sharing -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">4</span>
                        </div>
                        Data Sharing
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            We do not sell or share your personal information with third parties. Your profile data is private and used only to personalize your experience within the platform.
                        </p>
                    </div>
                </div>

                <!-- Cookies & Analytics -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">5</span>
                        </div>
                        Cookies & Analytics
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            Geezap uses privacy-first analytics (e.g., SimpleAnalytics) to understand how the site is used. We do not track users across the web or collect personally identifiable analytics data.
                        </p>
                    </div>
                </div>

                <!-- Security -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">6</span>
                        </div>
                        Security
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            We implement strong security measures, including encryption and secure protocols, to protect your data. Passwords are stored in encrypted format and cannot be accessed by anyone, including us.
                        </p>
                    </div>
                </div>

                <!-- Your Rights -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">7</span>
                        </div>
                        Your Rights
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            You can:
                        </p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Access and edit all personal data through your profile page</li>
                            <li>Delete your account by contacting us directly</li>
                            <li>Update your password anytime through your account settings</li>
                        </ul>
                    </div>
                </div>

                <!-- Third-Party Job Boards -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">8</span>
                        </div>
                        Third-Party Job Boards
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            When you click "Apply" on a job post, you are redirected to an external job board. Geezap is not responsible for the privacy practices of third-party sites.
                        </p>
                    </div>
                </div>

                <!-- Contact -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">9</span>
                        </div>
                        Contact
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            If you have any questions or requests regarding your data:
                        </p>
                        <div class="flex items-center gap-2 mt-4">
                            <i class="las la-envelope text-blue-600 dark:text-pink-500"></i>
                            <span>Email: <a href="mailto:contact@geezap.com" class="text-blue-500 dark:text-pink-400 hover:text-blue-600 dark:hover:text-pink-500 transition">contact@geezap.com</a></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="lab la-github text-blue-600 dark:text-pink-500"></i>
                            <span>GitHub: <a href="https://github.com/theihasan" target="_blank" class="text-blue-500 dark:text-pink-400 hover:text-blue-600 dark:hover:text-pink-500 transition">github.com/theihasan</a></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="lab la-linkedin text-blue-600 dark:text-pink-500"></i>
                            <span>LinkedIn: <a href="https://linkedin.com/in/theihasan" target="_blank" class="text-blue-500 dark:text-pink-400 hover:text-blue-600 dark:hover:text-pink-500 transition">linkedin.com/in/theihasan</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
