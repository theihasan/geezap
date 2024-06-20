@extends('layouts.app')
@section('main-content')
        <section class="relative lg:mt-24 mt-[74px]">
            <div class="lg:container container-fluid">
                <div class="relative shrink-0">
                    <img src="assets/images/hero/bg5.jpg" class="h-64 w-full object-cover lg:rounded-xl shadow dark:shadow-gray-700" alt="">
                </div>

                <div class="md:flex mx-4 -mt-12">
                    <div class="md:w-full">
                        <div class="relative flex items-end justify-between">
                            <div class="relative flex items-end">
                                <img src="assets/images/team/01.jpg" class="size-28 rounded-full shadow dark:shadow-gray-800 ring-4 ring-slate-50 dark:ring-slate-800" alt="">
                                <div class="ms-4">
                                    <h5 class="text-lg font-semibold">Mr. Calvin carlo</h5>
                                    <p class="text-slate-400">Web Designer</p>
                                </div>
                            </div>

                            <div class="">
                                <a href="{{route('profile.update')}}" class="btn btn-icon rounded-full bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white"><i data-feather="settings" class="size-4"></i></a>
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
                        <h5 class="text-xl font-semibold">Calvin Carlo</h5>
                        <p class="text-slate-400 mt-4">Obviously I'M Web Developer. Web Developer with over 3 years of experience. Experienced with all stages of the development cycle for dynamic web projects. The as opposed to using 'Content here, content here', making it look like readable English.</p>
                        <p class="text-slate-400 mt-4">Data Structures and Algorithms are the heart of programming. Initially most of the developers do not realize its importance but when you will start your career in software development, you will find your code is either taking too much time or taking too much space.</p>

                        <h4 class="mt-6 text-xl font-semibold">Skills :</h4>
                        <div class="grid lg:grid-cols-2 grid-cols-1 mt-6 gap-6">
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-slate-400">WordPress</span>
                                    <span class="text-slate-400">84%</span>
                                </div>
                                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-[6px]">
                                    <div class="bg-emerald-600 h-[6px] rounded-full" style="width: 84%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-slate-400">JavaScript</span>
                                    <span class="text-slate-400">79%</span>
                                </div>
                                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-[6px]">
                                    <div class="bg-emerald-600 h-[6px] rounded-full" style="width: 79%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-slate-400">HTML</span>
                                    <span class="text-slate-400">95%</span>
                                </div>
                                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-[6px]">
                                    <div class="bg-emerald-600 h-[6px] rounded-full" style="width: 95%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-slate-400">Figma</span>
                                    <span class="text-slate-400">85%</span>
                                </div>
                                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-[6px]">
                                    <div class="bg-emerald-600 h-[6px] rounded-full" style="width: 85%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-slate-400">Photoshop</span>
                                    <span class="text-slate-400">70%</span>
                                </div>
                                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-[6px]">
                                    <div class="bg-emerald-600 h-[6px] rounded-full" style="width: 70%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-slate-400">Illustration</span>
                                    <span class="text-slate-400">65%</span>
                                </div>
                                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-[6px]">
                                    <div class="bg-emerald-600 h-[6px] rounded-full" style="width: 65%"></div>
                                </div>
                            </div>
                        </div>

                        <h4 class="mt-6 text-xl font-semibold">Experience :</h4>

                        <div class="flex mt-6">
                            <div class="text-slate-400 font-semibold min-w-[80px] text-center">
                                <img src="assets/images/company/shree-logo.png" class="size-16 mx-auto mb-2 block" alt=""> 2019-22
                            </div>

                            <div class="ms-4">
                                <h5 class="text-lg font-medium mb-0">Full Stack Developer</h5>
                                <span class="text-slate-400 company-university">Shreethemes - India</span>
                                <p class="text-slate-400 mt-2 mb-0">It seems that only fragments of the original text remain in the Lorem Ipsum texts used today. One may speculate that over the course of time certain letters were added or deleted at various positions within the text. </p>
                            </div>
                        </div>

                        <div class="flex mt-6">
                            <div class="text-slate-400 font-semibold min-w-[80px] text-center">
                                <img src="assets/images/company/circle-logo.png" class="size-16 mx-auto mb-2 block" alt=""> 2017-19
                            </div>

                            <div class="ms-4">
                                <h5 class="text-lg font-medium mb-0">Back-end Developer</h5>
                                <span class="text-slate-400 company-university">CircleCI - U.S.A.</span>
                                <p class="text-slate-400 mt-2 mb-0">It seems that only fragments of the original text remain in the Lorem Ipsum texts used today. One may speculate that over the course of time certain letters were added or deleted at various positions within the text. </p>
                            </div>
                        </div>

                        <div class="rounded-md shadow dark:shadow-gray-700 p-6 mt-6">
                            <h5 class="text-xl font-semibold">Contact Me:</h5>

                            <form method="post" name="myForm" id="myForm" class="mt-4" onsubmit="return validateForm()">
                                <p class="mb-0" id="error-msg"></p>
                                <div id="simple-msg"></div>
                                <div class="grid grid-cols-12 gap-2">
                                    <div class="md:col-span-6 col-span-12">
                                        <label for="name" class="font-semibold">Your Name:</label>
                                        <input name="name" id="name" type="text" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="Name :">
                                    </div>

                                    <div class="md:col-span-6 col-span-12">
                                        <label for="email" class="font-semibold">Your Email:</label>
                                        <input name="email" id="email" type="email" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="Email :">
                                    </div>

                                    <div class="col-span-12">
                                        <label for="subject" class="font-semibold">Your Question:</label>
                                        <input name="subject" id="subject" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="Subject :">
                                    </div>

                                    <div class="col-span-12">
                                        <label for="comments" class="font-semibold">Your Comment:</label>
                                        <textarea name="comments" id="comments" class="form-input border border-slate-100 dark:border-slate-800 mt-2 textarea" placeholder="Message :"></textarea>
                                    </div>
                                </div>
                                <button type="submit" id="submit" name="send" class="btn bg-emerald-600 hover:bg-emerald-700 text-white rounded-md">Send Message</button>
                            </form>
                        </div>
                    </div><!--end col-->

                    <div class="lg:col-span-4 md:col-span-5">
                        <div class="bg-slate-50 dark:bg-slate-800 rounded-md shadow dark:shadow-gray-700 p-6 sticky top-20">
                            <h5 class="text-lg font-semibold">Personal Detail:</h5>
                            <ul class="list-none mt-4">
                                <li class="flex justify-between mt-3 items-center font-medium">
                                    <span><i data-feather="mail" class="size-4 text-slate-400 me-3 inline"></i><span class="text-slate-400 me-3">Email  :</span></span>

                                    <span>thomas@mail.com</span>
                                </li>
                                <li class="flex justify-between mt-3 items-center font-medium">
                                    <span><i data-feather="gift" class="size-4 text-slate-400 me-3 inline"></i><span class="text-slate-400 me-3">D.O.B. :</span></span>

                                    <span>31st Dec, 1996</span>
                                </li>
                                <li class="flex justify-between mt-3 items-center font-medium">
                                    <span><i data-feather="home" class="size-4 text-slate-400 me-3 inline"></i><span class="text-slate-400 me-3">Address :</span></span>

                                    <span>15 Razy street</span>
                                </li>
                                <li class="flex justify-between mt-3 items-center font-medium">
                                    <span><i data-feather="map-pin" class="size-4 text-slate-400 me-3 inline"></i><span class="text-slate-400 me-3">City :</span></span>

                                    <span>London</span>
                                </li>
                                <li class="flex justify-between mt-3 items-center font-medium">
                                    <span><i data-feather="globe" class="size-4 text-slate-400 me-3 inline"></i><span class="text-slate-400 me-3">Country :</span></span>

                                    <span>UK</span>
                                </li>
                                <li class="flex justify-between mt-3 items-center font-medium">
                                    <span><i data-feather="server" class="size-4 text-slate-400 me-3 inline"></i><span class="text-slate-400 me-3">Postal Code :</span></span>

                                    <span>521452</span>
                                </li>
                                <li class="flex justify-between mt-3 items-center font-medium">
                                    <span><i data-feather="phone" class="size-4 text-slate-400 me-3 inline"></i><span class="text-slate-400 me-3">Mobile :</span></span>

                                    <span>(+125) 1542-8452</span>
                                </li>

                                <li class="flex justify-between mt-3">
                                    <span class="text-slate-400 font-medium">Social:</span>

                                    <ul class="list-none text-end space-x-0.5">
                                        <li class="inline"><a href="https://dribbble.com/shreethemes" target="_blank" class="btn btn-icon btn-sm border-2 border-gray-200 dark:border-gray-700 rounded-md hover:border-emerald-600 dark:hover:border-emerald-600 hover:bg-emerald-600 dark:hover:bg-emerald-600 hover:text-white dark:text-white text-slate-400"><i class="uil uil-dribbble align-middle" title="dribbble"></i></a></li>
                                        <li class="inline"><a href="https://www.behance.net/shreethemes" target="_blank" class="btn btn-icon btn-sm border-2 border-gray-200 dark:border-gray-700 rounded-md hover:border-emerald-600 dark:hover:border-emerald-600 hover:bg-emerald-600 dark:hover:bg-emerald-600 hover:text-white dark:text-white text-slate-400"><i class="uil uil-behance" title="Behance"></i></a></li>
                                        <li class="inline"><a href="http://linkedin.com/company/shreethemes" target="_blank" class="btn btn-icon btn-sm border-2 border-gray-200 dark:border-gray-700 rounded-md hover:border-emerald-600 dark:hover:border-emerald-600 hover:bg-emerald-600 dark:hover:bg-emerald-600 hover:text-white dark:text-white text-slate-400"><i class="uil uil-linkedin" title="Linkedin"></i></a></li>
                                        <li class="inline"><a href="https://www.facebook.com/shreethemes" target="_blank" class="btn btn-icon btn-sm border-2 border-gray-200 dark:border-gray-700 rounded-md hover:border-emerald-600 dark:hover:border-emerald-600 hover:bg-emerald-600 dark:hover:bg-emerald-600 hover:text-white dark:text-white text-slate-400"><i class="uil uil-facebook-f align-middle" title="facebook"></i></a></li>
                                        <li class="inline"><a href="https://www.instagram.com/shreethemes/" target="_blank" class="btn btn-icon btn-sm border-2 border-gray-200 dark:border-gray-700 rounded-md hover:border-emerald-600 dark:hover:border-emerald-600 hover:bg-emerald-600 dark:hover:bg-emerald-600 hover:text-white dark:text-white text-slate-400"><i class="uil uil-instagram align-middle" title="instagram"></i></a></li>
                                        <li class="inline"><a href="https://twitter.com/shreethemes" target="_blank" class="btn btn-icon btn-sm border-2 border-gray-200 dark:border-gray-700 rounded-md hover:border-emerald-600 dark:hover:border-emerald-600 hover:bg-emerald-600 dark:hover:bg-emerald-600 hover:text-white dark:text-white text-slate-400"><i class="uil uil-twitter align-middle" title="twitter"></i></a></li>
                                        <li class="inline"><a href="mailto:support@shreethemes.in" class="btn btn-icon btn-sm border-2 border-gray-200 dark:border-gray-700 rounded-md hover:border-emerald-600 dark:hover:border-emerald-600 hover:bg-emerald-600 dark:hover:bg-emerald-600 hover:text-white dark:text-white text-slate-400"><i class="uil uil-envelope align-middle" title="email"></i></a></li>
                                    </ul><!--end icon-->
                                </li>

                                <li class="mt-3 w-full bg-white p-3 rounded-md shadow dark:shadow-gray-700">
                                    <div class="flex items-center mb-3">
                                        <i data-feather="file-text" class="size-8 text-slate-400"></i>
                                        <span class="font-medium ms-2">calvin-carlo-resume.pdf</span>
                                    </div>

                                    <a href="assets/images/calvin-carlo-resume.pdf" class="btn bg-emerald-600 hover:bg-emerald-700 border-emerald-600 dark:border-emerald-600 text-white rounded-md w-full" download><i class="uil uil-file-download-alt"></i> Download CV</a>
                                </li>
                            </ul>
                        </div>
                    </div><!--end col-->
                </div><!--end grid-->
            </div><!--end container-->

            <div class="container lg:mt-24 mt-16">
                <div class="grid grid-cols-1 pb-8 text-center">
                    <h3 class="mb-4 md:text-[26px] md:leading-normal text-2xl leading-normal font-semibold">Related Candidates</h3>

                    <p class="text-slate-400 dark:text-slate-300 max-w-xl mx-auto">Search all the open positions on the web. Get your own personalized salary estimate. Read reviews on over 30000+ companies worldwide.</p>
                </div><!--end grid-->

                <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-[30px] mt-8">
                    <div class="group bg-white dark:bg-slate-900 relative overflow-hidden rounded-md shadow dark:shadow-gray-700 text-center p-6">
                        <img src="assets/images/team/01.jpg" class="size-20 rounded-full shadow dark:shadow-gray-700 mx-auto" alt="">

                        <div class="mt-2">
                            <a href="candidate-detail.html" class="hover:text-emerald-600 font-semibold text-lg">Steven Townsend</a>
                            <p class="text-sm text-slate-400">Web Designer</p>
                        </div>

                        <ul class="mt-2 list-none">
                            <li class="inline"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Design</span></li>
                            <li class="inline"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">UI</span></li>
                            <li class="inline"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Digital</span></li>
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
                            <a href="candidate-detail.html" class="btn btn-sm bg-emerald-600 hover:bg-emerald-700 border-emerald-600 dark:border-emerald-600 text-white rounded-md">Profile</a>
                            <a href="#" class="btn btn-sm btn-icon bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white rounded-full ms-1"><i class="uil uil-hipchat text-[20px]"></i></a>
                        </div>

                        <span class="w-24 bg-yellow-400 text-white text-center absolute ltr:-rotate-45 rtl:rotate-45 -start-[30px] top-1"><i class="uil uil-star"></i></span>

                        <span class="absolute top-[10px] end-4">
                            <a href="javascript:void(0)" class="text-slate-100 dark:text-slate-700 focus:text-red-600 dark:focus:text-red-600 hover:text-red-600 dark:hover:text-red-600 text-2xl"><i class="mdi mdi-heart"></i></a>
                        </span>
                    </div><!--end content-->

                    <div class="group bg-white dark:bg-slate-900 relative overflow-hidden rounded-md shadow dark:shadow-gray-700 text-center p-6">
                        <img src="assets/images/team/02.jpg" class="size-20 rounded-full shadow dark:shadow-gray-700 mx-auto" alt="">

                        <div class="mt-2">
                            <a href="candidate-detail.html" class="hover:text-emerald-600 font-semibold text-lg">Tiffany Betancourt</a>
                            <p class="text-sm text-slate-400">Web Designer</p>
                        </div>

                        <ul class="mt-2 list-none">
                            <li class="inline"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Design</span></li>
                            <li class="inline"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">UI</span></li>
                            <li class="inline"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Digital</span></li>
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
                            <a href="candidate-detail.html" class="btn btn-sm bg-emerald-600 hover:bg-emerald-700 border-emerald-600 dark:border-emerald-600 text-white rounded-md">Profile</a>
                            <a href="#" class="btn btn-sm btn-icon bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white rounded-full ms-1"><i class="uil uil-hipchat text-[20px]"></i></a>
                        </div>

                        <span class="absolute top-[10px] end-4">
                            <a href="javascript:void(0)" class="text-slate-100 dark:text-slate-700 focus:text-red-600 dark:focus:text-red-600 hover:text-red-600 dark:hover:text-red-600 text-2xl"><i class="mdi mdi-heart"></i></a>
                        </span>
                    </div><!--end content-->

                    <div class="group bg-white dark:bg-slate-900 relative overflow-hidden rounded-md shadow dark:shadow-gray-700 text-center p-6">
                        <img src="assets/images/team/03.jpg" class="size-20 rounded-full shadow dark:shadow-gray-700 mx-auto" alt="">

                        <div class="mt-2">
                            <a href="candidate-detail.html" class="hover:text-emerald-600 font-semibold text-lg">Jacqueline Burns</a>
                            <p class="text-sm text-slate-400">Web Designer</p>
                        </div>

                        <ul class="mt-2 list-none">
                            <li class="inline"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Design</span></li>
                            <li class="inline"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">UI</span></li>
                            <li class="inline"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Digital</span></li>
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
                            <a href="candidate-detail.html" class="btn btn-sm bg-emerald-600 hover:bg-emerald-700 border-emerald-600 dark:border-emerald-600 text-white rounded-md">Profile</a>
                            <a href="#" class="btn btn-sm btn-icon bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white rounded-full ms-1"><i class="uil uil-hipchat text-[20px]"></i></a>
                        </div>

                        <span class="absolute top-[10px] end-4">
                            <a href="javascript:void(0)" class="text-slate-100 dark:text-slate-700 focus:text-red-600 dark:focus:text-red-600 hover:text-red-600 dark:hover:text-red-600 text-2xl"><i class="mdi mdi-heart"></i></a>
                        </span>
                    </div><!--end content-->

                    <div class="group bg-white dark:bg-slate-900 relative overflow-hidden rounded-md shadow dark:shadow-gray-700 text-center p-6">
                        <img src="assets/images/team/04.jpg" class="size-20 rounded-full shadow dark:shadow-gray-700 mx-auto" alt="">

                        <div class="mt-2">
                            <a href="candidate-detail.html" class="hover:text-emerald-600 font-semibold text-lg">Mari Harrington</a>
                            <p class="text-sm text-slate-400">Web Designer</p>
                        </div>

                        <ul class="mt-2 list-none">
                            <li class="inline"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Design</span></li>
                            <li class="inline"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">UI</span></li>
                            <li class="inline"><span class="bg-emerald-600/10 inline-block text-emerald-600 text-xs px-2.5 py-0.5 font-semibold rounded-full">Digital</span></li>
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
                            <a href="candidate-detail.html" class="btn btn-sm bg-emerald-600 hover:bg-emerald-700 border-emerald-600 dark:border-emerald-600 text-white rounded-md">Profile</a>
                            <a href="#" class="btn btn-sm btn-icon bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white rounded-full ms-1"><i class="uil uil-hipchat text-[20px]"></i></a>
                        </div>

                        <span class="absolute top-[10px] end-4">
                            <a href="javascript:void(0)" class="text-slate-100 dark:text-slate-700 focus:text-red-600 dark:focus:text-red-600 hover:text-red-600 dark:hover:text-red-600 text-2xl"><i class="mdi mdi-heart"></i></a>
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
                                        <h3 class="text-2xl font-semibold text-black dark:text-white mb-4">Explore a job now!</h3>
                                        <p class="text-slate-400 max-w-xl">Search all the open positions on the web. Get your own personalized salary estimate. Read reviews on over 30000+ companies worldwide.</p>
                                    </div>
                                </div>

                                <div class="lg:col-span-4 md:col-span-5">
                                    <div class="text-end relative z-1">
                                        <a href="employer-detail.html" class="btn bg-emerald-600 hover:bg-emerald-700 border-emerald-600 dark:border-emerald-600 text-white rounded-md">Apply Now</a>
                                        <a href="aboutus.html" class="btn bg-emerald-600/5 hover:bg-emerald-600 border-emerald-600/10 hover:border-emerald-600 text-emerald-600 hover:text-white rounded-md ms-2">Learn More</a>
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

