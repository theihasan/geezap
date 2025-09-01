@include('v2.partials.header')
<body class="bg-white dark:bg-[#0A0A1B] font-ubuntu-light">
<!-- Navigation -->
<nav class="bg-white dark:bg-[#12122b] border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center h-20">
            <!-- Left Section: Logo and Menu -->
            <div class="flex items-center gap-8">
                <!-- Logo -->
                @include('v2.partials.logo')

                <!-- Desktop Menu -->
               @include('v2.partials.desktop-menu')
            </div>

            <!-- Right Section: Auth Controls -->
           @include('v2.partials.header-auth')

            <!-- Mobile Menu Button -->
            <button class="md:hidden w-10 h-10 rounded-lg bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 transition-colors flex items-center justify-center"
                    onclick="toggleMobileMenu()"
                    id="menu-toggle">
                <i class="las la-bars text-2xl text-gray-900 dark:text-white transition-transform duration-300"></i>
            </button>
        </div>
    </div>

   @include('v2.partials.mobile-menu')
</nav>

@yield('content')

<!-- Footer -->
@include('v2.partials.footer')

