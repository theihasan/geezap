@extends('layouts.app')
@section('main-content')

    <!-- Start -->
    <section class="bg-slate-50 dark:bg-slate-800 md:py-24 py-16">
        <div class="container mt-10">
            <div class="grid md:grid-cols-12 grid-cols-1 gap-[30px]">
                <div class="lg:col-span-8 md:col-span-6">
                    <div class="md:flex items-center p-6 shadow dark:shadow-gray-700 rounded-md bg-white dark:bg-slate-900">
                        <img src="{{$job->employer_logo}}" class="rounded-full size-28 p-4 bg-white dark:bg-slate-900 shadow dark:shadow-gray-700" alt="{{$job->employer_name}}">
                        <div class="md:ms-4 md:mt-0 mt-6">
                            <h5 class="text-xl font-semibold">{{$job->job_title}}</h5>
                            <div class="mt-2">
                            <span class="text-slate-400 font-medium me-2 inline-block">
                                <i class="uil uil-building text-[18px] text-emerald-600 me-1"></i> {{$job->employer_name}}</span>
                                @if($job->state && $job->country)
                                    <span class="text-slate-400 font-medium me-2 inline-block">
                                <i class="uil uil-map-marker text-[18px] text-emerald-600 me-1"></i> {{$job->state}} , {{$job->country}}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <h5 class="text-lg font-semibold mt-6">Job Description:</h5>
                    <span id="description" class="text-slate-400 mt-2">
                    {!! nl2br($job->description) !!}
                </span>

                    @if($job->qualifications)
                        <h5 class="text-lg font-semibold mt-6">Qualifications: </h5>
                        <ul id="qualifications" class="list-none">
                            @foreach($job->qualifications as $qualification)
                                <li class="text-slate-400 mt-2"><i class="uil uil-arrow-right text-emerald-600 me-1"></i>{{$qualification}}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if($job->responsibilities)
                        <h5 class="text-lg font-semibold mt-6">Responsibilities: </h5>
                        <ul id="responsibilities" class="list-none">
                            @foreach($job->responsibilities as $responsibility)
                                <li class="text-slate-400 mt-2"><i class="uil uil-arrow-right text-emerald-600 me-1"></i>{{$responsibility}}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if($job->benefits)
                        <h5 class="text-lg font-semibold mt-6">Benefits: </h5>
                        <ul id="benefits" class="list-none">
                            @foreach($job->benefits as $benefit)
                                <li class="text-slate-400 mt-2"><i class="uil uil-arrow-right text-emerald-600 me-1"></i>{{$benefit}}</li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="mt-5">
                        <a href="{{$job->apply_link}}" class="btn rounded-md bg-emerald-600 hover:bg-emerald-700 border-emerald-600 hover:border-emerald-700 text-white md:ms-2 w-full md:w-auto">Apply Now</a>
                        <button id="cover-letter" class="btn rounded-md bg-white dark:bg-slate-900 dark:border-slate-900 border-emerald-600 text-emerald-600 md:ms-2 w-full md:w-auto mt-3 md:mt-0">Generate Cover Letter</button>
                    </div>
                    <div id="generated-cover-letter-container" style="display: none;">
                        <h1>Generated Cover Letter</h1>
                        <div class="mt-5">
                            <textarea id="generated-cover-letter" rows="30" cols="30" class="w-full p-2 border rounded-md" placeholder=""></textarea>
                        </div>
                    </div>
                    <div id="previous-cover-letters-container" style="display: none;">
                        <h2>Previous Cover Letters</h2>
                        <div id="previous-cover-letters">
                            @if(@session('cover_letters'))
                                @foreach(@session('cover_letters') as $index => $coverLetter)
                                    <div class="cover-letter">
                                        <p><strong>Cover Letter {{$index + 1}}</strong></p>
                                        <hr>
                                        <p>{!! nl2br(e($coverLetter)) !!}</p>
                                        <br>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div><!--end col-->


                <div class="lg:col-span-4 md:col-span-6">
                    <div class="shadow dark:shadow-gray-700 rounded-md bg-white dark:bg-slate-900 sticky top-20">
                        <div class="p-6">
                            <h5 class="text-lg font-semibold">Job Information</h5>
                        </div>
                        <div class="p-6 border-t border-slate-100 dark:border-t-gray-700">
                            <ul class="list-none">
                                <li class="flex items-center">
                                    <i data-feather="user-check" class="size-5"></i>
                                    <div class="ms-4">
                                        <p class="font-medium">Employee Type:</p>
                                        <span class="text-emerald-600 font-medium text-sm">{{$job->employment_type}}</span>
                                    </div>
                                </li>
                                @if($job->state && $job->country)
                                    <li class="flex items-center mt-3">
                                        <i data-feather="map-pin" class="size-5"></i>
                                        <div class="ms-4">
                                            <p class="font-medium">Location:</p>
                                            <span class="text-emerald-600 font-medium text-sm">{{$job->state}} , {{$job->country}}</span>
                                        </div>
                                    </li>
                                @endif
                                @if($job->required_experience)
                                    @php
                                        $experienceInYears = $job->required_experience / 12;
                                        $formattedExperience = $experienceInYears > 1 ? number_format($experienceInYears, 1) . '+' : ($experienceInYears == (int)$experienceInYears ? (int)$experienceInYears : number_format($experienceInYears, 0));
                                    @endphp
                                    <li class="flex items-center mt-3">
                                        <i data-feather="briefcase" class="size-5"></i>
                                        <div class="ms-4">
                                            <p class="font-medium">Experience:</p>
                                            <span class="text-emerald-600 font-medium text-sm">{{ $formattedExperience }} {{Str::plural('Year')}}</span>
                                        </div>
                                    </li>
                                @endif
                                @if($job->max_salary && $job->min_salary)
                                    <li class="flex items-center mt-3">
                                        <i data-feather="dollar-sign" class="size-5"></i>
                                        <div class="ms-4">
                                            <p class="font-medium">Salary:</p>
                                            <span class="text-emerald-600 font-medium text-sm">{{$job->min_salary}} - {{$job->max_salary}} / {{$job->salary_period}}</span>
                                        </div>
                                    </li>
                                @endif
                                <li class="flex items-center mt-3">
                                    <i data-feather="clock" class="size-5"></i>
                                    <div class="ms-4">
                                        <p class="font-medium">Date posted:</p>
                                        <span class="text-emerald-600 font-medium text-sm">{{ $job->posted_at?->isoFormat('Do MMMM, YYYY') }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end grid-->
        </div><!--end container-->

        <div class="container lg:mt-24 mt-16">
            <div class="grid grid-cols-1 pb-8 text-center">
                <h3 class="mb-4 md:text-[26px] md:leading-normal text-2xl leading-normal font-semibold">Related Jobs</h3>
                <p class="text-slate-400 dark:text-slate-300 max-w-xl mx-auto">Search all the open positions on the web. Get your own personalized salary estimate.</p>
            </div><!--end grid-->

            <div class="grid lg:grid-cols-3 md:grid-cols-2 mt-8 gap-[30px]">
                @foreach($relatedJobs as $relatedJob)
                    <div class="group shadow dark:shadow-gray-700 p-6 rounded-md bg-white dark:bg-slate-900">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="size-14 flex items-center justify-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-700 rounded-md">
                                    <img src="{{$relatedJob->employer_logo}}" class="size-8" alt="{{$relatedJob->employer_name}}">
                                </div>
                                <div class="ms-3">
                                    <a href="{{route('job.show', $relatedJob->slug)}}" class="block text-[16px] font-semibold hover:text-emerald-600 transition-all duration-500">{{$relatedJob->employer_name}}</a>
                                    <span class="block text-sm text-slate-400">{{$relatedJob->posted_at?->diffForHumans()}}</span>
                                </div>
                            </div>
                            <span class="bg-emerald-600/10 group-hover:bg-emerald-600 inline-block text-emerald-600 group-hover:text-white text-xs px-2.5 py-0.5 font-semibold rounded-full transition-all duration-500">{{$relatedJob->employment_type}}</span>
                        </div>
                        <div class="mt-6">
                            <a href="{{route('job.show', $relatedJob->slug)}}" class="text-lg hover:text-emerald-600 font-semibold transition-all duration-500">{{$relatedJob->job_title}}</a>
                            <h6 class="text-base font-medium"><i class="uil uil-map-marker"></i> {{$relatedJob->country}}</h6>
                        </div>
                    </div><!--end content-->
                @endforeach
            </div><!--end grid-->
        </div><!--end container-->

    </section><!--end section-->
    <!-- End -->

@endsection
@push('extra-js')
    <script>
        document.getElementById('cover-letter').addEventListener('click', function () {
            var jobTitleElem = document.querySelector('.text-xl');
            var employerNameElem = document.querySelector('.text-slate-400');
            var employerLocationElem = document.querySelector('.text-slate-400:nth-child(2)');
            var jobDescriptionElem = document.getElementById('description');
            var responsibilitiesElem = document.getElementById('responsibilities');
            var qualificationsElem = document.getElementById('qualifications');
            var benefitsElem = document.getElementById('benefits');

            if (!jobTitleElem || !employerNameElem || !employerLocationElem || !jobDescriptionElem) {
                alert('Some required elements are missing on the page.');
                return;
            }

            var data = {
                job_title: jobTitleElem ? jobTitleElem.textContent : '',
                employer_name: employerNameElem ? employerNameElem.textContent : '',
                employer_location: employerLocationElem ? employerLocationElem.textContent : '',
                job_description: jobDescriptionElem ? jobDescriptionElem.textContent : '',
                responsibilities: responsibilitiesElem ? responsibilitiesElem.textContent : '',
                qualifications: qualificationsElem ? qualificationsElem.textContent : '',
                benefits: benefitsElem ? benefitsElem.textContent : ''
            };

            var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/cover-letter', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message === 'Gemini API is working' || data.message === 'Chat GPT API is working') {
                        document.getElementById('generated-cover-letter').value = data.data.response;
                        document.getElementById('generated-cover-letter-container').style.display = 'block';
                        saveCoverLetter(data.data.response);
                        displayPreviousCoverLetters();
                        document.getElementById('previous-cover-letters-container').style.display = 'block';
                    } else {
                        console.log(data.message);
                    }
                })
                .catch((error) => {
                    alert(error);
                });
        });

        function saveCoverLetter(coverLetter) {
            clearOldStorage();
            let coverLetters = JSON.parse(localStorage.getItem('coverLetters')) || [];
            coverLetters.unshift(coverLetter);
            localStorage.setItem('coverLetters', JSON.stringify(coverLetters));
        }

        function displayPreviousCoverLetters() {
            let coverLetters = JSON.parse(localStorage.getItem('coverLetters')) || [];
            let previousCoverLettersContainer = document.getElementById('previous-cover-letters');
            previousCoverLettersContainer.innerHTML = '';
            coverLetters.slice(1).slice(0, 5).reverse().forEach((coverLetter, index) => {
                let coverLetterElement = document.createElement('div');
                coverLetterElement.classList.add('cover-letter');
                coverLetterElement.innerHTML = `
                <p><strong>Cover Letter ${index + 1}</strong></p>
                <hr>
                <p>${coverLetter.replace(/\n/g, '<br>')}</p>
                <br>
            `;
                previousCoverLettersContainer.appendChild(coverLetterElement);
            });
        }

        function clearOldStorage() {
            const today = new Date().toISOString().split('T')[0];
            const lastClearDate = localStorage.getItem('lastClearDate');

            if (lastClearDate !== today) {
                localStorage.removeItem('coverLetters');
                localStorage.setItem('lastClearDate', today);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            displayPreviousCoverLetters();
            clearOldStorage();
        });
    </script>


@endpush
