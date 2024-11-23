@extends('layouts.app')
@section('main-content')
        <!-- Start Hero -->
        <section class="relative table w-full py-36 pb-0 lg:py-44 lg:pb-0 bg-orange-600/5 dark:bg-orange-600/10">
            <div class="container relative">
                <div class="grid lg:grid-cols-12 md:grid-cols-2 grid-cols-1 items-center gap-[30px]">
                    <div class="lg:col-span-7">
                        <div class="md:me-6 md:mb-20">
                            <h4 class="lg:leading-normal leading-normal text-4xl lg:text-5xl mb-5 font-bold">FIND Your<br> <span class="text-emerald-600">Dream Job</span><br> with Geezap</h4>
                            <p class="text-lg max-w-xl">Find Jobs, Employment & Career Opportunities. We are here to help you land your dream job</p>

                            <div class="grid lg:grid-cols-12 grid-cols-1" id="reserve-form">
                                <div class="lg:col-span-10 mt-8">
                                    <div class="bg-white dark:bg-slate-900 border-0 shadow rounded p-3">
                                        <form action="{{route('job.index')}}" method="GET">
                                            <div class="registration-form text-dark text-start">
                                                <div class="grid md:grid-cols-12 grid-cols-1 md:gap-0 gap-6">
                                                    <div class="lg:col-span-8 md:col-span-7">
                                                        <div class="filter-search-form relative filter-border">
                                                            <i class="uil uil-briefcase-alt icons"></i>
                                                            <input name="search" type="text" id="job-keyword" class="form-input filter-input-box bg-gray-50 dark:bg-slate-800 border-0" placeholder="Search your Keywords">
                                                        </div>
                                                    </div>

                                                    <div class="lg:col-span-4 md:col-span-5">
                                                        <button style="height: 60px;"  type="submit" class="btn bg-emerald-600 hover:bg-emerald-700 border-emerald-600
                                                                    hover:border-emerald-700 text-white searchbtn submit-btn w-full">Search</button>
                                                    </div>
                                                </div><!--end grid-->
                                            </div><!--end container-->
                                        </form>
                                    </div>
                                </div><!--ed col-->
                            </div><!--end grid-->

                            <div class="mt-4">
                                <span class="text-slate-400"><span class="text-dark">Popular Searches :</span> Designer, Developer, Web, IOS, PHP Senior Engineer</span>
                            </div>
                        </div>
                    </div><!--end col-->

                    <div class="lg:col-span-5">
                        <div class="relative">
                            <img src="{{asset('assets/images/hero.png')}}" alt="">

                            <div class="absolute lg:top-48 top-56 xl:-start-20 lg:-start-10 md:-start-4 start-2 p-4 rounded-lg shadow-md dark:shadow-gray-800 bg-white dark:bg-slate-900 w-60 z-2">
                                <h5 class="text-lg font-semibold mb-3">5k+ candidates get job</h5>

                                <ul class="list-none relative">
                                    <li class="inline-block relative"><a href="#"><img src="{{asset('assets/images/team/01.jpg')}}" class="size-10 rounded-full shadow-md dark:shadow-gray-700 border-4 border-white dark:border-slate-900 relative hover:z-1 hover:scale-105 transition-all duration-500" alt=""></a></li>
                                    <li class="inline-block relative -ms-3"><a href="#"><img src="{{asset('assets/images/team/02.jpg')}}" class="size-10 rounded-full shadow-md dark:shadow-gray-700 border-4 border-white dark:border-slate-900 relative hover:z-1 hover:scale-105 transition-all duration-500" alt=""></a></li>
                                    <li class="inline-block relative -ms-3"><a href="#"><img src="{{asset('assets/images/team/03.jpg')}}" class="size-10 rounded-full shadow-md dark:shadow-gray-700 border-4 border-white dark:border-slate-900 relative hover:z-1 hover:scale-105 transition-all duration-500" alt=""></a></li>
                                    <li class="inline-block relative -ms-3"><a href="#"><img src="{{asset('assets/images/team/04.jpg')}}" class="size-10 rounded-full shadow-md dark:shadow-gray-700 border-4 border-white dark:border-slate-900 relative hover:z-1 hover:scale-105 transition-all duration-500" alt=""></a></li>
                                    <li class="inline-block relative -ms-3"><a href="#"><img src="{{asset('assets/images/team/05.jpg')}}" class="size-10 rounded-full shadow-md dark:shadow-gray-700 border-4 border-white dark:border-slate-900 relative hover:z-1 hover:scale-105 transition-all duration-500" alt=""></a></li>
                                    <li class="inline-block relative -ms-3"><a href="#" class="btn btn-icon table-cell rounded-full bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700 text-white hover:z-1 hover:scale-105"><i class="uil uil-plus"></i></a></li>
                                </ul>
                            </div>

                            <div class="absolute flex justify-between items-center bottom-6 lg:-end-10 end-2 p-4 rounded-lg shadow-md dark:shadow-gray-800 bg-white dark:bg-slate-900 w-max">
                                <i class="uil uil-bell text-[24px] text-amber-500"></i>
                                <p class="text-lg font-semibold mb-0 ms-2">Job Alert Subscribe</p>
                            </div>

                            <div class="overflow-hidden after:content-[''] after:absolute after:size-16 after:bg-emerald-600/30 after:top-20 after:start-20 after:-z-1 after:rounded-lg after:animate-[spin_10s_linear_infinite]"></div>
                        </div>
                    </div><!--end col-->
                </div><!--end grid-->
            </div><!--end container-->
        </section><!--end section-->
        <!-- End Hero -->

        <!-- Start -->
        <section class="relative md:py-24 py-16">

            <div class="container md:mt-24 mt-16">
                <div class="grid grid-cols-1 pb-8 text-center">
                    <h3 class="mb-4 md:text-[26px] md:leading-normal text-2xl leading-normal font-semibold">Latest Jobs</h3>

                    <p class="text-slate-400 max-w-xl mx-auto">Search all the open positions on the web. Get your own personalized salary estimate. Explore over 3000+ jobs worldwide.</p>
                </div><!--end grid-->

                <div class="grid md:grid-cols-2 mt-8 gap-[30px]">

                    @forelse($latestJobs as $latestJob)
                    <div class="group rounded-lg shadow hover:shadow-lg dark:shadow-gray-700 transition-all duration-500">
                        <div class="flex items-center justify-between p-6">
                            <div class="flex items-center">
                                <div class="size-14 flex items-center justify-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-700 rounded-md">
                                    @if($latestJob->employer_logo)
                                        <img src="{{$latestJob->employer_logo}}" class="size-8"  alt="{{$latestJob->employer_name}}">
                                    @endif
                                </div>

                                <div class="ms-3">
                                    <a href="{{route('job.show', $latestJob->slug)}}"
                                       class="block text-[16px] font-semibold hover:text-emerald-600 transition-all duration-500">{{$latestJob->employer_name}}</a>
                                    <span class="block text-sm text-slate-400">{{$latestJob->posted_at?->diffForHumans()}}</span>
                                </div>
                            </div>

                            <a href="{{route('job.show', $latestJob->slug)}}"
                               class="btn btn-icon btn-lg rounded-full bg-emerald-600/5
                               group-hover:bg-emerald-600 border border-slate-100 dark:border-slate-800
                               text-emerald-600 group-hover:text-white"><i class="uil uil-arrow-up-right"></i></a>
                        </div>

                        <div class="lg:flex items-center justify-between border-t border-gray-100 dark:border-gray-800 p-6">
                            <div>
                                <a href="{{route('job.show', $latestJob->slug)}}" class="text-lg font-semibold hover:text-emerald-600">{{$latestJob->job_title}}</a>
                                <p class="text-slate-400 mt-1">Total Openings: 1</p>
                            </div>

                            <p class="text-slate-400 lg:mt-0 mt-4">
                                @if($latestJob->min_salary && $latestJob->max_salary)
                                    <i class="uil uil-usd-circle text-[20px] text-emerald-600"></i>
                                    ${{$latestJob->min_salary}} - ${{$latestJob->max_salary}}/${{$latestJob->salary_period}}
                                @endif
                            </p>
                        </div>

                        <div class="px-6 py-2 bg-slate-50 dark:bg-slate-800 flex justify-between items-center">
                            <div>

                            </div>
                            <span
                                class="inline-block me-1 text-slate-400">
                                <i class="uil uil-map-marker text-[18px] text-slate-900 dark:text-white me-1"></i>
                                @if($latestJob->country)
                                    {{$latestJob->country}}
                                @endif
                            </span>
                        </div>
                    </div><!--end content-->
                    @empty
                        No Jobs Found
                    @endforelse

                </div><!--end grid-->

                <div class="grid md:grid-cols-12 grid-cols-1 mt-8">
                    <div class="md:col-span-12 text-center">
                        <a href="job-list-five.html" class="btn btn-link text-slate-400 hover:text-emerald-600 after:bg-emerald-600 duration-500 ease-in-out">See More Jobs <i class="uil uil-arrow-right align-middle"></i></a>
                    </div>
                </div><!--end grid-->
            </div><!--end container-->

            <div class="container md:mt-24 mt-16">
                <div class="relative grid md:grid-cols-3 grid-cols-1 items-center gap-[30px] z-1">
                    <div class="counter-box text-center">
                        <h1 class="lg:text-5xl text-4xl font-semibold mb-2 dark:text-white">
                            <span class="counter-value" data-target="@if($todayAddedJobsCount){{$todayAddedJobsCount}} @else 100 @endif">
                            </span>+</h1>
                        <h5 class="counter-head text-sm font-semibold text-slate-400 uppercase">Today Added Jobs</h5>
                    </div><!--end counter box-->

                    <div class="counter-box text-center">
                        <h1 class="lg:text-5xl text-4xl font-semibold mb-2 dark:text-white">
                            <span class="counter-value" data-target="@if($jobCategoriesJobsCount){{$jobCategoriesJobsCount}}@else 250 @endif">
                            </span>+</h1>
                        <h5 class="counter-head text-sm font-semibold text-slate-400 uppercase">Job Categories Jobs</h5>
                    </div><!--end counter box-->

                    <div class="counter-box text-center">
                        <h1 class="lg:text-5xl text-4xl font-semibold mb-2 dark:text-white">
                            <span class="counter-value" data-target="@if($jobCategoriesCount)  {{$jobCategoriesCount}} @else 20 @endif">
                            </span>+</h1>
                        <h5 class="counter-head text-sm font-semibold text-slate-400 uppercase">Job Categories</h5>
                    </div><!--end counter box-->
                </div>
            </div><!--end container-->
        </section><!--end section-->
        <!-- End -->


        <!-- Start -->
        <section class="relative md:py-24 py-16">
            <div class="container">
                <div class="grid grid-cols-1 pb-8 text-center">
                    <h3 class="mb-4 md:text-[26px] md:leading-normal text-2xl leading-normal font-semibold">Browse by Category</h3>

                    <p class="text-slate-400 max-w-xl mx-auto">Browse over 3000+ job with your desired category</p>
                </div><!--end grid-->

                <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 mt-8 gap-[30px]">

                    @forelse($jobCategories as $category)
                        <div class="group relative p-6 rounded-md shadow dark:shadow-gray-700 mt-6">
                            <div class="size-14 flex items-center justify-center bg-white dark:bg-slate-900 shadow-md dark:shadow-gray-700 rounded-md relative -mt-12">
                                <img src="{{$category->category_image}}" class="size-8" alt="{{$category->job_category}}">
                            </div>

                            <div class="mt-4">
                                <a href="{{ url('jobs?category=' . $category->job_category) }}" class="text-lg hover:text-emerald-600 font-semibold">{{ ucwords($category->job_category) }}</a>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between">
                                <span class="text-slate-400"><i class="uil uil-map-marker"></i> {{ $category->country }}</span>
                                <span class="block font-semibold text-emerald-600">{{ $category->total_jobs }} Jobs</span>
                            </div>
                        </div><!--end content-->
                    @empty
                        No Job Categories Found
                    @endforelse


                </div><!--end grid-->

                <div class="grid md:grid-cols-12 grid-cols-1 mt-6">
                    <div class="md:col-span-12 text-center">
                        <a href="{{route('job.categories')}}"
                           class="btn btn-link text-slate-400 hover:text-emerald-600
                           after:bg-emerald-600 duration-500 ease-in-out">See More Categories <i class="uil uil-arrow-right align-middle"></i></a>
                    </div>
                </div><!--end grid-->
            </div><!--end container-->

        </section><!--end section-->
        <!-- End -->
@endsection
