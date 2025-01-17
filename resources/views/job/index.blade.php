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
                   <li class="inline breadcrumb-item text-[15px] font-semibold duration-500 ease-in-out text-white/50 hover:text-white"><a href="{{route('home')}}">Geezap</a></li>
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
                            <form method="GET" action="{{ route('job.index') }}">
                                @foreach(request()->except(['page', 'search', 'category', 'fulltime', 'contractor', 'parttime']) as $key => $value)
                                    @if(!empty($value))
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach

                                <div class="grid grid-cols-1 gap-3">
                                    <div>
                                        <label for="searchname" class="font-semibold">Search Company</label>
                                        <div class="relative mt-2">
                                            <i class="uil uil-search text-lg absolute top-[5px] start-3"></i>
                                            <input name="search" id="searchname" type="text" class="form-input border border-slate-100 dark:border-slate-800 ps-10" placeholder="Search" value="{{ request('search') }}">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="font-semibold">Categories</label>
                                        <select name="category" class="form-select form-input border border-slate-100 dark:border-slate-800 block w-full mt-1">
                                            <option value="">Select Category</option>
                                            @foreach(App\Enums\JobCategory::cases() as $category)
                                                <option value="{{ $category->value }}" {{ request('category') == $category->value ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="font-semibold">Job Types</label>
                                        <div class="block mt-2">
                                            <div class="flex justify-between">
                                                <div class="inline-flex items-center mb-0">
                                                    <input class="form-checkbox rounded border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50 me-2"
                                                           type="checkbox" name="fulltime" id="fulltime" value="fulltime" {{ request('fulltime') ? 'checked' : '' }}>
                                                    <label class="form-checkbox-label text-slate-400" for="fulltime">Full Time</label>
                                                </div>
                                            </div>
                                            <div class="flex justify-between">
                                                <div class="inline-flex items-center mb-0">
                                                    <input class="form-checkbox rounded border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50 me-2"
                                                           type="checkbox" name="contractor" id="contractor" value="contractor" {{ request('contractor') ? 'checked' : '' }}>
                                                    <label class="form-checkbox-label text-slate-400" for="contractor">Contractor</label>
                                                </div>
                                            </div>
                                            <div class="flex justify-between">
                                                <div class="inline-flex items-center mb-0">
                                                    <input class="form-checkbox rounded border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50 me-2"
                                                           type="checkbox" name="parttime" id="parttime" value="parttime" {{ request('parttime') ? 'checked' : '' }}>
                                                    <label class="form-checkbox-label text-slate-400" for="parttime">Part Time</label>
                                                </div>
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
                            @forelse($jobs as $job)

                            <div class="group relative overflow-hidden lg:flex justify-between items-center rounded shadow hover:shadow-md dark:shadow-gray-700 transition-all duration-500 p-5">
                                <div class="flex items-center">
                                    <div class="size-14 flex items-center justify-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-700 rounded-md">
                                        <img src="{{$job->employer_logo}}" class="size-8"  alt="{{$job->employer_name}}">
                                    </div>
                                    <a href="{{route('job.show', $job->slug)}}"
                                       class="text-lg hover:text-emerald-600 font-semibold
                                       transition-all duration-500 ms-3 min-w-[150px]">{{$job->job_title}}</a>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-4">
                                    <span class="block"><span class="bg-emerald-600/10 inline-block
                                    text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">{{$job->employment_type}}</span></span>
                                    <span class="block text-slate-400 text-sm md:mt-1 mt-0">
                                        <i class="uil uil-clock"></i> {{ $job->posted_at?->dayWithSuffix() . '-' . $job->posted_at?->format('M-Y') }}</span>
                                </div>

                                <div class="lg:block flex justify-between lg:mt-0 mt-2">
                                    <span class="text-slate-400"><i class="uil uil-map-marker"></i> {{$job->country}}</span>
                                    @if($job->min_salary && $job->max_salary)
                                    <span class="block font-semibold lg:mt-1 mt-0">
                                        <i class="uil uil-dollar-sign"></i>
                                        {{ \App\Helpers\NumberFormatter::formatNumber($job->min_salary) }} -
                                        {{ \App\Helpers\NumberFormatter::formatNumber($job->max_salary) }} / {{$job->salary_period}}
                                    </span>
                                    @endif
                                </div>

                                <div class="lg:mt-0 mt-4">
                                    <a href="{{route('job.show', $job->slug)}}"
                                       class="btn rounded-md bg-emerald-600 hover:bg-emerald-700 border-emerald-600
                                       hover:border-emerald-700 text-white md:ms-2 w-full md:w-auto">View Details</a>
                                </div>

                            </div><!--end content-->
                            @empty
                                No Job Found
                            @endforelse

                        </div><!--end grid-->

                        <div class="grid md:grid-cols-12 grid-cols-1 mt-8">
                            <div class="md:col-span-12 text-center">
                                <nav aria-label="Page navigation example">
                                    <ul class="inline-flex items-center -space-x-px">
                                        @php
                                            $query = array_filter(request()->except('page'), fn($value) => !is_null($value) && $value !== '');
                                        @endphp

                                        @if ($jobs->previousPageUrl())
                                            <li>
                                                <a href="{{ $jobs->previousPageUrl() }}&{{ http_build_query($query) }}" class="pagination-item">
                                                    <i class="uil uil-angle-left text-[20px] rtl:rotate-180 rtl:-mt-1"></i>
                                                    <span>Previous Page</span>
                                                </a>
                                            </li>
                                        @endif

                                        <li>
                                        <span class="pagination-item active">
                                            Page {{ $jobs->currentPage() }}
                                        </span>
                                        </li>

                                        @if ($jobs->nextPageUrl())
                                            <li>
                                                <a href="{{ $jobs->nextPageUrl() }}&{{ http_build_query($query) }}" class="pagination-item">
                                                    <span>Next Page</span>
                                                    <i class="uil uil-angle-right text-[20px] rtl:rotate-180 rtl:-mt-1"></i>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>


                            </div><!--end col-->
                        </div><!--end grid-->
                    </div><!--end col-->
                </div><!--end grid-->
            </div><!--end container-->

        </section><!--end section-->
        <!-- End -->

@endsection
@push('extra-css')
    <style>
        .pagination-item {
            display: inline-flex;
            justify-center: center;
            align-items: center;
            text-slate-400;
            bg-white;
            dark:bg-slate-900;
            border: 1px solid;
            border-color: gray-100;
            dark:border-gray-800;
            hover:border-emerald-600;
            dark:hover:border-emerald-600;
            hover:bg-emerald-600;
            dark:hover:bg-emerald-600;
            padding: 10px 15px;
            border-radius: 5px;
            margin: 0 5px;
        }
        .pagination-item.active {
            bg-emerald-600;
            text-white;
            border-color: emerald-600;
        }
        .pagination-item span {
            margin-left: 8px;
        }
    </style>
@endpush

