@extends('layouts.app')
@section('main-content')
    <section class="relative lg:mt-24 mt-[74px] pb-16">
        <div class="lg:container container-fluid">
            <div class="profile-banner relative text-transparent">
                <input id="pro-banner" name="profile-banner" type="file" class="hidden" onchange="loadFile(event)"/>
                <div class="relative shrink-0">
                    <img src="{{asset('assets/images/hero/bg5.jpg')}}"
                         class="h-64 w-full object-cover lg:rounded-xl shadow dark:shadow-gray-700" id="profile-banner"
                         alt="">
                    <label class="absolute inset-0 cursor-pointer" for="pro-banner"></label>
                </div>
            </div>

            <div class="md:flex mx-4 -mt-12">
                <div class="md:w-full">
                    <div class="relative flex items-end justify-between mt-4">
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
                            <a href="{{route('dashboard')}}"
                               class="btn btn-icon rounded-full bg-emerald-600/5 hover:bg-emerald-600
                                    border-emerald-600/10 hover:border-emerald-600 text-emerald-600
                                    hover:text-white"><i data-feather="user" class="size-4"></i></a>
                        </div>
                    </div>
                </div>
            </div>


            <div class="container mt-16">
                <div class="grid lg:grid-cols-12 grid-cols-1 gap-[30px]">
                    <div class="lg:col-span-12">
                        <div class="p-6 rounded-md shadow dark:shadow-gray-800 bg-white dark:bg-slate-900">
                            <h5 class="text-lg font-semibold mb-4">Personal Detail :</h5>
                            @session('status')
                            <p class="text-slate-400 mb-4">{{$value}}</p>
                            @endsession
                            <form action="{{route('personal-info.update')}}" method="POST">
                                @csrf
                                <div class="grid lg:grid-cols-12 md:grid-cols-2 grid-cols-1 gap-4">
                                    <div class="lg:col-span-12">
                                        <label class="form-label font-medium">Name : <span class="text-red-600">*</span></label>
                                        <input type="text"
                                               class="form-input border border-slate-100 dark:border-slate-800 mt-2"
                                               value="{{auth()->user()->name}}"
                                               id="firstname" name="name" required="">
                                        @error('name')
                                        <span class="text-red-600">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="lg:col-span-6">
                                        <label class="form-label font-medium">Your Email : <span
                                                class="text-red-600">*</span></label>
                                        <input type="email"
                                               class="form-input border border-slate-100 dark:border-slate-800 mt-2 disabled:opacity-50"
                                               value="{{ auth()->user()->email }}"
                                               name="email" required="" disabled>
                                        @error('email')
                                        <span class="text-red-600">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="lg:col-span-6">
                                        <label class="form-label font-medium" for="birthday">Date of Birth :</label>
                                        <input type="date" id="birthday" name="dob"
                                               class="form-input border border-slate-100 dark:border-slate-800 mt-2"
                                               value="{{auth()->user()->dob->format('Y-m-d')}}">
                                        @error('dob')
                                        <span class="text-red-600">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="lg:col-span-12">
                                        <label class="form-label font-medium">Your Address :</label>
                                        <input type="address"
                                               class="form-input border border-slate-100 dark:border-slate-800 mt-2"
                                               value="{{auth()->user()->address}}"
                                               name="address" required="">
                                        @error('address')
                                        <span class="text-red-600">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="lg:col-span-3">
                                        <label class="form-label font-medium">State :</label>
                                        <input type="text"
                                               class="form-input border border-slate-100 dark:border-slate-800 mt-2"
                                               value="{{auth()->user()->state}}"
                                               name="state" required="">
                                        @error('state')
                                        <span class="text-red-600">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="lg:col-span-3">
                                        <label class="form-label font-medium">Country :</label>
                                        <input type="text"
                                               class="form-input border border-slate-100 dark:border-slate-800 mt-2"
                                               value="{{auth()->user()->country ?? null}}"
                                               name="country" required="">
                                        @error('country')
                                        <span class="text-red-600">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="lg:col-span-3">
                                        <label class="form-label font-medium">Timezone :</label>
                                        <select name="timezone"
                                                class="form-select form-input border border-slate-100 dark:border-slate-800 block w-full mt-2">
                                            <option value="{{auth()->user()->timezone}}"
                                                    selected>{{auth()->user()->timezone}}</option>
                                            @foreach($timezones as $timezone)
                                                <option value="{{$timezone->value}}">{{$timezone->value}}</option>
                                            @endforeach
                                        </select>
                                        @error('timezone')
                                        <span class="text-red-600">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="lg:col-span-2">
                                        <label class="form-label font-medium">Postal Code :</label>
                                        <input type="number"
                                               class="form-input border border-slate-100 dark:border-slate-800 mt-2"
                                               value="{{auth()->user()->postcode}}"
                                               name="postcode" required="">
                                        @error('postcode')
                                        <span class="text-red-600">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="lg:col-span-6">
                                        <label class="form-label font-medium">Mobile No. :</label>
                                        <input type="number"
                                               class="form-input border border-slate-100 dark:border-slate-800 mt-2"
                                               value="{{auth()->user()->phone}}" `
                                               name="phone" required="">
                                        @error('phone')
                                        <span class="text-red-600">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="lg:col-span-6">
                                        <label class="form-label font-medium">Occupation :</label>
                                        <input type="text"
                                               class="form-input border border-slate-100 dark:border-slate-800 mt-2"
                                               value="{{auth()->user()->occupation}}"
                                               name="occupation" required="">
                                        @error('occupation')
                                        <span class="text-red-600">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div><!--end grid-->

                                <div class="grid grid-cols-1">
                                    <div class="mt-5">
                                        <label class="form-label font-medium">Introduce yourself : </label>
                                        <textarea name="bio" id="comments"
                                                  class="form-input border border-slate-100 dark:border-slate-800 mt-2 textarea"
                                        >{{auth()->user()->bio}}</textarea>
                                        @error('comments')
                                        <span class="text-red-600">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div><!--end row-->

                                <button type="submit"
                                        class="btn bg-emerald-600 hover:bg-emerald-700 text-white rounded-md mt-5">
                                    Update Personal Info
                                </button>
                            </form><!--end form-->
                        </div>
                    </div>

                    <div class="lg:col-span-6">
                        <div class="p-6 rounded-md shadow dark:shadow-gray-800 bg-white dark:bg-slate-900">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <h5 class="text-lg font-semibold mb-4">Skills :</h5>
                                    <form id="skills-form" action="{{ route('skill.update') }}" method="POST">
                                        @csrf
                                        <div id="skills-container">
                                            @foreach ($skills['skill'] as $index => $skill)
                                                <div class="grid grid-cols-1 gap-4 skill-entry">
                                                    <div>
                                                        <label for="skill{{ $index }}" class="form-label font-medium">Skill
                                                            Name</label>
                                                        <input type="text"
                                                               class="form-input border border-slate-100 dark:border-slate-800 mt-2"
                                                               placeholder="Skill Name:" id="skill{{ $index }}"
                                                               name="skill[]" required=""
                                                               value="{{ $skill }}">
                                                        @error('skill')
                                                        <span class="text-red-600">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <label for="skill_level{{ $index }}"
                                                               class="form-label font-medium">Skill Level</label>
                                                        <select
                                                            class="form-select border border-slate-100 dark:border-slate-800 mt-2 mx-5 px-20 py-3"
                                                            id="skill_level{{ $index }}" name="skill_level[]"
                                                            required="">
                                                            <option
                                                                value="{{ App\Enums\SkillProficiency::PROFICIENT->value }}"
                                                                @if ($skills['skill_level'][$index] == App\Enums\SkillProficiency::PROFICIENT->value) selected @endif>
                                                                Proficient
                                                            </option>
                                                            <option
                                                                value="{{ App\Enums\SkillProficiency::INTERMEDIATE->value }}"
                                                                @if ($skills['skill_level'][$index] == App\Enums\SkillProficiency::INTERMEDIATE->value) selected @endif>
                                                                Intermediate
                                                            </option>
                                                            <option
                                                                value="{{ App\Enums\SkillProficiency::BEGINNER->value }}"
                                                                @if ($skills['skill_level'][$index] == App\Enums\SkillProficiency::BEGINNER->value) selected @endif>
                                                                Beginner
                                                            </option>
                                                        </select>
                                                        @error('skill_level')
                                                        <span class="text-red-600">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <button type="button"
                                                class="btn bg-emerald-600 hover:bg-emerald-700 text-white rounded-md mt-5"
                                                id="add-skill">Add More Skill
                                        </button>
                                        <button type="submit"
                                                class="btn bg-emerald-600 hover:bg-emerald-700 text-white rounded-md mt-5">
                                            Update
                                            Skills
                                        </button>
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
                                    <form action="{{ route('experience.update') }}" method="POST">
                                        @csrf
                                        <div id="experience-container">
                                            @if(!empty($experiences['job_title']))
                                                @foreach($experiences['job_title'] as $index => $job_title)
                                                    <div class="experience-form">
                                                        <div class="grid grid-cols-12 mt-6 gap-4">
                                                            <div class="col-span-12">
                                                                <label class="form-label font-medium">Job Title <span
                                                                        class="text-red-600">*</span></label>
                                                                <input name="job_title[]" id="JobTitle" type="text"
                                                                       class="form-input border border-slate-100 dark:border-slate-800"
                                                                       placeholder="Title :" value="{{ $job_title }}">
                                                            </div><!--end col-->
                                                            <div class="col-span-12">
                                                                <label class="form-label font-medium">Company Name <span
                                                                        class="text-red-600">*</span></label>
                                                                <input name="company_name[]" id="CompanyName"
                                                                       type="text"
                                                                       class="form-input border border-slate-100 dark:border-slate-800"
                                                                       placeholder="Company :"
                                                                       value="{{ $experiences['company_name'][$index] }}">
                                                            </div><!--end col-->
                                                            <div class="col-span-12">
                                                                <label class="form-label font-medium">Year <span
                                                                        class="text-red-600">*</span></label>
                                                                <input name="year[]" id="Year" type="number"
                                                                       class="form-input border border-slate-100 dark:border-slate-800"
                                                                       placeholder="Year :"
                                                                       value="{{ $experiences['year'][$index] }}">
                                                            </div><!--end col-->
                                                            <div class="col-span-12">
                                                                <label class="form-label font-medium"> Description
                                                                    : </label>
                                                                <textarea name="description[]" id="Description"
                                                                          class="form-input border border-slate-100 dark:border-slate-800 textarea"
                                                                          placeholder="Description :">{{ $experiences['description'][$index] }}</textarea>
                                                            </div><!--end col-->
                                                        </div>
                                                    </div><!-- end experience-form -->
                                                @endforeach
                                            @else
                                                <div class="experience-form">
                                                    <div class="grid grid-cols-12 mt-6 gap-4">
                                                        <div class="col-span-12">
                                                            <label class="form-label font-medium">Job Title <span
                                                                    class="text-red-600">*</span></label>
                                                            <input name="job_title[]" id="JobTitle" type="text"
                                                                   class="form-input border border-slate-100 dark:border-slate-800"
                                                                   placeholder="Title :">
                                                        </div><!--end col-->
                                                        <div class="col-span-12">
                                                            <label class="form-label font-medium">Company Name <span
                                                                    class="text-red-600">*</span></label>
                                                            <input name="company_name[]" id="CompanyName" type="text"
                                                                   class="form-input border border-slate-100 dark:border-slate-800"
                                                                   placeholder="Company :">
                                                        </div><!--end col-->
                                                        <div class="col-span-12">
                                                            <label class="form-label font-medium">Year <span
                                                                    class="text-red-600">*</span></label>
                                                            <input name="year[]" id="Year" type="number"
                                                                   class="form-input border border-slate-100 dark:border-slate-800"
                                                                   placeholder="Year :">
                                                        </div><!--end col-->
                                                        <div class="col-span-12">
                                                            <label class="form-label font-medium"> Description
                                                                : </label>
                                                            <textarea name="description[]" id="Description"
                                                                      class="form-input border border-slate-100 dark:border-slate-800 textarea"
                                                                      placeholder="Description :"></textarea>
                                                        </div><!--end col-->
                                                    </div>
                                                </div><!-- end experience-form -->
                                            @endif
                                        </div><!-- end experience-container -->
                                        <button id="add-experience"
                                                class="btn bg-emerald-600 hover:bg-emerald-700 text-white rounded-md mt-5">
                                            Add New Experience
                                        </button>
                                        <button type="submit"
                                                class="btn bg-emerald-600 hover:bg-emerald-700 text-white rounded-md mt-5">
                                            Update Experience
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="lg:col-span-12">
                        <div class="p-6 rounded-md shadow dark:shadow-gray-800 bg-white dark:bg-slate-900">
                            <div class="grid lg:grid-cols-2 grid-cols-1 gap-4">
                                <div>
                                    <h5 class="text-lg font-semibold mb-4">Contact Info :</h5>

                                    <form action="{{route('contact-info.update')}}" method="POST">
                                        @csrf
                                        <div class="grid grid-cols-1 gap-4">
                                            <div>
                                                <label class="form-label font-medium">Phone No. :</label>
                                                <input name="phone" value="{{auth()->user()->phone}}" id="number"
                                                       type="number"
                                                       class="form-input border border-slate-100 dark:border-slate-800 mt-2"
                                                       placeholder="Phone :">
                                                @error('phone')
                                                <span class="text-red-600">{{$message}}</span>
                                                @enderror
                                            </div>

                                            <div>
                                                <label class="form-label font-medium">Website :</label>
                                                <input name="website"
                                                       value="{{auth()->user()->website}}"
                                                       id="url" type="url"
                                                       class="form-input border border-slate-100 dark:border-slate-800 mt-2"
                                                       placeholder="Url :">
                                                @error('website')
                                                <span class="text-red-600">{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div><!--end grid-->

                                        <button
                                            class="btn bg-emerald-600 hover:bg-emerald-700 text-white rounded-md mt-5">
                                            Update Contact Info
                                        </button>
                                    </form>
                                </div><!--end col-->

                                <div>
                                    <h5 class="text-lg font-semibold mb-4">Change password :</h5>
                                    <form action="{{route('userpassword.update')}}" method="POST">
                                        @csrf
                                        <div class="grid grid-cols-1 gap-4">
                                            <div>
                                                <label class="form-label font-medium">Old password :</label>
                                                <input type="password"
                                                       name="current_password"
                                                       class="form-input border border-slate-100 dark:border-slate-800 mt-2"
                                                       placeholder="Old password" required="">
                                            </div>

                                            <div>
                                                <label class="form-label font-medium">New password :</label>
                                                <input type="password"
                                                       name="password"
                                                       class="form-input border border-slate-100 dark:border-slate-800 mt-2"
                                                       placeholder="New password" required="">
                                            </div>

                                            <div>
                                                <label class="form-label font-medium">Re-type New password :</label>
                                                <input type="password"
                                                       name="password_confirmation"
                                                       class="form-input border border-slate-100 dark:border-slate-800 mt-2"
                                                       placeholder="Re-type New password" required="">
                                            </div>
                                        </div><!--end grid-->

                                        <button
                                            class="btn bg-emerald-600 hover:bg-emerald-700 text-white rounded-md mt-5">
                                            Save password
                                        </button>
                                    </form>
                                </div><!--end col-->
                            </div><!--end row-->
                        </div>
                    </div>

                    <div class="lg:col-span-12">
                        <div class="p-6 rounded-md shadow dark:shadow-gray-800 bg-white dark:bg-slate-900">
                            <h5 class="text-lg font-semibold mb-4">Social Media :</h5>

                            <form action="{{route('social-media-info.update')}}" method="POST">
                                @csrf
                                <div class="md:flex">
                                    <div class="md:w-1/3">
                                        <span class="font-medium">Twitter</span>
                                    </div>

                                    <div class="md:w-2/3 mt-4 md:mt-0">

                                        <div class="form-icon relative">
                                            <i data-feather="twitter" class="size-4 absolute top-5 start-4"></i>
                                            <input type="text"
                                                   class="form-input border border-slate-100 dark:border-slate-800 mt-2 ps-12"
                                                   value="{{auth()->user()->twitter}}" id="twitter_name" name="twitter"
                                                   required="">
                                        </div>


                                        <p class="text-slate-400 mt-1">Add your Twitter username (e.g. jennyhot).</p>
                                    </div>
                                </div>

                                <div class="md:flex mt-8">
                                    <div class="md:w-1/3">
                                        <span class="font-medium">Facebook</span>
                                    </div>

                                    <div class="md:w-2/3 mt-4 md:mt-0">

                                        <div class="form-icon relative">
                                            <i data-feather="facebook" class="size-4 absolute top-5 start-4"></i>
                                            <input type="text"
                                                   class="form-input border border-slate-100 dark:border-slate-800 mt-2 ps-12"
                                                   value="{{auth()->user()->facebook}}" id="facebook_name"
                                                   name="facebook" required="">
                                        </div>


                                        <p class="text-slate-400 mt-1">Add your Facebook username (e.g. jennyhot).</p>
                                    </div>
                                </div>

                                <div class="md:flex mt-8">
                                    <div class="md:w-1/3">
                                        <span class="font-medium">Github</span>
                                    </div>

                                    <div class="md:w-2/3 mt-4 md:mt-0">

                                        <div class="form-icon relative">
                                            <i data-feather="github" class="size-4 absolute top-5 start-4"></i>
                                            <input type="text"
                                                   class="form-input border border-slate-100 dark:border-slate-800 mt-2 ps-12"
                                                   value="{{auth()->user()->github}}" id="git_name" name="github"
                                                   required="">
                                        </div>


                                        <p class="text-slate-400 mt-1">Add your Github username (e.g. jennyhot).</p>
                                    </div>
                                </div>

                                <div class="md:flex mt-8">
                                    <div class="md:w-1/3">
                                        <span class="font-medium">Linkedin</span>
                                    </div>

                                    <div class="md:w-2/3 mt-4 md:mt-0">

                                        <div class="form-icon relative">
                                            <i data-feather="linkedin" class="size-4 absolute top-5 start-4"></i>
                                            <input type="text"
                                                   class="form-input border border-slate-100 dark:border-slate-800 mt-2 ps-12"
                                                   value="{{auth()->user()->linkedin}}" id="linkedin_name"
                                                   name="linkedin" required="">
                                        </div>


                                        <p class="text-slate-400 mt-1">Add your Linkedin username.</p>
                                    </div>
                                </div>

                                <div class="md:flex">
                                    <div class="md:w-1/3">
                                        <button type="submit"
                                                class="btn bg-emerald-600 hover:bg-emerald-700 text-white rounded-md mt-5">
                                            Update Social Info
                                        </button>
                                    </div>

                                </div>
                            </form>

                        </div>

                    </div>
                </div>


            </div>
        </div>
    </section>
    <!-- End Hero -->
@endsection
@push('extra-js')
    <script>
        document.getElementById('add-experience').addEventListener('click', function (event) {
            event.preventDefault();
            var experienceContainer = document.getElementById('experience-container');
            var experienceForm = document.querySelector('.experience-form');
            var newExperienceForm = experienceForm.cloneNode(true);

            var inputs = newExperienceForm.querySelectorAll('input, textarea');
            inputs.forEach(function (input) {
                input.value = '';
            });

            experienceContainer.appendChild(newExperienceForm);
        });

        document.getElementById('add-skill').addEventListener('click', function () {
            var skillContainer = document.getElementById('skills-container');
            var skillEntry = document.querySelector('.skill-entry'); // Select the first skill entry template

            if (skillEntry) {
                var newSkillEntry = skillEntry.cloneNode(true);
                newSkillEntry.querySelector('input').value = '';
                newSkillEntry.querySelector('select').value = '{{ App\Enums\SkillProficiency::PROFICIENT->value }}'; // Set default skill level
                skillContainer.appendChild(newSkillEntry);
            }
        });


    </script>

@endpush
