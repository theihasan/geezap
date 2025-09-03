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
                    Terms of <span class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-500 bg-clip-text text-transparent">Service</span>
                </h1>
                <p class="font-ubuntu-light text-xl leading-relaxed text-gray-600 dark:text-gray-100 max-w-3xl mx-auto">
                    By accessing or using our platform, you agree to be bound by the following terms and conditions. Please read them carefully before using the site.
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

    <!-- Terms of Service Content -->
    <section class="bg-white dark:bg-[#0A0A1B] py-20">
        <div class="mx-auto max-w-4xl px-6">
            <div class="bg-white dark:bg-[#12122b] rounded-2xl p-8 md:p-12 border border-gray-200 dark:border-gray-800">
                <!-- Overview -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">1</span>
                        </div>
                        Overview
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            Geezap is a job aggregator that helps tech professionals discover relevant job opportunities across various external job boards. When a user clicks on a job listing, they are redirected to the original source to apply.
                        </p>
                    </div>
                </div>

                <!-- Eligibility -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">2</span>
                        </div>
                        Eligibility
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            By using Geezap, you confirm that:
                        </p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>You are at least 16 years of age.</li>
                            <li>You have the legal capacity to enter into this agreement.</li>
                            <li>All information you provide is accurate and up to date.</li>
                        </ul>
                    </div>
                </div>

                <!-- User Accounts -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">3</span>
                        </div>
                        User Accounts
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            To access certain features (like saving profiles or tracking work experience), you may need to create an account. You are responsible for:
                        </p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Maintaining the confidentiality of your password.</li>
                            <li>Ensuring all information in your profile is accurate.</li>
                            <li>Not sharing your account or impersonating others.</li>
                        </ul>
                    </div>
                </div>

                <!-- Use of Platform -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">4</span>
                        </div>
                        Use of Platform
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            You agree not to:
                        </p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Use Geezap for unlawful or harmful purposes.</li>
                            <li>Post false or misleading information.</li>
                            <li>Attempt to access or collect data from other users.</li>
                            <li>Use bots or automated systems to scrape or manipulate the site.</li>
                        </ul>
                    </div>
                </div>

                <!-- Job Listings -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">5</span>
                        </div>
                        Job Listings
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            Geezap does not post jobs directly. Job listings are:
                        </p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Aggregated from third-party sources.</li>
                            <li>Subject to change or removal without notice.</li>
                            <li>Owned by the respective external job boards.</li>
                        </ul>
                        <p class="mt-4">
                            We are not responsible for the accuracy, completeness, or authenticity of any job post, nor the outcome of any application made through redirected links.
                        </p>
                    </div>
                </div>

                <!-- Intellectual Property -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">6</span>
                        </div>
                        Intellectual Property
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            All content on Geezap—including design, branding, and layout—is the intellectual property of Geezap or its creator and may not be copied, reused, or reproduced without permission.
                        </p>
                    </div>
                </div>

                <!-- Data & Privacy -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">7</span>
                        </div>
                        Data & Privacy
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            Your use of Geezap is also governed by our <a href="{{ route('privacy-policy') }}" class="text-blue-600 dark:text-pink-500 hover:text-blue-700 dark:hover:text-pink-600 transition">Privacy Policy</a>. We collect limited personal data to provide personalized experiences. We do not sell or share your data with employers or other users.
                        </p>
                    </div>
                </div>

                <!-- Account Termination -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">8</span>
                        </div>
                        Account Termination
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            Geezap reserves the right to suspend or delete accounts that violate these terms or abuse the platform, with or without prior notice.
                        </p>
                    </div>
                </div>

                <!-- Limitation of Liability -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">9</span>
                        </div>
                        Limitation of Liability
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            Geezap is provided "as-is." We do not guarantee:
                        </p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>That jobs will be available at all times.</li>
                            <li>That redirection links will always work or remain active.</li>
                            <li>That any job application will result in employment.</li>
                        </ul>
                        <p class="mt-4">
                            We are not liable for any direct or indirect damages resulting from your use of the platform.
                        </p>
                    </div>
                </div>

                <!-- Changes to Terms -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">10</span>
                        </div>
                        Changes to Terms
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            We may update these Terms of Service at any time. Significant changes will be communicated via email or site notice. Continued use of the platform after changes constitutes your agreement to the new terms.
                        </p>
                    </div>
                </div>

                <!-- Contact -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/20 dark:bg-pink-500/20">
                            <span class="text-blue-600 dark:text-pink-500">11</span>
                        </div>
                        Contact
                    </h2>
                    <div class="text-gray-700 dark:text-gray-300 space-y-4">
                        <p>
                            If you have any questions or concerns about these terms:
                        </p>
                        <div class="flex items-center gap-2 mt-4">
                            <i class="las la-envelope text-blue-600 dark:text-pink-500"></i>
                            <span>Email: <a href="mailto:contact@geezap.com" class="text-blue-600 dark:text-pink-500 hover:text-blue-700 dark:hover:text-pink-600 transition">contact@geezap.com</a></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="lab la-github text-blue-600 dark:text-pink-500"></i>
                            <span>GitHub: <a href="https://github.com/theihasan" target="_blank" class="text-blue-600 dark:text-pink-500 hover:text-blue-700 dark:hover:text-pink-600 transition">github.com/theihasan</a></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="lab la-linkedin text-blue-600 dark:text-pink-500"></i>
                            <span>LinkedIn: <a href="https://linkedin.com/in/theihasan" target="_blank" class="text-blue-600 dark:text-pink-500 hover:text-blue-700 dark:hover:text-pink-600 transition">linkedin.com/in/theihasan</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
