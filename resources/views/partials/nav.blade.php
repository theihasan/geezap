<nav id="topnav" class="defaultscroll is-sticky">
    <div class="container">
        <!-- Logo container-->
        <a class="logo" href="{{route('home')}}">
            <div class="block sm:hidden">
                <img src="{{asset('assets/images/logo-icon-40.png')}}" class="h-10 inline-block dark:hidden"  alt="">
                <img src="{{asset('assets/images/logo-icon-40-white.png')}}" class="h-10 hidden dark:inline-block"  alt="">
            </div>
            <div class="sm:block hidden">
                GeezAp
            </div>
        </a>
        <!-- End Logo container-->

        <!-- Start Mobile Toggle -->
        <div class="menu-extras">
            <div class="menu-item">
                <a class="navbar-toggle" id="isToggle" onclick="toggleMenu()">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
            </div>
        </div>
        <!-- End Mobile Toggle -->

        <!--Login button Start-->
        <ul class="buy-button list-none mb-0">
            <li class="dropdown inline-block relative ps-1">
                <button data-dropdown-toggle="dropdown" class="dropdown-toggle items-center" type="button">
                    <span class="btn btn-icon rounded-full bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700 text-white">
                        <img src="{{asset('assets/images/profile.jpg')}}" class="rounded-full" alt=""></span>
                </button>
                <!-- Dropdown menu -->
                <div class="dropdown-menu absolute end-0 m-0 mt-4 z-10 w-44 rounded-md overflow-hidden bg-white dark:bg-slate-900 shadow dark:shadow-gray-700 hidden" onclick="event.stopPropagation();">
                    <ul class="py-2 text-start">
                        <li>
                            <a href="{{route('dashboard')}}" class="flex items-center font-medium py-2 px-4 dark:text-white/70 hover:text-emerald-600 dark:hover:text-white"><i data-feather="user" class="size-4 me-2"></i>Profile</a>
                        </li>
                        <li>
                            <a href="{{route('profile.update')}}" class="flex items-center font-medium py-2 px-4 dark:text-white/70 hover:text-emerald-600 dark:hover:text-white"><i data-feather="settings" class="size-4 me-2"></i>Settings</a>
                        </li>
                        <li class="border-t border-gray-100 dark:border-gray-800 my-2"></li>

                        <li>
                            <a href="{{route('logout')}}" class="flex items-center font-medium py-2 px-4 dark:text-white/70 hover:text-emerald-600 dark:hover:text-white"><i data-feather="log-out" class="size-4 me-2"></i>Logout</a>
                        </li>
                    </ul>
                </div>
            </li>
            <!--end dropdown-->
        </ul>
        <!--Login button End-->

        <div id="navigation">
            <!-- Navigation Menu-->
            <ul class="navigation-menu">
                <li class="">
                    <a href="{{route('home')}}">Home</a>

                </li>

                <li class="has-submenu parent-parent-menu-item"><a href="javascript:void(0)"> Jobs </a><span class="menu-arrow"></span>
                    <ul class="submenu">
                        <li><a href="{{route('job.index')}}" class="sub-menu-item">Job List</a></li>
                        <li><a href="{{route('job.categories')}}" class="sub-menu-item">Job Categories</a></li>
                    </ul>
                </li>

                <li><a href="{{route('contact')}}" class="sub-menu-item">Contact</a></li>
                @guest
                <li><a href="{{route('login')}}" class="sub-menu-item">Login</a></li>
                <li><a href="{{route('register')}}" class="sub-menu-item">Register</a></li>
                @endguest
            </ul>
        </div><!--end navigation-->
    </div><!--end container-->
</nav>
