<footer class="relative bg-slate-900 dark:bg-slate-800">
    <div class="container">
        <div class="grid grid-cols-1">
            <div class="relative py-12">
                <!-- Subscribe -->
                <div class="relative w-full">
                    <div class="grid md:grid-cols-12 grid-cols-1 gap-[30px]">
                        <div class="md:col-span-3">
                            <h3 class="text-xl font-semibold text-white">Hosting Sponsor</h3>
                            <a href="https://ibb.co/6Zy2K3P"><img src="https://i.ibb.co/6Zy2K3P/Logo-Satisfy-Host3-0-copy-Logo-V-copy-3.png" alt="Logo-Satisfy-Host3-0-copy-Logo-V-copy-3" border="0" /></a>
                        </div><!--end col-->

                        <div class="md:col-span-9">
                            <ul class="list-disc footer-list md:text-end text-center space-x-3">
                                <li class="inline-block"><a href="index.html" class="text-gray-300 hover:text-gray-400 duration-500 ease-in-out font-medium">Home</a></li>
                                <li class="inline-block mt-[10px] md:mt-0"><a href="services.html" class="text-gray-300 hover:text-gray-400 duration-500 ease-in-out font-medium">How it works</a></li>
                                <li class="inline-block mt-[10px] md:mt-0"><a href="#" class="text-gray-300 hover:text-gray-400 duration-500 ease-in-out font-medium">Create a job</a></li>
                                <li class="inline-block mt-[10px] md:mt-0"><a href="aboutus.html" class="text-gray-300 hover:text-gray-400 duration-500 ease-in-out font-medium">About us</a></li>
                                <li class="inline-block mt-[10px] md:mt-0"><a href="contact.html" class="text-gray-300 hover:text-gray-400 duration-500 ease-in-out font-medium">Contact us</a></li>
                            </ul><!--end icon-->
                        </div><!--end col-->
                    </div><!--end grid-->
                </div>
                <!-- Subscribe -->
            </div>
        </div>
    </div><!--end container-->

    <div class="py-[30px] px-0 border-t border-gray-800 dark:border-gray-700">
        <div class="container text-center">
            <div class="grid md:grid-cols-2 items-center gap-6">
                <div class="md:text-start text-center">
                    <p class="mb-0 text-gray-300 font-medium">© <script>document.write(new Date().getFullYear())</script> All rights goes to Geezap</p>
                </div>

            </div><!--end grid-->
        </div><!--end container-->
    </div>
</footer><!--end footer-->
<!-- End Footer -->


<!-- Back to top -->
<a href="#" onclick="topFunction()" id="back-to-top" class="back-to-top fixed hidden text-lg rounded-full z-10 bottom-5 end-5 size-9 text-center bg-emerald-600 text-white justify-center items-center"><i class="uil uil-arrow-up"></i></a>
<!-- Back to top -->

<!-- JAVASCRIPTS -->
<script src="{{asset('assets/libs/tobii/js/tobii.min.js')}}"></script>
<script src="{{asset('assets/libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{asset('assets/libs/feather-icons/feather.min.js')}}"></script>
<script src="{{asset('assets/js/plugins.init.js')}}"></script>
<script src="{{asset('assets/js/app.js')}}"></script>
@stack('extra-js')
<!-- JAVASCRIPTS -->
</body>

</html>
