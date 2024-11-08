@extends('layouts.app')
@section('main-content')
    <section class="relative lg:mt-24 mt-[74px]">
        <div class="lg:container container-fluid">
            <div class="relative shrink-0">
                <img src="assets/images/hero/bg5.jpg"
                     class="h-64 w-full object-cover lg:rounded-xl shadow dark:shadow-gray-700" alt="">
            </div>

            <div class="md:flex mx-4 -mt-12">
                <div class="md:w-full">
                    <div class="relative flex items-end justify-between">
                        <div class="relative flex items-end">
                            <img src="{{asset('assets/images/profile.jpg')}}"
                                 class="size-28 rounded-full shadow dark:shadow-gray-800 ring-4 ring-slate-50 dark:ring-slate-800"
                                 alt="">
                            <div class="ms-4">
                                <h5 class="text-lg font-semibold">{{auth()->user()->name}}</h5>
                                <p class="text-slate-400">{{auth()->user()->occupation}}</p>
                            </div>
                        </div>

                        <div class="">
                            <a href="{{route('profile.update')}}"
                               class="btn btn-icon rounded-full bg-emerald-600/5 hover:bg-emerald-600
                                   border-emerald-600/10 hover:border-emerald-600 text-emerald-600
                                   hover:text-white"><i data-feather="settings" class="size-4"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end -->
    </section>
    <!-- End Hero -->

    <!-- Start -->
    <section class="relative mt-12 md:pb-24 pb-16">
        <div class="container">
            <div class="grid md:grid-cols-12 grid-cols-1 gap-[30px]">
                <div class="lg:col-span-8 md:col-span-7">
                    <h5 class="text-xl font-semibold">{{auth()->user()->name}}</h5>
                    {{auth()->user()->bio}}
                    <h4 class="mt-6 text-xl font-semibold">Skills :</h4>
                    <div class="grid lg:grid-cols-2 grid-cols-1 mt-6 gap-6">

                        @if(auth()->user()->skills)
                            @foreach(json_decode(auth()->user()->skills, true)['skill'] as $index => $skill)
                                @php
                                    $skillLevel = json_decode(auth()->user()->skills, true)['skill_level'][$index];
                                    $percentage = 0;
                                    match ($skillLevel) {
                                        App\Enums\SkillProficiency::BEGINNER->value => $percentage = 33,
                                        App\Enums\SkillProficiency::INTERMEDIATE->value => $percentage = 66,
                                        App\Enums\SkillProficiency::PROFICIENT->value => $percentage = 90,
                                    };
                                @endphp
                                <div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-slate-400">{{ $skill }}</span>
                                        <span class="text-slate-400">{{ $percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-[6px]">
                                        <div class="bg-emerald-600 h-[6px] rounded-full"
                                             style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <h4 class="mt-6 text-xl font-semibold">Experience :</h4>

                    @if(auth()->user()->experience)
                        @foreach(json_decode(auth()->user()->experience, true)['year'] as $index => $year)
                            <div class="flex mt-6">
                                <div class="text-slate-400 font-semibold min-w-[80px] text-center">
                                    <img src="{{ asset('assets/images/company/circle-logo.png') }}"
                                         class="size-16 mx-auto mb-2 block"
                                         alt="{{ json_decode(auth()->user()->experience, true)['job_title'][$index] }}"> {{ $year }}
                                </div>

                                <div class="ms-4">
                                    @if(isset(json_decode(auth()->user()->experience, true)['job_title'][$index]))
                                        <h5 class="text-lg font-medium mb-0">{{ json_decode(auth()->user()->experience, true)['job_title'][$index] }}</h5>
                                    @endif
                                    @if(isset(json_decode(auth()->user()->experience, true)['company_name'][$index]))
                                        <span class="text-slate-400 company-university">{{ json_decode(auth()->user()->experience, true)['company_name'][$index] }}</span>
                                    @endif
                                    @if(isset(json_decode(auth()->user()->experience, true)['description'][$index]))
                                        <p class="text-slate-400 mt-2 mb-0">{{ json_decode(auth()->user()->experience, true)['description'][$index] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif


                    <div class="rounded-md shadow dark:shadow-gray-700 p-6 mt-6">

                    </div>
                </div><!--end col-->

                <div class="lg:col-span-4 md:col-span-5">
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-md shadow dark:shadow-gray-700 p-6 sticky top-20">
                        <h5 class="text-lg font-semibold">Personal Detail:</h5>
                        <ul class="list-none mt-4">
                            <li class="flex justify-between mt-3 items-center font-medium">
                                <span><i data-feather="mail" class="size-4 text-slate-400 me-3 inline"></i><span
                                            class="text-slate-400 me-3">Email  :</span></span>

                                <span>{{auth()->user()->email}}</span>
                            </li>
                            <li class="flex justify-between mt-3 items-center font-medium">
                                <span><i data-feather="gift" class="size-4 text-slate-400 me-3 inline"></i><span
                                            class="text-slate-400 me-3">D.O.B. :</span></span>

                                <span>{{Carbon\Carbon::parse(auth()->user()->dob)->format('jS M, Y')}}</span>
                            </li>
                            <li class="flex justify-between mt-3 items-center font-medium">
                                <span><i data-feather="home" class="size-4 text-slate-400 me-3 inline"></i><span
                                            class="text-slate-400 me-3">Address :</span></span>

                                <span>{{auth()->user()->address}}</span>
                            </li>
                            <li class="flex justify-between mt-3 items-center font-medium">
                                    <span><i data-feather="map-pin" class="size-4 text-slate-400 me-3 inline"></i>
                                        <span class="text-slate-400 me-3">City :</span></span>

                                <span>{{auth()->user()->state}}</span>
                            </li>
                            <li class="flex justify-between mt-3 items-center font-medium">
                                <span><i data-feather="globe" class="size-4 text-slate-400 me-3 inline"></i><span
                                            class="text-slate-400 me-3">Country :</span></span>

                                <span>{{auth()->user()->country}}</span>
                            </li>
                            <li class="flex justify-between mt-3 items-center font-medium">
                                    <span><i data-feather="server" class="size-4 text-slate-400 me-3 inline"></i>
                                        <span class="text-slate-400 me-3">Postal Code :</span></span>

                                <span>{{auth()->user()->postcode}}</span>
                            </li>
                            <li class="flex justify-between mt-3 items-center font-medium">
                                    <span><i data-feather="phone" class="size-4 text-slate-400 me-3 inline"></i>
                                        <span class="text-slate-400 me-3">Mobile :</span></span>

                                <span>{{auth()->user()->phone}}</span>
                            </li>

                            <li class="flex justify-between mt-3">
                                <span class="text-slate-400 font-medium">Social:</span>

                                <ul class="list-none text-end space-x-0.5">
                                    <li class="inline">
                                        <a href="{{auth()->user()->website}}" target="_blank"
                                           class="btn btn-icon btn-sm border-2 border-gray-200 dark:border-gray-700
                                               rounded-md hover:border-emerald-600 dark:hover:border-emerald-600
                                               hover:bg-emerald-600 dark:hover:bg-emerald-600 hover:text-white
                                               dark:text-white text-slate-400"><i class="uil uil-dribbble align-middle"
                                                                                  title="website"></i></a></li>
                                    <li class="inline">
                                        <a href="{{auth()->user()->github}}" target="_blank"
                                           class="btn btn-icon btn-sm border-2 border-gray-200 dark:border-gray-700
                                               rounded-md hover:border-emerald-600 dark:hover:border-emerald-600
                                               hover:bg-emerald-600 dark:hover:bg-emerald-600 hover:text-white
                                               dark:text-white text-slate-400"><i class="uil uil-github"
                                                                                  title="Behance"></i></a></li>
                                    <li class="inline">
                                        <a href="{{auth()->user()->linkedin}}" target="_blank"
                                           class="btn btn-icon btn-sm border-2 border-gray-200 dark:border-gray-700
                                               rounded-md hover:border-emerald-600 dark:hover:border-emerald-600
                                               hover:bg-emerald-600 dark:hover:bg-emerald-600 hover:text-white
                                               dark:text-white text-slate-400">
                                            <i class="uil uil-linkedin" title="Linkedin"></i></a></li>
                                    <li class="inline">
                                        <a href="{{auth()->user()->facebook}}" target="_blank"
                                           class="btn btn-icon btn-sm border-2 border-gray-200
                                                dark:border-gray-700 rounded-md hover:border-emerald-600
                                                dark:hover:border-emerald-600 hover:bg-emerald-600
                                                dark:hover:bg-emerald-600 hover:text-white dark:text-white
                                                text-slate-400"><i class="uil uil-facebook-f align-middle"
                                                                   title="facebook"></i></a></li>
                                    <li class="inline">
                                        <a href="{{auth()->user()->twitter}}" target="_blank"
                                           class="btn btn-icon btn-sm border-2 border-gray-200 dark:border-gray-700
                                               rounded-md hover:border-emerald-600 dark:hover:border-emerald-600
                                               hover:bg-emerald-600 dark:hover:bg-emerald-600 hover:text-white
                                               dark:text-white text-slate-400"><i class="uil uil-twitter
                                               align-middle" title="twitter"></i></a></li>
                                </ul><!--end icon-->
                            </li>
                        </ul>
                    </div>
                </div><!--end col-->
            </div><!--end grid-->
        </div><!--end container-->

        <div class="container lg:mt-24 mt-16">
            <div class="grid grid-cols-1 pb-8 text-center">
                <h3 class="mb-4 md:text-[26px] md:leading-normal text-2xl leading-normal font-semibold">Related
                    Candidates</h3>

                <p class="text-slate-400 dark:text-slate-300 max-w-xl mx-auto">Search all the open positions on the web.
                    Get your own personalized salary estimate. Read reviews on over 30000+ companies worldwide.</p>
            </div><!--end grid-->

            <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-[30px] mt-8">
                <div class="group bg-white dark:bg-slate-900 relative overflow-hidden rounded-md shadow dark:shadow-gray-700 text-center p-6">
                    <img src="assets/images/team/01.jpg"
                         class="size-20 rounded-full shadow dark:shadow-gray-700 mx-auto" alt="">

                    <div class="mt-2">
                        <a href="candidate-detail.html" class="hover:text-emerald-600 font-semibold text-lg">Steven
                            Townsend</a>
                        <p class="text-sm text-slate-400">Web Designer</p>
                    </div>

                    <ul class="mt-2 list-none">
                        <li class="inline"><span
                                    class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Design</span>
                        </li>
                        <li class="inline"><span
                                    class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">UI</span>
                        </li>
                        <li class="inline"><span
                                    class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Digital</span>
                        </li>
                    </ul>

                    <div class="flex justify-between mt-2">
                        <div class="block">
                            <span class="text-slate-400">Salery:</span>
                            <span class="block text-sm font-semibold">$4k - $4.5k</span>
                        </div>
                        <div class="block">
                            <span class="text-slate-400">Experience:</span>
                            <span class="block text-sm font-semibold">2 Years</span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="candidate-detail.html"
                           class="btn btn-sm bg-emerald-600 hover:bg-emerald-700 border-emerald-600 dark:border-emerald-600 text-white rounded-md">Profile</a>
                        <a href="#"
                           class="btn btn-sm btn-icon bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white rounded-full ms-1"><i
                                    class="uil uil-hipchat text-[20px]"></i></a>
                    </div>

                    <span class="w-24 bg-yellow-400 text-white text-center absolute ltr:-rotate-45 rtl:rotate-45 -start-[30px] top-1"><i
                                class="uil uil-star"></i></span>

                    <span class="absolute top-[10px] end-4">
                            <a href="javascript:void(0)"
                               class="text-slate-100 dark:text-slate-700 focus:text-red-600 dark:focus:text-red-600 hover:text-red-600 dark:hover:text-red-600 text-2xl"><i
                                        class="mdi mdi-heart"></i></a>
                        </span>
                </div><!--end content-->

                <div class="group bg-white dark:bg-slate-900 relative overflow-hidden rounded-md shadow dark:shadow-gray-700 text-center p-6">
                    <img src="assets/images/team/02.jpg"
                         class="size-20 rounded-full shadow dark:shadow-gray-700 mx-auto" alt="">

                    <div class="mt-2">
                        <a href="candidate-detail.html" class="hover:text-emerald-600 font-semibold text-lg">Tiffany
                            Betancourt</a>
                        <p class="text-sm text-slate-400">Web Designer</p>
                    </div>

                    <ul class="mt-2 list-none">
                        <li class="inline"><span
                                    class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Design</span>
                        </li>
                        <li class="inline"><span
                                    class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">UI</span>
                        </li>
                        <li class="inline"><span
                                    class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Digital</span>
                        </li>
                    </ul>

                    <div class="flex justify-between mt-2">
                        <div class="block">
                            <span class="text-slate-400">Salery:</span>
                            <span class="block text-sm font-semibold">$4k - $4.5k</span>
                        </div>
                        <div class="block">
                            <span class="text-slate-400">Experience:</span>
                            <span class="block text-sm font-semibold">2 Years</span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="candidate-detail.html"
                           class="btn btn-sm bg-emerald-600 hover:bg-emerald-700 border-emerald-600 dark:border-emerald-600 text-white rounded-md">Profile</a>
                        <a href="#"
                           class="btn btn-sm btn-icon bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white rounded-full ms-1"><i
                                    class="uil uil-hipchat text-[20px]"></i></a>
                    </div>

                    <span class="absolute top-[10px] end-4">
                            <a href="javascript:void(0)"
                               class="text-slate-100 dark:text-slate-700 focus:text-red-600 dark:focus:text-red-600 hover:text-red-600 dark:hover:text-red-600 text-2xl"><i
                                        class="mdi mdi-heart"></i></a>
                        </span>
                </div><!--end content-->

                <div class="group bg-white dark:bg-slate-900 relative overflow-hidden rounded-md shadow dark:shadow-gray-700 text-center p-6">
                    <img src="assets/images/team/03.jpg"
                         class="size-20 rounded-full shadow dark:shadow-gray-700 mx-auto" alt="">

                    <div class="mt-2">
                        <a href="candidate-detail.html" class="hover:text-emerald-600 font-semibold text-lg">Jacqueline
                            Burns</a>
                        <p class="text-sm text-slate-400">Web Designer</p>
                    </div>

                    <ul class="mt-2 list-none">
                        <li class="inline"><span
                                    class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Design</span>
                        </li>
                        <li class="inline"><span
                                    class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">UI</span>
                        </li>
                        <li class="inline"><span
                                    class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Digital</span>
                        </li>
                    </ul>

                    <div class="flex justify-between mt-2">
                        <div class="block">
                            <span class="text-slate-400">Salery:</span>
                            <span class="block text-sm font-semibold">$4k - $4.5k</span>
                        </div>
                        <div class="block">
                            <span class="text-slate-400">Experience:</span>
                            <span class="block text-sm font-semibold">2 Years</span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="candidate-detail.html"
                           class="btn btn-sm bg-emerald-600 hover:bg-emerald-700 border-emerald-600 dark:border-emerald-600 text-white rounded-md">Profile</a>
                        <a href="#"
                           class="btn btn-sm btn-icon bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white rounded-full ms-1"><i
                                    class="uil uil-hipchat text-[20px]"></i></a>
                    </div>

                    <span class="absolute top-[10px] end-4">
                            <a href="javascript:void(0)"
                               class="text-slate-100 dark:text-slate-700 focus:text-red-600 dark:focus:text-red-600 hover:text-red-600 dark:hover:text-red-600 text-2xl"><i
                                        class="mdi mdi-heart"></i></a>
                        </span>
                </div><!--end content-->

                <div class="group bg-white dark:bg-slate-900 relative overflow-hidden rounded-md shadow dark:shadow-gray-700 text-center p-6">
                    <img src="assets/images/team/04.jpg"
                         class="size-20 rounded-full shadow dark:shadow-gray-700 mx-auto" alt="">

                    <div class="mt-2">
                        <a href="candidate-detail.html" class="hover:text-emerald-600 font-semibold text-lg">Mari
                            Harrington</a>
                        <p class="text-sm text-slate-400">Web Designer</p>
                    </div>

                    <ul class="mt-2 list-none">
                        <li class="inline"><span
                                    class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Design</span>
                        </li>
                        <li class="inline"><span
                                    class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">UI</span>
                        </li>
                        <li class="inline"><span
                                    class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Digital</span>
                        </li>
                    </ul>

                    <div class="flex justify-between mt-2">
                        <div class="block">
                            <span class="text-slate-400">Salery:</span>
                            <span class="block text-sm font-semibold">$4k - $4.5k</span>
                        </div>
                        <div class="block">
                            <span class="text-slate-400">Experience:</span>
                            <span class="block text-sm font-semibold">2 Years</span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="candidate-detail.html"
                           class="btn btn-sm bg-emerald-600 hover:bg-emerald-700 border-emerald-600 dark:border-emerald-600 text-white rounded-md">Profile</a>
                        <a href="#"
                           class="btn btn-sm btn-icon bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white rounded-full ms-1"><i
                                    class="uil uil-hipchat text-[20px]"></i></a>
                    </div>

                    <span class="absolute top-[10px] end-4">
                            <a href="javascript:void(0)"
                               class="text-slate-100 dark:text-slate-700 focus:text-red-600 dark:focus:text-red-600 hover:text-red-600 dark:hover:text-red-600 text-2xl"><i
                                        class="mdi mdi-heart"></i></a>
                        </span>
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
                                    <h3 class="text-2xl font-semibold text-black dark:text-white mb-4">Explore a job
                                        now!</h3>
                                    <p class="text-slate-400 max-w-xl">Search all the open positions on the web. Get
                                        your own personalized salary estimate. Read reviews on over 30000+ companies
                                        worldwide.</p>
                                </div>
                            </div>

                            <div class="lg:col-span-4 md:col-span-5">
                                <div class="text-end relative z-1">
                                    <a href="employer-detail.html"
                                       class="btn bg-emerald-600 hover:bg-emerald-700 border-emerald-600 dark:border-emerald-600 text-white rounded-md">Apply
                                        Now</a>
                                    <a href="aboutus.html"
                                       class="btn bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white rounded-md ms-2">Learn
                                        More</a>
                                </div>
                            </div>
                        </div>

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
    <!-- Start -->
@endsection

