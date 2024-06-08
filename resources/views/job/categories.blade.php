@extends('layouts.app')
@section('main-content')
        <!-- Start Hero -->
        <section class="relative table w-full py-36 bg-[url('../../assets/images/hero/bg.html')] bg-top bg-no-repeat bg-cover">
            <div class="absolute inset-0 bg-emerald-900/90"></div>
            <div class="container">
                <div class="grid grid-cols-1 text-center mt-10">
                    <h3 class="md:text-3xl text-2xl md:leading-snug tracking-wide leading-snug font-medium text-white">Job Categories</h3>

                </div><!--end grid-->
            </div><!--end container-->

            <div class="absolute text-center z-10 bottom-5 start-0 end-0 mx-3">
                <ul class="breadcrumb tracking-[0.5px] breadcrumb-light mb-0 inline-block">
                   <li class="inline breadcrumb-item text-[15px] font-semibold duration-500 ease-in-out text-white/50 hover:text-white"><a href="index.html">Jobstack</a></li>
                    <li class="inline breadcrumb-item text-[15px] font-semibold duration-500 ease-in-out text-white" aria-current="page">Job Categories</li>
                </ul>
            </div>
        </section><!--end section-->
        <div class="relative">
            <div class="shape absolute start-0 end-0 sm:-bottom-px -bottom-[2px] overflow-hidden z-1 text-white dark:text-slate-900">
                <svg class="w-full h-auto" viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
                </svg>
            </div>
        </div>
        <!-- End Hero -->

        <!-- Start -->
        <section class="relative md:py-24 py-16">
            <div class="container">
                <div class="grid lg:grid-cols-5 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-[30px]">
                    <div class="group px-3 py-10 rounded-md shadow dark:shadow-gray-700 hover:shadow-emerald-600/10 dark:hover:shadow-emerald-600/10 text-center bg-white dark:bg-slate-900 hover:bg-emerald-600/5 dark:hover:bg-emerald-600/5 transition duration-500">
                        <div class="size-16 bg-emerald-600/5 group-hover:bg-emerald-600 text-emerald-600 group-hover:text-white rounded-md text-2xl flex align-middle justify-center items-center shadow-sm dark:shadow-gray-700 transition duration-500 mx-auto">
                            <i class="uil uil-gitlab"></i>
                        </div>

                        <div class="content mt-6">
                            <a href="#" class="title text-lg font-semibold hover:text-emerald-600">Business <br> Development</a>
                            <p class="text-slate-400 mt-3">74 Jobs</p>
                        </div>
                    </div><!--end content-->

                    <div class="group px-3 py-10 rounded-md shadow dark:shadow-gray-700 hover:shadow-emerald-600/10 dark:hover:shadow-emerald-600/10 text-center bg-white dark:bg-slate-900 hover:bg-emerald-600/5 dark:hover:bg-emerald-600/5 transition duration-500">
                        <div class="size-16 bg-emerald-600/5 group-hover:bg-emerald-600 text-emerald-600 group-hover:text-white rounded-md text-2xl flex align-middle justify-center items-center shadow-sm dark:shadow-gray-700 transition duration-500 mx-auto">
                            <i class="uil uil-book-open"></i>
                        </div>

                        <div class="content mt-6">
                            <a href="#" class="title text-lg font-semibold hover:text-emerald-600">Marketing & <br> Communication</a>
                            <p class="text-slate-400 mt-3">20 Jobs</p>
                        </div>
                    </div><!--end content-->

                    <div class="group px-3 py-10 rounded-md shadow dark:shadow-gray-700 hover:shadow-emerald-600/10 dark:hover:shadow-emerald-600/10 text-center bg-white dark:bg-slate-900 hover:bg-emerald-600/5 dark:hover:bg-emerald-600/5 transition duration-500">
                        <div class="size-16 bg-emerald-600/5 group-hover:bg-emerald-600 text-emerald-600 group-hover:text-white rounded-md text-2xl flex align-middle justify-center items-center shadow-sm dark:shadow-gray-700 transition duration-500 mx-auto">
                            <i class="uil uil-chart-pie-alt"></i>
                        </div>

                        <div class="content mt-6">
                            <a href="#" class="title text-lg font-semibold hover:text-emerald-600">Project <br> Management</a>
                            <p class="text-slate-400 mt-3">35 Jobs</p>
                        </div>
                    </div><!--end content-->

                    <div class="group px-3 py-10 rounded-md shadow dark:shadow-gray-700 hover:shadow-emerald-600/10 dark:hover:shadow-emerald-600/10 text-center bg-white dark:bg-slate-900 hover:bg-emerald-600/5 dark:hover:bg-emerald-600/5 transition duration-500">
                        <div class="size-16 bg-emerald-600/5 group-hover:bg-emerald-600 text-emerald-600 group-hover:text-white rounded-md text-2xl flex align-middle justify-center items-center shadow-sm dark:shadow-gray-700 transition duration-500 mx-auto">
                            <i class="uil uil-feedback"></i>
                        </div>

                        <div class="content mt-6">
                            <a href="#" class="title text-lg font-semibold hover:text-emerald-600">Customer <br> Service</a>
                            <p class="text-slate-400 mt-3">46 Jobs</p>
                        </div>
                    </div><!--end content-->

                    <div class="group px-3 py-10 rounded-md shadow dark:shadow-gray-700 hover:shadow-emerald-600/10 dark:hover:shadow-emerald-600/10 text-center bg-white dark:bg-slate-900 hover:bg-emerald-600/5 dark:hover:bg-emerald-600/5 transition duration-500">
                        <div class="size-16 bg-emerald-600/5 group-hover:bg-emerald-600 text-emerald-600 group-hover:text-white rounded-md text-2xl flex align-middle justify-center items-center shadow-sm dark:shadow-gray-700 transition duration-500 mx-auto">
                            <i class="uil uil-presentation-line"></i>
                        </div>

                        <div class="content mt-6">
                            <a href="#" class="title text-lg font-semibold hover:text-emerald-600">Software <br> Engineering</a>
                            <p class="text-slate-400 mt-3">60 Jobs</p>
                        </div>
                    </div><!--end content-->

                    <div class="group px-3 py-10 rounded-md shadow dark:shadow-gray-700 hover:shadow-emerald-600/10 dark:hover:shadow-emerald-600/10 text-center bg-white dark:bg-slate-900 hover:bg-emerald-600/5 dark:hover:bg-emerald-600/5 transition duration-500">
                        <div class="size-16 bg-emerald-600/5 group-hover:bg-emerald-600 text-emerald-600 group-hover:text-white rounded-md text-2xl flex align-middle justify-center items-center shadow-sm dark:shadow-gray-700 transition duration-500 mx-auto">
                            <i class="uil uil-gitlab"></i>
                        </div>

                        <div class="content mt-6">
                            <a href="#" class="title text-lg font-semibold hover:text-emerald-600">Human Resource <br> HR</a>
                            <p class="text-slate-400 mt-3">74 Jobs</p>
                        </div>
                    </div><!--end content-->

                    <div class="group px-3 py-10 rounded-md shadow dark:shadow-gray-700 hover:shadow-emerald-600/10 dark:hover:shadow-emerald-600/10 text-center bg-white dark:bg-slate-900 hover:bg-emerald-600/5 dark:hover:bg-emerald-600/5 transition duration-500">
                        <div class="size-16 bg-emerald-600/5 group-hover:bg-emerald-600 text-emerald-600 group-hover:text-white rounded-md text-2xl flex align-middle justify-center items-center shadow-sm dark:shadow-gray-700 transition duration-500 mx-auto">
                            <i class="uil uil-book-open"></i>
                        </div>

                        <div class="content mt-6">
                            <a href="#" class="title text-lg font-semibold hover:text-emerald-600">It & <br> Networking</a>
                            <p class="text-slate-400 mt-3">20 Jobs</p>
                        </div>
                    </div><!--end content-->

                    <div class="group px-3 py-10 rounded-md shadow dark:shadow-gray-700 hover:shadow-emerald-600/10 dark:hover:shadow-emerald-600/10 text-center bg-white dark:bg-slate-900 hover:bg-emerald-600/5 dark:hover:bg-emerald-600/5 transition duration-500">
                        <div class="size-16 bg-emerald-600/5 group-hover:bg-emerald-600 text-emerald-600 group-hover:text-white rounded-md text-2xl flex align-middle justify-center items-center shadow-sm dark:shadow-gray-700 transition duration-500 mx-auto">
                            <i class="uil uil-chart-pie-alt"></i>
                        </div>

                        <div class="content mt-6">
                            <a href="#" class="title text-lg font-semibold hover:text-emerald-600">Sales & <br> Marketing </a>
                            <p class="text-slate-400 mt-3">35 Jobs</p>
                        </div>
                    </div><!--end content-->

                    <div class="group px-3 py-10 rounded-md shadow dark:shadow-gray-700 hover:shadow-emerald-600/10 dark:hover:shadow-emerald-600/10 text-center bg-white dark:bg-slate-900 hover:bg-emerald-600/5 dark:hover:bg-emerald-600/5 transition duration-500">
                        <div class="size-16 bg-emerald-600/5 group-hover:bg-emerald-600 text-emerald-600 group-hover:text-white rounded-md text-2xl flex align-middle justify-center items-center shadow-sm dark:shadow-gray-700 transition duration-500 mx-auto">
                            <i class="uil uil-feedback"></i>
                        </div>

                        <div class="content mt-6">
                            <a href="#" class="title text-lg font-semibold hover:text-emerald-600">Project <br> Manager</a>
                            <p class="text-slate-400 mt-3">46 Jobs</p>
                        </div>
                    </div><!--end content-->

                    <div class="group px-3 py-10 rounded-md shadow dark:shadow-gray-700 hover:shadow-emerald-600/10 dark:hover:shadow-emerald-600/10 text-center bg-white dark:bg-slate-900 hover:bg-emerald-600/5 dark:hover:bg-emerald-600/5 transition duration-500">
                        <div class="size-16 bg-emerald-600/5 group-hover:bg-emerald-600 text-emerald-600 group-hover:text-white rounded-md text-2xl flex align-middle justify-center items-center shadow-sm dark:shadow-gray-700 transition duration-500 mx-auto">
                            <i class="uil uil-presentation-line"></i>
                        </div>

                        <div class="content mt-6">
                            <a href="#" class="title text-lg font-semibold hover:text-emerald-600">Data <br> Science</a>
                            <p class="text-slate-400 mt-3">60 Jobs</p>
                        </div>
                    </div><!--end content-->
                </div><!--end grid-->
            </div><!--end container-->

            <div class="container md:mt-24 md:pb-16 mt-16">
                <div class="grid md:grid-cols-12 grid-cols-1 items-center gap-[30px]">
                    <div class="lg:col-span-5 md:col-span-6">
                        <div class="relative">
                            <div class="relative">
                                <img src="assets/images/about/ab01.jpg" class="lg:w-[400px] w-[280px] rounded-md shadow dark:shadow-gray-700" alt="">
                                <div class="absolute top-0 translate-y-2/4 end-0 text-center">
                                    <a href="#!" data-type="youtube" data-id="S_CGed6E610" class="lightbox size-20 rounded-full shadow-lg dark:shadow-gray-700 inline-flex items-center justify-center bg-white dark:bg-slate-900 text-emerald-600 dark:text-white">
                                        <i class="mdi mdi-play inline-flex items-center justify-center text-2xl"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="absolute md:-end-5 end-0 -bottom-16">
                                <img src="assets/images/about/ab02.jpg" class="lg:w-[280px] w-[200px] border-8 border-white dark:border-slate-900 rounded-md shadow dark:shadow-gray-700" alt="">
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-7 md:col-span-6 mt-14 md:mt-0">
                        <div class="lg:ms-5">
                            <h3 class="mb-6 md:text-[26px] text-2xl md:leading-normal leading-normal font-semibold">Frequently Asked Questions</h3>

                            <p class="text-slate-400 max-w-xl">Search all the open positions on the web. Get your own personalized salary estimate. Read reviews on over 30000+ companies worldwide.</p>

                            <div id="accordion-collapseone" data-accordion="collapse" class="mt-8">
                                <div class="relative shadow dark:shadow-gray-800 rounded-md overflow-hidden">
                                    <h2 class="text-base font-semibold" id="accordion-collapse-heading-1">
                                        <button type="button" class="flex justify-between items-center p-5 w-full font-semibold text-start" data-accordion-target="#accordion-collapse-body-1" aria-expanded="true" aria-controls="accordion-collapse-body-1">
                                            <span>How does it work ?</span>
                                            <svg data-accordion-icon class="size-4 rotate-180 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </h2>
                                    <div id="accordion-collapse-body-1" class="hidden" aria-labelledby="accordion-collapse-heading-1">
                                        <div class="p-5">
                                            <p class="text-slate-400 dark:text-gray-400">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative shadow dark:shadow-gray-800 rounded-md overflow-hidden mt-4">
                                    <h2 class="text-base font-semibold" id="accordion-collapse-heading-2">
                                        <button type="button" class="flex justify-between items-center p-5 w-full font-semibold text-start" data-accordion-target="#accordion-collapse-body-2" aria-expanded="false" aria-controls="accordion-collapse-body-2">
                                            <span>Do I need a designer to use Jobstack ?</span>
                                            <svg data-accordion-icon class="size-4 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </h2>
                                    <div id="accordion-collapse-body-2" class="hidden" aria-labelledby="accordion-collapse-heading-2">
                                        <div class="p-5">
                                            <p class="text-slate-400 dark:text-gray-400">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative shadow dark:shadow-gray-800 rounded-md overflow-hidden mt-4">
                                    <h2 class="text-base font-semibold" id="accordion-collapse-heading-3">
                                        <button type="button" class="flex justify-between items-center p-5 w-full font-semibold text-start" data-accordion-target="#accordion-collapse-body-3" aria-expanded="false" aria-controls="accordion-collapse-body-3">
                                            <span>What do I need to do to start selling ?</span>
                                            <svg data-accordion-icon class="size-4 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </h2>
                                    <div id="accordion-collapse-body-3" class="hidden" aria-labelledby="accordion-collapse-heading-3">
                                        <div class="p-5">
                                            <p class="text-slate-400 dark:text-gray-400">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--end grid-->
            </div><!--end container-->

            <div class="container-fluid md:mt-24 mt-16">
                <div class="container">
                    <div class="grid grid-cols-1">
                        <div class="relative overflow-hidden lg:px-8 px-6 py-10 rounded-xl shadow-lg dark:shadow-gray-700">
                            <div class="grid md:grid-cols-12 grid-cols-1 items-center gap-[30px]">
                                <div class="lg:col-span-8 md:col-span-7">
                                    <div class="md:text-start text-center relative z-1">
                                        <h3 class="text-2xl font-semibold text-black dark:text-white mb-4">Explore a job now!</h3>
                                        <p class="text-slate-400 max-w-xl">Search all the open positions on the web. Get your own personalized salary estimate. Read reviews on over 30000+ companies worldwide.</p>
                                    </div>
                                </div><!--end col-->

                                <div class="lg:col-span-4 md:col-span-5">
                                    <div class="text-end relative z-1">
                                        <a href="employer-detail.html" class="btn bg-emerald-600 hover:bg-emerald-700 border-emerald-600 dark:border-emerald-600 text-white rounded-md">Apply Now</a>
                                        <a href="aboutus.html" class="btn bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white rounded-md ms-2">Learn More</a>
                                    </div>
                                </div><!--end col-->
                            </div><!--end grid-->

                            <div class="absolute -top-5 -start-5">
                                <div class="uil uil-envelope lg:text-[150px] text-7xl text-black/5 dark:text-white/5 ltr:-rotate-45 rtl:rotate-45"></div>
                            </div>

                            <div class="absolute -bottom-5 -end-5">
                                <div class="uil uil-pen lg:text-[150px] text-7xl text-black/5 dark:text-white/5 rtl:-rotate-90"></div>
                            </div>
                        </div>
                    </div><!--end grid-->
                </div><!--end container-->
            </div><!--end container-->
        </section><!--end section-->
        <!-- End -->
@endsection
