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
                   <li class="inline breadcrumb-item text-[15px] font-semibold duration-500 ease-in-out text-white/50 hover:text-white"><a href="{{route('home')}}">Jobstack</a></li>
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
                    @forelse($jobCategories as $jobCategory)
                    <div class="group px-3 py-10 rounded-md shadow dark:shadow-gray-700 hover:shadow-emerald-600/10 dark:hover:shadow-emerald-600/10 text-center bg-white dark:bg-slate-900 hover:bg-emerald-600/5 dark:hover:bg-emerald-600/5 transition duration-500">
                        <div class="size-16 bg-emerald-600/5 group-hover:bg-emerald-600
                        text-emerald-600 group-hover:text-white rounded-md text-2xl
                        flex align-middle justify-center items-center shadow-sm dark:shadow-gray-700
                        transition duration-500 mx-auto">
                            <img src="{{$jobCategory->category_image}}" class="size-8" alt="{{$jobCategory->job_category}}">
                        </div>

                        <div class="content mt-6">
                            <a href="{{ url('jobs?category=' . $jobCategory->job_category) }}" class="title text-lg font-semibold hover:text-emerald-600">
                               {{ucwords($jobCategory->job_category)}}</a>
                            <p class="text-slate-400 mt-3">{{$jobCategory->total_jobs}} Jobs</p>
                        </div>
                    </div><!--end content-->
                    @empty
                        No Category Found
                    @endforelse
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
                            <p class="text-slate-400 max-w-xl">Search all the open positions on the web. Get your own personalized salary estimate. Read reviews on over 30,000+ companies worldwide.</p>
                            <div id="accordion-collapseone" data-accordion="collapse" class="mt-8">
                                <div class="relative shadow dark:shadow-gray-800 rounded-md overflow-hidden">
                                    <h2 class="text-base font-semibold" id="accordion-collapse-heading-1">
                                        <button type="button" class="flex justify-between items-center p-5 w-full font-semibold text-start" data-accordion-target="#accordion-collapse-body-1" aria-expanded="true" aria-controls="accordion-collapse-body-1">
                                            <span>How does it work?</span>
                                            <svg data-accordion-icon class="size-4 rotate-180 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </h2>
                                    <div id="accordion-collapse-body-1" class="hidden" aria-labelledby="accordion-collapse-heading-1">
                                        <div class="p-5">
                                            <p class="text-slate-400 dark:text-gray-400">Our application aggregates job postings from multiple sources such as Upwork, LinkedIn, and Indeed. You can search for jobs based on your criteria, and generate personalized cover letters directly from the job details page.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative shadow dark:shadow-gray-800 rounded-md overflow-hidden mt-4">
                                    <h2 class="text-base font-semibold" id="accordion-collapse-heading-3">
                                        <button type="button" class="flex justify-between items-center p-5 w-full font-semibold text-start" data-accordion-target="#accordion-collapse-body-3" aria-expanded="false" aria-controls="accordion-collapse-body-3">
                                            <span>Can I customize the generated cover letters?</span>
                                            <svg data-accordion-icon class="size-4 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </h2>
                                    <div id="accordion-collapse-body-3" class="hidden" aria-labelledby="accordion-collapse-heading-3">
                                        <div class="p-5">
                                            <p class="text-slate-400 dark:text-gray-400">Yes, the cover letters generated by our application can be customized to better fit the specific job you're applying for. You can edit the content before finalizing your application.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative shadow dark:shadow-gray-800 rounded-md overflow-hidden mt-4">
                                    <h2 class="text-base font-semibold" id="accordion-collapse-heading-4">
                                        <button type="button" class="flex justify-between items-center p-5 w-full font-semibold text-start" data-accordion-target="#accordion-collapse-body-4" aria-expanded="false" aria-controls="accordion-collapse-body-4">
                                            <span>Is there a fee to use the application?</span>
                                            <svg data-accordion-icon class="size-4 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </h2>
                                    <div id="accordion-collapse-body-4" class="hidden" aria-labelledby="accordion-collapse-heading-4">
                                        <div class="p-5">
                                            <p class="text-slate-400 dark:text-gray-400">
                                            Yes, Its completely free to use our application.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative shadow dark:shadow-gray-800 rounded-md overflow-hidden mt-4">
                                    <h2 class="text-base font-semibold" id="accordion-collapse-heading-5">
                                        <button type="button" class="flex justify-between items-center p-5 w-full font-semibold text-start" data-accordion-target="#accordion-collapse-body-5" aria-expanded="false" aria-controls="accordion-collapse-body-5">
                                            <span>How often are the job listings updated?</span>
                                            <svg data-accordion-icon class="size-4 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </h2>
                                    <div id="accordion-collapse-body-5" class="hidden" aria-labelledby="accordion-collapse-heading-5">
                                        <div class="p-5">
                                            <p class="text-slate-400 dark:text-gray-400">Our job listings are updated daily to ensure you have access to the latest job opportunities from across various platforms.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative shadow dark:shadow-gray-800 rounded-md overflow-hidden mt-4">
                                    <h2 class="text-base font-semibold" id="accordion-collapse-heading-6">
                                        <button type="button" class="flex justify-between items-center p-5 w-full font-semibold text-start" data-accordion-target="#accordion-collapse-body-6" aria-expanded="false" aria-controls="accordion-collapse-body-6">
                                            <span>How do I create a profile?</span>
                                            <svg data-accordion-icon class="size-4 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </h2>
                                    <div id="accordion-collapse-body-6" class="hidden" aria-labelledby="accordion-collapse-heading-6">
                                        <div class="p-5">
                                            <p class="text-slate-400 dark:text-gray-400">To create a profile, click on the 'Sign Up' button on the top right corner of the homepage. Fill in your details, including your resume, skills, and preferences. Once completed, you can start searching for jobs and generating cover letters.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative shadow dark:shadow-gray-800 rounded-md overflow-hidden mt-4">
                                    <h2 class="text-base font-semibold" id="accordion-collapse-heading-8">
                                        <button type="button" class="flex justify-between items-center p-5 w-full font-semibold text-start" data-accordion-target="#accordion-collapse-body-8" aria-expanded="false" aria-controls="accordion-collapse-body-8">
                                            <span>What types of jobs are available?</span>
                                            <svg data-accordion-icon class="size-4 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </h2>
                                    <div id="accordion-collapse-body-8" class="hidden" aria-labelledby="accordion-collapse-heading-8">
                                        <div class="p-5">
                                            <p class="text-slate-400 dark:text-gray-400">Our platform features a wide variety of jobs, including remote, freelance, part-time, and full-time positions across different industries such as tech, healthcare, finance, education, and more.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative shadow dark:shadow-gray-800 rounded-md overflow-hidden mt-4">
                                    <h2 class="text-base font-semibold" id="accordion-collapse-heading-9">
                                        <button type="button" class="flex justify-between items-center p-5 w-full font-semibold text-start" data-accordion-target="#accordion-collapse-body-9" aria-expanded="false" aria-controls="accordion-collapse-body-9">
                                            <span>Can I set up job alerts?</span>
                                            <svg data-accordion-icon class="size-4 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </h2>
                                    <div id="accordion-collapse-body-9" class="hidden" aria-labelledby="accordion-collapse-heading-9">
                                        <div class="p-5">
                                            <p class="text-slate-400 dark:text-gray-400">Yes, you can set up job alerts based on your preferred job title, industry, location, and other criteria. You'll receive notifications when new jobs matching your preferences are posted.</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div><!--end grid-->
            </div><!--end container-->
        </section><!--end section-->
        <!-- End -->
@endsection
