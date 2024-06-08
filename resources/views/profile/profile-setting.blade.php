@extends('layouts.app')
@section('main-content')
        <section class="relative lg:mt-24 mt-[74px] pb-16">
            <div class="lg:container container-fluid">
                <div class="profile-banner relative text-transparent">
                    <input id="pro-banner" name="profile-banner" type="file" class="hidden" onchange="loadFile(event)" />
                    <div class="relative shrink-0">
                        <img src="assets/images/hero/bg5.jpg" class="h-64 w-full object-cover lg:rounded-xl shadow dark:shadow-gray-700" id="profile-banner" alt="">
                        <label class="absolute inset-0 cursor-pointer" for="pro-banner"></label>
                    </div>
                </div>

                <div class="md:flex mx-4 -mt-12">
                    <div class="md:w-full">
                        <div class="relative flex items-end">
                            <div class="profile-pic text-center">
                                <input id="pro-img" name="profile-image" type="file" class="hidden" onchange="loadFile(event)" />
                                <div>
                                    <div class="relative size-28 max-w-[112px] max-h-[112px] mx-auto">
                                        <img src="assets/images/team/01.jpg" class="rounded-full shadow dark:shadow-gray-800 ring-4 ring-slate-50 dark:ring-slate-800" id="profile-image" alt="">
                                        <label class="absolute inset-0 cursor-pointer" for="pro-img"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="ms-4">
                                <h5 class="text-lg font-semibold">Mr. Calvin carlo</h5>
                                <p class="text-slate-400">Web Designer</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end -->

            <div class="container mt-16">
                <div class="grid lg:grid-cols-12 grid-cols-1 gap-[30px]">
                    <div class="lg:col-span-12">
                        <div class="p-6 rounded-md shadow dark:shadow-gray-800 bg-white dark:bg-slate-900">
                            <h5 class="text-lg font-semibold mb-4">Personal Detail :</h5>
                            <form>
                                <div class="grid lg:grid-cols-12 md:grid-cols-2 grid-cols-1 gap-4">
                                    <div class="lg:col-span-6">
                                        <label class="form-label font-medium">First Name : <span class="text-red-600">*</span></label>
                                        <input type="text" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="First Name:" id="firstname" name="name" required="">
                                    </div>

                                    <div class="lg:col-span-6">
                                        <label class="form-label font-medium">Last Name : <span class="text-red-600">*</span></label>
                                        <input type="text" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="Last Name:" id="lastname" name="name" required="">
                                    </div>

                                    <div class="lg:col-span-6">
                                        <label class="form-label font-medium">Your Email : <span class="text-red-600">*</span></label>
                                        <input type="email" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="Email" name="email" required="">
                                    </div>

                                    <div class="lg:col-span-6">
                                        <label class="form-label font-medium" for="birthday">Date of Birth :</label>
                                        <input type="date" id="birthday" name="birthday" class="form-input border border-slate-100 dark:border-slate-800 mt-2">
                                    </div>

                                    <div class="lg:col-span-4">
                                        <label class="form-label font-medium">Your Address :</label>
                                        <input type="address" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="Address" name="address" required="">
                                    </div>

                                    <div class="lg:col-span-3">
                                        <label class="form-label font-medium">State :</label>
                                        <select class="form-select form-input border border-slate-100 dark:border-slate-800 block w-full mt-2">
                                            <option value="NY">New York</option>
                                            <option value="MC">North Carolina</option>
                                            <option value="SC">South Carolina</option>
                                        </select>
                                    </div>

                                    <div class="lg:col-span-3">
                                        <label class="form-label font-medium">Country :</label>
                                        <select class="form-select form-input border border-slate-100 dark:border-slate-800 block w-full mt-2">
                                            <option value="NY">USA</option>
                                            <option value="MC">UK</option>
                                            <option value="SC">India</option>
                                        </select>
                                    </div>

                                    <div class="lg:col-span-2">
                                        <label class="form-label font-medium">Postal Code :</label>
                                        <input type="number" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="Postal Code" name="number" required="">
                                    </div>

                                    <div class="lg:col-span-6">
                                        <label class="form-label font-medium">Mobile No. :</label>
                                        <input type="number" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="Mobile No." name="number" required="">
                                    </div>

                                    <div class="lg:col-span-6">
                                        <label class="form-label font-medium">Occupation :</label>
                                        <select class="form-select form-input border border-slate-100 dark:border-slate-800 block w-full mt-2">
                                            <option value="WD">Web Designer</option>
                                            <option value="WD">Web Developer</option>
                                            <option value="UI">UI / UX Desinger</option>
                                        </select>
                                    </div>

                                    <div class="lg:col-span-6">
                                        <label class="form-label font-medium" for="multiple_files">Upload Resume:</label>
                                        <input class="relative form-input border border-slate-100 dark:border-slate-800 file:h-10 file:-mx-3 file:-my-2 file:cursor-pointer file:rounded-none file:border-0 file:px-3 file:text-neutral-700 bg-clip-padding px-3 py-1.5 file:me-3 mt-2" id="multiple_files" type="file" multiple>
                                    </div>
                                </div><!--end grid-->

                                <div class="grid grid-cols-1">
                                    <div class="mt-5">
                                        <label class="form-label font-medium">Intro : </label>
                                        <textarea name="comments" id="comments" class="form-input border border-slate-100 dark:border-slate-800 mt-2 textarea" placeholder="Intro :"></textarea>
                                    </div>
                                </div><!--end row-->

                                <input type="submit" id="submit" name="send" class="btn bg-emerald-600 hover:bg-emerald-700 text-white rounded-md mt-5" value="Save Changes">
                            </form><!--end form-->
                        </div>
                    </div>

                    <div class="lg:col-span-6">
                        <div class="p-6 rounded-md shadow dark:shadow-gray-800 bg-white dark:bg-slate-900">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <h5 class="text-lg font-semibold mb-4">Skills :</h5>
                                    <form>
                                        <div class="grid grid-cols-1 gap-4">
                                            <div class="">
                                                <label class="form-label font-medium" for="WordPress">WordPress</label>
                                                <input type="number" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="First Name:" id="WordPress" name="number" required="">
                                            </div>

                                            <div class="">
                                                <label class="form-label font-medium" for="JavaScript">JavaScript</label>
                                                <input type="number" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="First Name:" id="JavaScript" name="number" required="">
                                            </div>

                                            <div class="">
                                                <label class="form-label font-medium" for="HTML">HTML</label>
                                                <input type="number" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="First Name:" id="HTML" name="number" required="">
                                            </div>

                                            <div class="">
                                                <label class="form-label font-medium" for="Figma">Figma</label>
                                                <input type="number" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="First Name:" id="Figma" name="number" required="">
                                            </div>

                                            <div class="">
                                                <label class="form-label font-medium" for="Photoshop">Photoshop</label>
                                                <input type="number" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="First Name:" id="Photoshop" name="number" required="">
                                            </div>

                                            <div class="">
                                                <label class="form-label font-medium" for="Illustration">Illustration</label>
                                                <input type="number" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="First Name:" id="Illustration" name="number" required="">
                                            </div>
                                        </div>

                                        <input type="submit" id="submit" name="send" class="btn bg-emerald-600 hover:bg-emerald-700 text-white rounded-md mt-5" value="Save Changes">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-6">
                        <div class="p-6 rounded-md shadow dark:shadow-gray-800 bg-white dark:bg-slate-900">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <h5 class="text-lg font-semibold mb-4">Experience :</h5>

                                    <div>
                                        <div>
                                            <div class="preview-box flex justify-center rounded-md shadow dark:shadow-gray-800 overflow-hidden bg-gray-50 dark:bg-slate-800 text-slate-400 p-2 text-center small">Supports JPG, PNG and MP4 videos. Max file size : 10MB.</div>
                                            <input type="file" id="input-file" name="input-file" accept="image/*" onchange={handleChange()} hidden>
                                            <label class="btn-upload btn bg-emerald-600 hover:bg-emerald-700 text-white rounded-md mt-5 cursor-pointer" for="input-file">Upload Image</label>
                                        </div>
                                        <form>
                                            <div class="grid grid-cols-12 mt-6 gap-4">
                                                <div class="col-span-12">
                                                    <label class="form-label font-medium">Job Title <span class="text-red-600">*</span></label>
                                                    <input name="name" id="JobTitle" type="text" class="form-input border border-slate-100 dark:border-slate-800" placeholder="Title :">
                                                </div><!--end col-->

                                                <div class="col-span-12">
                                                    <label class="form-label font-medium">Company Name <span class="text-red-600">*</span></label>
                                                    <input name="name" id="CompanyName" type="text" class="form-input border border-slate-100 dark:border-slate-800" placeholder="Company :">
                                                </div><!--end col-->

                                                <div class="col-span-12">
                                                    <label class="form-label font-medium">Year <span class="text-red-600">*</span></label>
                                                    <input name="number" id="Year" type="number" class="form-input border border-slate-100 dark:border-slate-800" placeholder="Year :">
                                                </div><!--end col-->

                                                <div class="col-span-12">
                                                    <label class="form-label font-medium"> Description : </label>
                                                    <textarea name="comments" id="Description" class="form-input border border-slate-100 dark:border-slate-800 textarea" placeholder="Description :"></textarea>
                                                </div><!--end col-->
                                            </div>
                                        </form>

                                        <input type="submit" id="submit" name="send" class="btn bg-emerald-600 hover:bg-emerald-700 text-white rounded-md mt-5" value="Save Changes">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-12">
                        <div class="p-6 rounded-md shadow dark:shadow-gray-800 bg-white dark:bg-slate-900">
                            <div class="grid lg:grid-cols-2 grid-cols-1 gap-4">
                                <div>
                                    <h5 class="text-lg font-semibold mb-4">Contact Info :</h5>

                                    <form>
                                        <div class="grid grid-cols-1 gap-4">
                                            <div>
                                                <label class="form-label font-medium">Phone No. :</label>
                                                <input name="number" id="number" type="number" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="Phone :">
                                            </div>

                                            <div>
                                                <label class="form-label font-medium">Website :</label>
                                                <input name="url" id="url" type="url" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="Url :">
                                            </div>
                                        </div><!--end grid-->

                                        <button class="btn bg-emerald-600 hover:bg-emerald-700 text-white rounded-md mt-5">Add</button>
                                    </form>
                                </div><!--end col-->

                                <div>
                                    <h5 class="text-lg font-semibold mb-4">Change password :</h5>
                                    <form>
                                        <div class="grid grid-cols-1 gap-4">
                                            <div>
                                                <label class="form-label font-medium">Old password :</label>
                                                <input type="password" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="Old password" required="">
                                            </div>

                                            <div>
                                                <label class="form-label font-medium">New password :</label>
                                                <input type="password" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="New password" required="">
                                            </div>

                                            <div>
                                                <label class="form-label font-medium">Re-type New password :</label>
                                                <input type="password" class="form-input border border-slate-100 dark:border-slate-800 mt-2" placeholder="Re-type New password" required="">
                                            </div>
                                        </div><!--end grid-->

                                        <button class="btn bg-emerald-600 hover:bg-emerald-700 text-white rounded-md mt-5">Save password</button>
                                    </form>
                                </div><!--end col-->
                            </div><!--end row-->
                        </div>
                    </div>

                    <div class="lg:col-span-12">
                        <div class="p-6 rounded-md shadow dark:shadow-gray-800 bg-white dark:bg-slate-900">
                            <h5 class="text-lg font-semibold mb-4">Social Media :</h5>

                            <div class="md:flex">
                                <div class="md:w-1/3">
                                    <span class="font-medium">Twitter</span>
                                </div>

                                <div class="md:w-2/3 mt-4 md:mt-0">
                                    <form>
                                        <div class="form-icon relative">
                                            <i data-feather="twitter" class="size-4 absolute top-5 start-4"></i>
                                            <input type="text" class="form-input border border-slate-100 dark:border-slate-800 mt-2 ps-12" placeholder="Twitter Profile Name" id="twitter_name" name="name" required="">
                                        </div>
                                    </form>

                                    <p class="text-slate-400 mt-1">Add your Twitter username (e.g. jennyhot).</p>
                                </div>
                            </div>

                            <div class="md:flex mt-8">
                                <div class="md:w-1/3">
                                    <span class="font-medium">Facebook</span>
                                </div>

                                <div class="md:w-2/3 mt-4 md:mt-0">
                                    <form>
                                        <div class="form-icon relative">
                                            <i data-feather="facebook" class="size-4 absolute top-5 start-4"></i>
                                            <input type="text" class="form-input border border-slate-100 dark:border-slate-800 mt-2 ps-12" placeholder="Facebook Profile Name" id="facebook_name" name="name" required="">
                                        </div>
                                    </form>

                                    <p class="text-slate-400 mt-1">Add your Facebook username (e.g. jennyhot).</p>
                                </div>
                            </div>

                            <div class="md:flex mt-8">
                                <div class="md:w-1/3">
                                    <span class="font-medium">Instagram</span>
                                </div>

                                <div class="md:w-2/3 mt-4 md:mt-0">
                                    <form>
                                        <div class="form-icon relative">
                                            <i data-feather="instagram" class="size-4 absolute top-5 start-4"></i>
                                            <input type="text" class="form-input border border-slate-100 dark:border-slate-800 mt-2 ps-12" placeholder="Instagram Profile Name" id="insta_name" name="name" required="">
                                        </div>
                                    </form>

                                    <p class="text-slate-400 mt-1">Add your Instagram username (e.g. jennyhot).</p>
                                </div>
                            </div>

                            <div class="md:flex mt-8">
                                <div class="md:w-1/3">
                                    <span class="font-medium">Linkedin</span>
                                </div>

                                <div class="md:w-2/3 mt-4 md:mt-0">
                                    <form>
                                        <div class="form-icon relative">
                                            <i data-feather="linkedin" class="size-4 absolute top-5 start-4"></i>
                                            <input type="text" class="form-input border border-slate-100 dark:border-slate-800 mt-2 ps-12" placeholder="Linkedin Profile Name" id="linkedin_name" name="name" required="">
                                        </div>
                                    </form>

                                    <p class="text-slate-400 mt-1">Add your Linkedin username.</p>
                                </div>
                            </div>

                            <div class="md:flex mt-8">
                                <div class="md:w-1/3">
                                    <span class="font-medium">Youtube</span>
                                </div>

                                <div class="md:w-2/3 mt-4 md:mt-0">
                                    <form>
                                        <div class="form-icon relative">
                                            <i data-feather="youtube" class="size-4 absolute top-5 start-4"></i>
                                            <input type="url" class="form-input border border-slate-100 dark:border-slate-800 mt-2 ps-12" placeholder="Youtube url" id="you_url" name="url" required="">
                                        </div>
                                    </form>

                                    <p class="text-slate-400 mt-1">Add your Youtube url.</p>
                                </div>
                            </div>

                            <div class="md:flex">
                                <div class="md:w-1/3">
                                    <input type="submit" id="submit" name="send" class="btn bg-emerald-600 hover:bg-emerald-700 text-white rounded-md mt-5" value="Save Changes">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-12">
                        <div class="p-6 rounded-md shadow dark:shadow-gray-800 bg-white dark:bg-slate-900">
                            <h5 class="text-lg font-semibold mb-5">Account Notifications :</h5>

                            <div class="flex justify-between pb-4">
                                <h6 class="mb-0 font-medium">When someone mentions me</h6>
                                <div class="">
                                    <input class="form-checkbox rounded border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50" type="checkbox" value="" id="noti1">
                                    <label class="form-check-label" for="noti1"></label>
                                </div>
                            </div>
                            <div class="flex justify-between py-4 border-t border-gray-100 dark:border-gray-700">
                                <h6 class="mb-0 font-medium">When someone follows me</h6>
                                <div class="">
                                    <input class="form-checkbox rounded border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50" type="checkbox" value="" checked id="noti2">
                                    <label class="form-check-label" for="noti2"></label>
                                </div>
                            </div>
                            <div class="flex justify-between py-4 border-t border-gray-100 dark:border-gray-700">
                                <h6 class="mb-0 font-medium">When shares my activity</h6>
                                <div class="">
                                    <input class="form-checkbox rounded border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50" type="checkbox" value="" id="noti3">
                                    <label class="form-check-label" for="noti3"></label>
                                </div>
                            </div>
                            <div class="flex justify-between py-4 border-t border-gray-100 dark:border-gray-700">
                                <h6 class="mb-0 font-medium">When someone messages me</h6>
                                <div class="">
                                    <input class="form-checkbox rounded border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50" type="checkbox" value="" id="noti4">
                                    <label class="form-check-label" for="noti4"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-12">
                        <div class="p-6 rounded-md shadow dark:shadow-gray-800 bg-white dark:bg-slate-900">
                            <h5 class="text-lg font-semibold mb-5">Marketing Notifications :</h5>

                            <div class="flex justify-between pb-4">
                                <h6 class="mb-0 font-medium">There is a sale or promotion</h6>
                                <div class="">
                                    <input class="form-checkbox rounded border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50" type="checkbox" value="" id="noti5">
                                    <label class="form-check-label" for="noti5"></label>
                                </div>
                            </div>
                            <div class="flex justify-between py-4 border-t border-gray-100 dark:border-gray-700">
                                <h6 class="mb-0 font-medium">Company news</h6>
                                <div class="">
                                    <input class="form-checkbox rounded border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50" type="checkbox" value="" id="noti6">
                                    <label class="form-check-label" for="noti6"></label>
                                </div>
                            </div>
                            <div class="flex justify-between py-4 border-t border-gray-100 dark:border-gray-700">
                                <h6 class="mb-0 font-medium">Weekly jobs</h6>
                                <div class="">
                                    <input class="form-checkbox rounded border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50" type="checkbox" value="" checked id="noti7">
                                    <label class="form-check-label" for="noti7"></label>
                                </div>
                            </div>
                            <div class="flex justify-between py-4 border-t border-gray-100 dark:border-gray-700">
                                <h6 class="mb-0 font-medium">Unsubscribe News</h6>
                                <div class="">
                                    <input class="form-checkbox rounded border-gray-200 dark:border-gray-800 text-emerald-600 focus:border-emerald-300 focus:ring focus:ring-offset-0 focus:ring-emerald-200 focus:ring-opacity-50" type="checkbox" value="" checked id="noti8">
                                    <label class="form-check-label" for="noti8"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-12">
                        <div class="p-6 rounded-md shadow dark:shadow-gray-800 bg-white dark:bg-slate-900">
                            <h5 class="text-lg font-semibold mb-5 text-red-600">Delete Account :</h5>

                            <p class="text-slate-400 mb-4">Do you want to delete the account? Please press below "Delete" button</p>

                            <a href="#" class="btn bg-red-600 hover:bg-red-700 text-white rounded-md">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Hero -->
@endsection
@push('extra-js')


        <script>
            const handleChange = () => {
            const fileUploader = document.querySelector('#input-file');
                const getFile = fileUploader.files
                if (getFile.length !== 0) {
                    const uploadedFile = getFile[0];
                    readFile(uploadedFile);
                }
            }

            const readFile = (uploadedFile) => {
                if (uploadedFile) {
                    const reader = new FileReader();
                    reader.onload = () => {
                    const parent = document.querySelector('.preview-box');
                    parent.innerHTML = `<img class="preview-content" src=${reader.result} />`;
                    };

                    reader.readAsDataURL(uploadedFile);
                }
            };
        </script>
        <!-- JAVASCRIPTS -->
@endpush
