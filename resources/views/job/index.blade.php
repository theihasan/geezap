@extends('layouts.app')
@section('main-content')
        <!-- Start Hero -->
        <section class="relative table w-full py-36 bg-[url('../../assets/images/hero/bg.html')] bg-top bg-no-repeat bg-cover">
            <div class="absolute inset-0 bg-emerald-900/90"></div>
            <div class="container">
                <div class="grid grid-cols-1 text-center mt-10">
                    <h3 class="md:text-3xl text-2xl md:leading-snug tracking-wide leading-snug font-medium text-white">Job Vacancies</h3>
                </div><!--end grid-->
            </div><!--end container-->
            <div class="absolute text-center z-10 bottom-5 start-0 end-0 mx-3">
                <ul class="breadcrumb tracking-[0.5px] breadcrumb-light mb-0 inline-block">
                   <li class="inline breadcrumb-item text-[15px] font-semibold duration-500 ease-in-out text-white/50 hover:text-white"><a href="index.html">Jobstack</a></li>
                    <li class="inline breadcrumb-item text-[15px] font-semibold duration-500 ease-in-out text-white" aria-current="page">Job List</li>
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
                <div class="grid md:grid-cols-12 grid-cols-1 gap-[30px]">
                    <div class="lg:col-span-4 md:col-span-6">
                        <div class="shadow dark:shadow-gray-700 p-6 rounded-md bg-white dark:bg-slate-900 sticky top-20">
                            <form>
                                <div class="grid grid-cols-1 gap-3">
                                    <div>
                                        <label for="searchname" class="font-semibold">Search Company</label>
                                        <div class="relative mt-2">
                                            <i class="uil uil-search text-lg absolute top-[5px] start-3"></i>
                                            <input name="search" id="searchname" type="text" class="form-input border border-slate-100 dark:border-slate-800 ps-10" placeholder="Search">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="font-semibold">Categories</label>
                                        <select class="form-select form-input border border-slate-100 dark:border-slate-800 block w-full mt-1">
                                            <option value="WD">Web Designer</option>
                                            <option value="WD">Web Developer</option>
                                            <option value="UI">UI / UX Desinger</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="font-semibold">Location</label>
                                        <select class="form-select form-input border border-slate-100 dark:border-slate-800 block w-full mt-1">
                                            <option value="NY">New York</option>
                                            <option value="MC">North Carolina</option>
                                            <option value="SC">South Carolina</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="font-semibold">Job Types</label>
                                        <div class="block mt-2">
                                            <div class="flex justify-between">
                                                <div class="inline-flex items-center mb-0">
                                                    <input class="form-checkbox rounded border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50 me-2" type="checkbox" value="" id="fulltime">
                                                    <label class="form-checkbox-label text-slate-400" for="fulltime">Full Time</label>
                                                </div>

                                                <span class="bg-emerald-600/10 text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full h-5">3</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <div class="inline-flex items-center mb-0">
                                                    <input class="form-checkbox rounded border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50 me-2" type="checkbox" value="" id="parttime">
                                                    <label class="form-checkbox-label text-slate-400" for="parttime">Part Time</label>
                                                </div>

                                                <span class="bg-emerald-600/10 text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full h-5">7</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <div class="inline-flex items-center mb-0">
                                                    <input class="form-checkbox rounded border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50 me-2" type="checkbox" value="" id="Freelancing">
                                                    <label class="form-checkbox-label text-slate-400" for="Freelancing">Freelancing</label>
                                                </div>

                                                <span class="bg-emerald-600/10 text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full h-5">4</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <div class="inline-flex items-center mb-0">
                                                    <input class="form-checkbox rounded border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50 me-2" type="checkbox" value="" id="fixedprice">
                                                    <label class="form-checkbox-label text-slate-400" for="fixedprice">Fixed Price</label>
                                                </div>

                                                <span class="bg-emerald-600/10 text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full h-5">6</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <div class="inline-flex items-center mb-0">
                                                    <input class="form-checkbox rounded border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50 me-2" type="checkbox" value="" id="Remote">
                                                    <label class="form-checkbox-label text-slate-400" for="Remote">Remote</label>
                                                </div>

                                                <span class="bg-emerald-600/10 text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full h-5">7</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <div class="inline-flex items-center mb-0">
                                                    <input class="form-checkbox rounded border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50 me-2" type="checkbox" value="" id="hourlybasis">
                                                    <label class="form-checkbox-label text-slate-400" for="hourlybasis">Hourly Basis</label>
                                                </div>

                                                <span class="bg-emerald-600/10 text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full h-5">44</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="font-semibold">Salary</label>
                                        <div class="block mt-2">
                                            <div>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" class="form-radio border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50 me-2" name="radio-colors" value="1" checked>
                                                    <span class="text-slate-400">10k - 15k</span>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" class="form-radio border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50 me-2" name="radio-colors" value="1">
                                                    <span class="text-slate-400">15k - 25k</span>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" class="form-radio border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50 me-2" name="radio-colors" value="1">
                                                    <span class="text-slate-400">more than 25K</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <input type="submit" class="btn bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700 text-white rounded-md w-full" value="Apply Filter">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!--end col-->

                    <div class="lg:col-span-8 md:col-span-6">
                        <div class="grid grid-cols-1 gap-[30px]">
                            <div class="group relative overflow-hidden lg:flex justify-between items-center rounded shadow hover:shadow-md dark:shadow-gray-700 transition-all duration-500 p-5">
                                <div class="flex items-center">
                                    <div class="size-14 flex items-center justify-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-700 rounded-md">
                                        <img src="assets/images/company/facebook-logo.png" class="size-8"  alt="">
                                    </div>
                                    <a href="job-detail-three.html" class="text-lg hover:text-emerald-600 font-semibold transition-all duration-500 ms-3 min-w-[150px]">Web Designer</a>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-4">
                                    <span class="block"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Full Time</span></span>
                                    <span class="block text-slate-400 text-sm md:mt-1 mt-0"><i class="uil uil-clock"></i> 20th Feb 2023</span>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-2">
                                    <span class="text-slate-400"><i class="uil uil-map-marker"></i> Australia</span>
                                    <span class="block font-semibold lg:mt-1 mt-0">$4,000 - $4,500</span>
                                </div>

                                <div class="lg:mt-0 mt-4">
                                    <a href="#" class="btn btn-icon rounded-full bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white lg:relative absolute top-0 end-0 lg:m-0 m-3"><i data-feather="bookmark" class="size-4"></i></a>
                                    <a href="job-apply.html" class="btn rounded-md bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700 text-white md:ms-2 w-full md:w-auto">Apply Now</a>
                                </div>

                                <span class="w-24 bg-yellow-400 text-white text-center absolute ltr:-rotate-45 rtl:rotate-45 -start-[30px] top-1"><i class="uil uil-star"></i></span>
                            </div><!--end content-->

                            <div class="group relative overflow-hidden lg:flex justify-between items-center rounded shadow hover:shadow-md dark:shadow-gray-700 transition-all duration-500 p-5">
                                <div class="flex items-center">
                                    <div class="size-14 flex items-center justify-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-700 rounded-md">
                                        <img src="assets/images/company/google-logo.png" class="size-8"  alt="">
                                    </div>
                                    <a href="job-detail-three.html" class="text-lg hover:text-emerald-600 font-semibold transition-all duration-500 ms-3 min-w-[150px]">Marketing Director</a>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-4">
                                    <span class="block"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Part Time</span></span>
                                    <span class="block text-slate-400 text-sm md:mt-1 mt-0"><i class="uil uil-clock"></i> 20th Feb 2023</span>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-2">
                                    <span class="text-slate-400"><i class="uil uil-map-marker"></i> USA</span>
                                    <span class="block font-semibold lg:mt-1 mt-0">$4,000 - $4,500</span>
                                </div>

                                <div class="lg:mt-0 mt-4">
                                    <a href="#" class="btn btn-icon rounded-full bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white lg:relative absolute top-0 end-0 lg:m-0 m-3"><i data-feather="bookmark" class="size-4"></i></a>
                                    <a href="job-apply.html" class="btn rounded-md bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700 text-white md:ms-2 w-full md:w-auto">Apply Now</a>
                                </div>
                            </div><!--end content-->

                            <div class="group relative overflow-hidden lg:flex justify-between items-center rounded shadow hover:shadow-md dark:shadow-gray-700 transition-all duration-500 p-5">
                                <div class="flex items-center">
                                    <div class="size-14 flex items-center justify-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-700 rounded-md">
                                        <img src="assets/images/company/android.png" class="size-8"  alt="">
                                    </div>
                                    <a href="job-detail-three.html" class="text-lg hover:text-emerald-600 font-semibold transition-all duration-500 ms-3 min-w-[150px]">App Developer</a>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-4">
                                    <span class="block"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Remote</span></span>
                                    <span class="block text-slate-400 text-sm md:mt-1 mt-0"><i class="uil uil-clock"></i> 20th Feb 2023</span>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-2">
                                    <span class="text-slate-400"><i class="uil uil-map-marker"></i> China</span>
                                    <span class="block font-semibold lg:mt-1 mt-0">$4,000 - $4,500</span>
                                </div>

                                <div class="lg:mt-0 mt-4">
                                    <a href="#" class="btn btn-icon rounded-full bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white lg:relative absolute top-0 end-0 lg:m-0 m-3"><i data-feather="bookmark" class="size-4"></i></a>
                                    <a href="job-apply.html" class="btn rounded-md bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700 text-white md:ms-2 w-full md:w-auto">Apply Now</a>
                                </div>
                            </div><!--end content-->

                            <div class="group relative overflow-hidden lg:flex justify-between items-center rounded shadow hover:shadow-md dark:shadow-gray-700 transition-all duration-500 p-5">
                                <div class="flex items-center">
                                    <div class="size-14 flex items-center justify-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-700 rounded-md">
                                        <img src="assets/images/company/lenovo-logo.png" class="size-8"  alt="">
                                    </div>
                                    <a href="job-detail-three.html" class="text-lg hover:text-emerald-600 font-semibold transition-all duration-500 ms-3 min-w-[150px]">Product Designer</a>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-4">
                                    <span class="block"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">WFH</span></span>
                                    <span class="block text-slate-400 text-sm md:mt-1 mt-0"><i class="uil uil-clock"></i> 20th Feb 2023</span>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-2">
                                    <span class="text-slate-400"><i class="uil uil-map-marker"></i> Dubai</span>
                                    <span class="block font-semibold lg:mt-1 mt-0">$4,000 - $4,500</span>
                                </div>

                                <div class="lg:mt-0 mt-4">
                                    <a href="#" class="btn btn-icon rounded-full bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white lg:relative absolute top-0 end-0 lg:m-0 m-3"><i data-feather="bookmark" class="size-4"></i></a>
                                    <a href="job-apply.html" class="btn rounded-md bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700 text-white md:ms-2 w-full md:w-auto">Apply Now</a>
                                </div>
                            </div><!--end content-->

                            <div class="group relative overflow-hidden lg:flex justify-between items-center rounded shadow hover:shadow-md dark:shadow-gray-700 transition-all duration-500 p-5">
                                <div class="flex items-center">
                                    <div class="size-14 flex items-center justify-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-700 rounded-md">
                                        <img src="assets/images/company/spotify.png" class="size-8"  alt="">
                                    </div>
                                    <a href="job-detail-three.html" class="text-lg hover:text-emerald-600 font-semibold transition-all duration-500 ms-3 min-w-[150px]">C++ Developer</a>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-4">
                                    <span class="block"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Full Time</span></span>
                                    <span class="block text-slate-400 text-sm md:mt-1 mt-0"><i class="uil uil-clock"></i> 20th Feb 2023</span>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-2">
                                    <span class="text-slate-400"><i class="uil uil-map-marker"></i> India</span>
                                    <span class="block font-semibold lg:mt-1 mt-0">$4,000 - $4,500</span>
                                </div>

                                <div class="lg:mt-0 mt-4">
                                    <a href="#" class="btn btn-icon rounded-full bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white lg:relative absolute top-0 end-0 lg:m-0 m-3"><i data-feather="bookmark" class="size-4"></i></a>
                                    <a href="job-apply.html" class="btn rounded-md bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700 text-white md:ms-2 w-full md:w-auto">Apply Now</a>
                                </div>

                                <span class="w-24 bg-yellow-400 text-white text-center absolute ltr:-rotate-45 rtl:rotate-45 -start-[30px] top-1"><i class="uil uil-star"></i></span>
                            </div><!--end content-->

                            <div class="group relative overflow-hidden lg:flex justify-between items-center rounded shadow hover:shadow-md dark:shadow-gray-700 transition-all duration-500 p-5">
                                <div class="flex items-center">
                                    <div class="size-14 flex items-center justify-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-700 rounded-md">
                                        <img src="assets/images/company/linkedin.png" class="size-8"  alt="">
                                    </div>
                                    <a href="job-detail-three.html" class="text-lg hover:text-emerald-600 font-semibold transition-all duration-500 ms-3 min-w-[150px]">Php Developer</a>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-4">
                                    <span class="block"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Remote</span></span>
                                    <span class="block text-slate-400 text-sm md:mt-1 mt-0"><i class="uil uil-clock"></i> 20th Feb 2023</span>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-2">
                                    <span class="text-slate-400"><i class="uil uil-map-marker"></i> Pakistan</span>
                                    <span class="block font-semibold lg:mt-1 mt-0">$4,000 - $4,500</span>
                                </div>

                                <div class="lg:mt-0 mt-4">
                                    <a href="#" class="btn btn-icon rounded-full bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white lg:relative absolute top-0 end-0 lg:m-0 m-3"><i data-feather="bookmark" class="size-4"></i></a>
                                    <a href="job-apply.html" class="btn rounded-md bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700 text-white md:ms-2 w-full md:w-auto">Apply Now</a>
                                </div>

                                <span class="w-24 bg-yellow-400 text-white text-center absolute ltr:-rotate-45 rtl:rotate-45 -start-[30px] top-1"><i class="uil uil-star"></i></span>
                            </div><!--end content-->

                            <div class="group relative overflow-hidden lg:flex justify-between items-center rounded shadow hover:shadow-md dark:shadow-gray-700 transition-all duration-500 p-5">
                                <div class="flex items-center">
                                    <div class="size-14 flex items-center justify-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-700 rounded-md">
                                        <img src="assets/images/company/circle-logo.png" class="size-8"  alt="">
                                    </div>
                                    <a href="job-detail-three.html" class="text-lg hover:text-emerald-600 font-semibold transition-all duration-500 ms-3 min-w-[150px]">Web Designer</a>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-4">
                                    <span class="block"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Full Time</span></span>
                                    <span class="block text-slate-400 text-sm md:mt-1 mt-0"><i class="uil uil-clock"></i> 20th Feb 2023</span>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-2">
                                    <span class="text-slate-400"><i class="uil uil-map-marker"></i> Australia</span>
                                    <span class="block font-semibold lg:mt-1 mt-0">$4,000 - $4,500</span>
                                </div>

                                <div class="lg:mt-0 mt-4">
                                    <a href="#" class="btn btn-icon rounded-full bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white lg:relative absolute top-0 end-0 lg:m-0 m-3"><i data-feather="bookmark" class="size-4"></i></a>
                                    <a href="job-apply.html" class="btn rounded-md bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700 text-white md:ms-2 w-full md:w-auto">Apply Now</a>
                                </div>

                                <span class="w-24 bg-yellow-400 text-white text-center absolute ltr:-rotate-45 rtl:rotate-45 -start-[30px] top-1"><i class="uil uil-star"></i></span>
                            </div><!--end content-->

                            <div class="group relative overflow-hidden lg:flex justify-between items-center rounded shadow hover:shadow-md dark:shadow-gray-700 transition-all duration-500 p-5">
                                <div class="flex items-center">
                                    <div class="size-14 flex items-center justify-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-700 rounded-md">
                                        <img src="assets/images/company/skype.png" class="size-8"  alt="">
                                    </div>
                                    <a href="job-detail-three.html" class="text-lg hover:text-emerald-600 font-semibold transition-all duration-500 ms-3 min-w-[150px]">Marketing Director</a>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-4">
                                    <span class="block"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Part Time</span></span>
                                    <span class="block text-slate-400 text-sm md:mt-1 mt-0"><i class="uil uil-clock"></i> 20th Feb 2023</span>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-2">
                                    <span class="text-slate-400"><i class="uil uil-map-marker"></i> USA</span>
                                    <span class="block font-semibold lg:mt-1 mt-0">$4,000 - $4,500</span>
                                </div>

                                <div class="lg:mt-0 mt-4">
                                    <a href="#" class="btn btn-icon rounded-full bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white lg:relative absolute top-0 end-0 lg:m-0 m-3"><i data-feather="bookmark" class="size-4"></i></a>
                                    <a href="job-apply.html" class="btn rounded-md bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700 text-white md:ms-2 w-full md:w-auto">Apply Now</a>
                                </div>
                            </div><!--end content-->

                            <div class="group relative overflow-hidden lg:flex justify-between items-center rounded shadow hover:shadow-md dark:shadow-gray-700 transition-all duration-500 p-5">
                                <div class="flex items-center">
                                    <div class="size-14 flex items-center justify-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-700 rounded-md">
                                        <img src="assets/images/company/snapchat.png" class="size-8"  alt="">
                                    </div>
                                    <a href="job-detail-three.html" class="text-lg hover:text-emerald-600 font-semibold transition-all duration-500 ms-3 min-w-[150px]">App Developer</a>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-4">
                                    <span class="block"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Remote</span></span>
                                    <span class="block text-slate-400 text-sm md:mt-1 mt-0"><i class="uil uil-clock"></i> 20th Feb 2023</span>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-2">
                                    <span class="text-slate-400"><i class="uil uil-map-marker"></i> China</span>
                                    <span class="block font-semibold lg:mt-1 mt-0">$4,000 - $4,500</span>
                                </div>

                                <div class="lg:mt-0 mt-4">
                                    <a href="#" class="btn btn-icon rounded-full bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white lg:relative absolute top-0 end-0 lg:m-0 m-3"><i data-feather="bookmark" class="size-4"></i></a>
                                    <a href="job-apply.html" class="btn rounded-md bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700 text-white md:ms-2 w-full md:w-auto">Apply Now</a>
                                </div>
                            </div><!--end content-->

                            <div class="group relative overflow-hidden lg:flex justify-between items-center rounded shadow hover:shadow-md dark:shadow-gray-700 transition-all duration-500 p-5">
                                <div class="flex items-center">
                                    <div class="size-14 flex items-center justify-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-700 rounded-md">
                                        <img src="assets/images/company/shree-logo.png" class="size-8"  alt="">
                                    </div>
                                    <a href="job-detail-three.html" class="text-lg hover:text-emerald-600 font-semibold transition-all duration-500 ms-3 min-w-[150px]">Product Designer</a>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-4">
                                    <span class="block"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">WFH</span></span>
                                    <span class="block text-slate-400 text-sm md:mt-1 mt-0"><i class="uil uil-clock"></i> 20th Feb 2023</span>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-2">
                                    <span class="text-slate-400"><i class="uil uil-map-marker"></i> Dubai</span>
                                    <span class="block font-semibold lg:mt-1 mt-0">$4,000 - $4,500</span>
                                </div>

                                <div class="lg:mt-0 mt-4">
                                    <a href="#" class="btn btn-icon rounded-full bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white lg:relative absolute top-0 end-0 lg:m-0 m-3"><i data-feather="bookmark" class="size-4"></i></a>
                                    <a href="job-apply.html" class="btn rounded-md bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700 text-white md:ms-2 w-full md:w-auto">Apply Now</a>
                                </div>
                            </div><!--end content-->

                            <div class="group relative overflow-hidden lg:flex justify-between items-center rounded shadow hover:shadow-md dark:shadow-gray-700 transition-all duration-500 p-5">
                                <div class="flex items-center">
                                    <div class="size-14 flex items-center justify-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-700 rounded-md">
                                        <img src="assets/images/company/telegram.png" class="size-8"  alt="">
                                    </div>
                                    <a href="job-detail-three.html" class="text-lg hover:text-emerald-600 font-semibold transition-all duration-500 ms-3 min-w-[150px]">C++ Developer</a>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-4">
                                    <span class="block"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Full Time</span></span>
                                    <span class="block text-slate-400 text-sm md:mt-1 mt-0"><i class="uil uil-clock"></i> 20th Feb 2023</span>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-2">
                                    <span class="text-slate-400"><i class="uil uil-map-marker"></i> India</span>
                                    <span class="block font-semibold lg:mt-1 mt-0">$4,000 - $4,500</span>
                                </div>

                                <div class="lg:mt-0 mt-4">
                                    <a href="#" class="btn btn-icon rounded-full bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white lg:relative absolute top-0 end-0 lg:m-0 m-3"><i data-feather="bookmark" class="size-4"></i></a>
                                    <a href="job-apply.html" class="btn rounded-md bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700 text-white md:ms-2 w-full md:w-auto">Apply Now</a>
                                </div>

                                <span class="w-24 bg-yellow-400 text-white text-center absolute ltr:-rotate-45 rtl:rotate-45 -start-[30px] top-1"><i class="uil uil-star"></i></span>
                            </div><!--end content-->

                            <div class="group relative overflow-hidden lg:flex justify-between items-center rounded shadow hover:shadow-md dark:shadow-gray-700 transition-all duration-500 p-5">
                                <div class="flex items-center">
                                    <div class="size-14 flex items-center justify-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-700 rounded-md">
                                        <img src="assets/images/company/whatsapp.png" class="size-8"  alt="">
                                    </div>
                                    <a href="job-detail-three.html" class="text-lg hover:text-emerald-600 font-semibold transition-all duration-500 ms-3 min-w-[150px]">Php Developer</a>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-4">
                                    <span class="block"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Remote</span></span>
                                    <span class="block text-slate-400 text-sm md:mt-1 mt-0"><i class="uil uil-clock"></i> 20th Feb 2023</span>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-2">
                                    <span class="text-slate-400"><i class="uil uil-map-marker"></i> Pakistan</span>
                                    <span class="block font-semibold lg:mt-1 mt-0">$4,000 - $4,500</span>
                                </div>

                                <div class="lg:mt-0 mt-4">
                                    <a href="#" class="btn btn-icon rounded-full bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white lg:relative absolute top-0 end-0 lg:m-0 m-3"><i data-feather="bookmark" class="size-4"></i></a>
                                    <a href="job-apply.html" class="btn rounded-md bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700 text-white md:ms-2 w-full md:w-auto">Apply Now</a>
                                </div>

                                <span class="w-24 bg-yellow-400 text-white text-center absolute ltr:-rotate-45 rtl:rotate-45 -start-[30px] top-1"><i class="uil uil-star"></i></span>
                            </div><!--end content-->
                        </div><!--end grid-->

                        <div class="grid md:grid-cols-12 grid-cols-1 mt-8">
                            <div class="md:col-span-12 text-center">
                                <nav aria-label="Page navigation example">
                                    <ul class="inline-flex items-center -space-x-px">
                                        <li>
                                            <a href="#" class="size-[40px] inline-flex justify-center items-center text-slate-400 bg-white dark:bg-slate-900 rounded-s-3xl hover:text-white border border-gray-100 dark:border-gray-800 hover:border-emerald-600 dark:hover:border-emerald-600 hover:bg-emerald-600 dark:hover:bg-emerald-600">
                                                <i class="uil uil-angle-left text-[20px] rtl:rotate-180 rtl:-mt-1"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="size-[40px] inline-flex justify-center items-center text-slate-400 hover:text-white bg-white dark:bg-slate-900 border border-gray-100 dark:border-gray-800 hover:border-emerald-600 dark:hover:border-emerald-600 hover:bg-emerald-600 dark:hover:bg-emerald-600">1</a>
                                        </li>
                                        <li>
                                            <a href="#" class="size-[40px] inline-flex justify-center items-center text-slate-400 hover:text-white bg-white dark:bg-slate-900 border border-gray-100 dark:border-gray-800 hover:border-emerald-600 dark:hover:border-emerald-600 hover:bg-emerald-600 dark:hover:bg-emerald-600">2</a>
                                        </li>
                                        <li>
                                            <a href="#" aria-current="page" class="z-10 size-[40px] inline-flex justify-center items-center text-white bg-emerald-600 border border-emerald-600">3</a>
                                        </li>
                                        <li>
                                            <a href="#" class="size-[40px] inline-flex justify-center items-center text-slate-400 hover:text-white bg-white dark:bg-slate-900 border border-gray-100 dark:border-gray-800 hover:border-emerald-600 dark:hover:border-emerald-600 hover:bg-emerald-600 dark:hover:bg-emerald-600">4</a>
                                        </li>
                                        <li>
                                            <a href="#" class="size-[40px] inline-flex justify-center items-center text-slate-400 hover:text-white bg-white dark:bg-slate-900 border border-gray-100 dark:border-gray-800 hover:border-emerald-600 dark:hover:border-emerald-600 hover:bg-emerald-600 dark:hover:bg-emerald-600">5</a>
                                        </li>
                                        <li>
                                            <a href="#" class="size-[40px] inline-flex justify-center items-center text-slate-400 bg-white dark:bg-slate-900 rounded-e-3xl hover:text-white border border-gray-100 dark:border-gray-800 hover:border-emerald-600 dark:hover:border-emerald-600 hover:bg-emerald-600 dark:hover:bg-emerald-600">
                                                <i class="uil uil-angle-right text-[20px] rtl:rotate-180 rtl:-mt-1"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div><!--end col-->
                        </div><!--end grid-->
                    </div><!--end col-->
                </div><!--end grid-->
            </div><!--end container-->

            <div class="container md:mt-24 mt-16">
                <div class="grid grid-cols-1 pb-8 text-center">
                    <h3 class="mb-4 md:text-[26px] md:leading-normal text-2xl leading-normal font-semibold">Here's why you'll love it Jobstack</h3>

                    <p class="text-slate-400 max-w-xl mx-auto">Search all the open positions on the web. Get your own personalized salary estimate. Read reviews on over 30000+ companies worldwide.</p>
                </div><!--end grid-->

                <div class="grid lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 mt-8 gap-[30px]">
                    <div class="group p-6 shadow dark:shadow-gray-700 rounded-md bg-white hover:bg-emerald-600/5 dark:bg-slate-900 dark:hover:bg-emerald-600/10 text-center transition-all duration-500">
                        <div class="size-16 flex items-center justify-center mx-auto bg-emerald-600/5 group-hover:bg-emerald-600 dark:bg-emerald-600/10 dark:group-hover:bg-emerald-600 shadow dark:shadow-gray-700 rounded-lg transition-all duration-500">
                            <i class="uil uil-phone text-[30px] text-emerald-600 group-hover:text-white"></i>
                        </div>

                        <div class="mt-4">
                            <a href="#" class="text-lg font-semibold hover:text-emerald-600 transition-all duration-500">24/7 Support</a>

                            <p class="text-slate-400 mt-3 mb-2">Many desktop publishing now use and a search for job.</p>

                            <a href="#" class="hover:text-emerald-600 font-medium transition-all duration-500">Read More <i class="uil uil-arrow-right"></i></a>
                        </div>
                    </div><!--end content-->

                    <div class="group p-6 shadow dark:shadow-gray-700 rounded-md bg-white hover:bg-emerald-600/5 dark:bg-slate-900 dark:hover:bg-emerald-600/10 text-center transition-all duration-500">
                        <div class="size-16 flex items-center justify-center mx-auto bg-emerald-600/5 group-hover:bg-emerald-600 dark:bg-emerald-600/10 dark:group-hover:bg-emerald-600 shadow dark:shadow-gray-700 rounded-lg transition-all duration-500">
                            <i class="uil uil-atom text-[30px] text-emerald-600 group-hover:text-white"></i>
                        </div>

                        <div class="mt-4">
                            <a href="#" class="text-lg font-semibold hover:text-emerald-600 transition-all duration-500">Tech & Startup Jobs</a>

                            <p class="text-slate-400 mt-3 mb-2">Many desktop publishing now use and a search for job.</p>

                            <a href="#" class="hover:text-emerald-600 font-medium transition-all duration-500">Read More <i class="uil uil-arrow-right"></i></a>
                        </div>
                    </div><!--end content-->

                    <div class="group p-6 shadow dark:shadow-gray-700 rounded-md bg-white hover:bg-emerald-600/5 dark:bg-slate-900 dark:hover:bg-emerald-600/10 text-center transition-all duration-500">
                        <div class="size-16 flex items-center justify-center mx-auto bg-emerald-600/5 group-hover:bg-emerald-600 dark:bg-emerald-600/10 dark:group-hover:bg-emerald-600 shadow dark:shadow-gray-700 rounded-lg transition-all duration-500">
                            <i class="uil uil-user-arrows text-[30px] text-emerald-600 group-hover:text-white"></i>
                        </div>

                        <div class="mt-4">
                            <a href="#" class="text-lg font-semibold hover:text-emerald-600 transition-all duration-500">Quick & Easy</a>

                            <p class="text-slate-400 mt-3 mb-2">Many desktop publishing now use and a search for job.</p>

                            <a href="#" class="hover:text-emerald-600 font-medium transition-all duration-500">Read More <i class="uil uil-arrow-right"></i></a>
                        </div>
                    </div><!--end content-->

                    <div class="group p-6 shadow dark:shadow-gray-700 rounded-md bg-white hover:bg-emerald-600/5 dark:bg-slate-900 dark:hover:bg-emerald-600/10 text-center transition-all duration-500">
                        <div class="size-16 flex items-center justify-center mx-auto bg-emerald-600/5 group-hover:bg-emerald-600 dark:bg-emerald-600/10 dark:group-hover:bg-emerald-600 shadow dark:shadow-gray-700 rounded-lg transition-all duration-500">
                            <i class="uil uil-hourglass text-[30px] text-emerald-600 group-hover:text-white"></i>
                        </div>

                        <div class="mt-4">
                            <a href="#" class="text-lg font-semibold hover:text-emerald-600 transition-all duration-500">Save Time</a>

                            <p class="text-slate-400 mt-3 mb-2">Many desktop publishing now use and a search for job.</p>

                            <a href="#" class="hover:text-emerald-600 font-medium transition-all duration-500">Read More <i class="uil uil-arrow-right"></i></a>
                        </div>
                    </div><!--end content-->
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

