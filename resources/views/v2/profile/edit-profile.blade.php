@extends('v2.layouts.app')
@section('content')
    <!-- Profile Header -->
    <div class="bg-[#12122b] border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-6">
                <!-- Profile Image -->
                <div class="relative group">
                    <img src="{{asset('assets/images/profile.jpg')}}" alt="{{ auth()->user()->name }}"
                         class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover border-2 border-pink-500/20">
                    <div class="absolute inset-0 bg-black/50 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <button class="text-white hover:text-pink-500 transition-colors">
                            <i class="las la-camera text-2xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="flex-1 text-center sm:text-left">
                    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2 font-oxanium-bold">{{ auth()->user()->name }}</h1>
                    <p class="text-gray-400 mb-4 font-ubuntu-regular">{{ auth()->user()->occupation }}</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3 mt-4 sm:mt-0">
                    <a href="{{ route('dashboard') }}" class="bg-gradient-to-r from-pink-500 to-purple-600 hover:opacity-90 text-white px-4 sm:px-6 py-2 rounded-xl transition-all flex items-center gap-2 font-oxanium-semibold text-sm sm:text-base">
                        <i class="las la-eye"></i>
                        <span class="hidden sm:inline">View Profile</span>
                        <span class="sm:hidden">Edit</span>
                    </a>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Form -->
        <div class="max-w-7xl mx-auto px-6 py-12">
            <!-- Success Message -->
            @if (session('status'))
                <div class="mb-8 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 px-4 py-3 rounded-xl">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 font-ubuntu-regular">
                <!-- Left Column -->
                <div class="space-y-8">
                    <!-- Personal Information -->
                    <form action="{{ route('personal-info.update') }}" method="POST" class="bg-[#12122b] rounded-2xl p-6 border border-gray-800">
                        @csrf
                        <h2 class="text-xl font-semibold text-white mb-6 flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-user-circle text-pink-500"></i>
                            Personal Information
                        </h2>
                        <div class="space-y-4">
                            <!-- Keep all existing personal information fields -->
                            <!-- Full Name -->
                            <div>
                                <label class="text-sm text-gray-400 block mb-1">Full Name*</label>
                                <input type="text" name="name" value="{{ auth()->user()->name }}"
                                       class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="text-sm text-gray-400 block mb-1">Email Address*</label>
                                <input type="email" value="{{ auth()->user()->email }}" disabled
                                       class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white/50">
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label class="text-sm text-gray-400 block mb-1">Date of Birth</label>
                                <input type="date" name="dob" value="{{ auth()->user()->dob?->format('Y-m-d') }}"
                                       class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                                @error('dob') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="text-sm text-gray-400 block mb-1">Phone Number</label>
                                <input type="tel" name="phone" value="{{ auth()->user()->phone }}"
                                       class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                                @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Occupation -->
                            <div>
                                <label class="text-sm text-gray-400 block mb-1">Occupation</label>
                                <input type="text" name="occupation" value="{{ auth()->user()->occupation }}"
                                       class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                                @error('occupation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Address -->
                            <div>
                                <label class="text-sm text-gray-400 block mb-1">Address</label>
                                <textarea name="address"
                                          class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500 h-24 resize-none">{{ auth()->user()->address }}</textarea>
                                @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Location Details -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm text-gray-400 block mb-1">State</label>
                                    <input type="text" name="state" value="{{ auth()->user()->state }}"
                                           class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="text-sm text-gray-400 block mb-1">Country</label>
                                    <input type="text" name="country" value="{{ auth()->user()->country }}"
                                           class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                                </div>
                            </div>

                            <!-- Timezone -->
                            <div>
                                <label class="text-sm text-gray-400 block mb-1">Timezone</label>
                                <select name="timezone"
                                        class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                                    @foreach($timezones as $timezone)
                                        <option value="{{ $timezone->value }}"
                                                @if(auth()->user()->timezone === $timezone->value) selected @endif>
                                            {{ $timezone->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Bio -->
                            <div>
                                <label class="text-sm text-gray-400 block mb-1">Bio</label>
                                <textarea name="bio"
                                          class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500 h-24 resize-none">{{ auth()->user()->bio }}</textarea>
                            </div>

                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl py-3 hover:opacity-90 transition-opacity">
                                Update Personal Information
                            </button>
                        </div>
                    </form>

                    <!-- Change Password -->
                    <form action="{{ route('password.update') }}" method="POST" class="bg-[#12122b] rounded-2xl p-6 border border-gray-800">
                        @csrf
                        <h2 class="text-xl font-semibold text-white mb-6 flex items-center gap-2 font-oxanium-semibold">
                            <i class="las la-lock text-pink-500"></i>
                            Change Password
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm text-gray-400 block mb-1">Current Password*</label>
                                <input type="password" name="current_password"
                                       class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                                @error('current_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="text-sm text-gray-400 block mb-1">New Password*</label>
                                <input type="password" name="password"
                                       class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="text-sm text-gray-400 block mb-1">Confirm New Password*</label>
                                <input type="password" name="password_confirmation"
                                       class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                            </div>

                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl py-3 hover:opacity-90 transition-opacity">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Column -->
                <div class="md:col-span-2 space-y-8">
                    <!-- Experience Section -->
                    <form action="{{ route('experience.update') }}" method="POST" class="bg-[#12122b] rounded-2xl p-6 border border-gray-800">
                        @csrf
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold text-white flex items-center gap-2">
                                <i class="las la-briefcase text-pink-500"></i>
                                Work Experience
                            </h2>
                            <button type="button" id="add-experience" class="text-pink-500 hover:text-pink-400">
                                <i class="las la-plus"></i> Add Experience
                            </button>
                        </div>

                        <div id="experience-container" class="space-y-6">
                            @if(!empty($experiences['job_title']))
                                @foreach($experiences['job_title'] as $index => $job_title)
                                    <div class="experience-form border border-gray-700 rounded-xl p-6 space-y-4 relative">
                                        <button type="button" class="remove-experience absolute -top-3 -right-3 bg-red-500/10 hover:bg-red-500/20 text-red-500 rounded-full p-1 transition-colors">
                                            <i class="las la-times text-lg"></i>
                                        </button>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="text-sm text-gray-400 block mb-1">Job Title*</label>
                                                <input type="text" name="job_title[]" value="{{ $job_title }}"
                                                       class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                                            </div>
                                            <div>
                                                <label class="text-sm text-gray-400 block mb-1">Company Name*</label>
                                                <input type="text" name="company_name[]" value="{{ $experiences['company_name'][$index] }}"
                                                       class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="text-sm text-gray-400 block mb-1">Year*</label>
                                                <input type="number" name="year[]" value="{{ $experiences['year'][$index] }}"
                                                       class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="text-sm text-gray-400 block mb-1">Description</label>
                                            <textarea name="description[]"
                                                      class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500 h-24 resize-none">{{ $experiences['description'][$index] }}</textarea>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <!-- Empty state experience form template -->
                                <div class="experience-form border border-gray-700 rounded-xl p-6 space-y-4 relative">
                                    <button type="button" class="remove-experience absolute -top-3 -right-3 bg-red-500/10 hover:bg-red-500/20 text-red-500 rounded-full p-1 transition-colors">
                                        <i class="las la-times text-lg"></i>
                                    </button>
                                    <!-- Rest of your empty form fields -->
                                </div>
                            @endif
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl py-3 hover:opacity-90 transition-opacity">
                                Update Experience
                            </button>
                        </div>
                    </form>

                    <!-- Grid for Skills and Social Media -->
                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Skills Section -->
                        <form id="skills-form" action="{{ route('skill.update') }}" method="POST" class="bg-[#12122b] rounded-2xl p-6 border border-gray-800">
                            @csrf
                            <h2 class="text-xl font-semibold text-white mb-6 flex items-center gap-2 font-oxanium-semibold">
                                <i class="las la-tools text-pink-500"></i>
                                Skills
                            </h2>
                            <div id="skills-container" class="space-y-4">
                                @foreach ($skills['skill'] as $index => $skill)
                                    <div class="grid grid-cols-1 gap-4 skill-entry relative">
                                        <button type="button" class="remove-skill absolute -top-2 -right-2 bg-red-500/10 hover:bg-red-500/20 text-red-500 rounded-full p-1 transition-colors">
                                            <i class="las la-times text-lg"></i>
                                        </button>
                                        <div>
                                            <label class="text-sm text-gray-400 block mb-1">Skill Name</label>
                                            <input type="text" name="skill[]" value="{{ $skill }}"
                                                   class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                                        </div>
                                        <div>
                                            <label class="text-sm text-gray-400 block mb-1">Skill Level</label>
                                            <select name="skill_level[]"
                                                    class="w-full bg-white/5 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                                                @foreach([
                                                    App\Enums\SkillProficiency::PROFICIENT->value => 'Proficient',
                                                    App\Enums\SkillProficiency::INTERMEDIATE->value => 'Intermediate',
                                                    App\Enums\SkillProficiency::BEGINNER->value => 'Beginner'
                                                ] as $value => $label)
                                                    <option value="{{ $value }}"
                                                            @if($skills['skill_level'][$index] == $value) selected @endif>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 space-y-4">
                                <button type="button" id="add-skill"
                                        class="w-full bg-pink-500/10 hover:bg-pink-500/20 text-pink-300 rounded-xl py-3 transition-colors">
                                    <i class="las la-plus"></i> Add More Skills
                                </button>
                                <button type="submit"
                                        class="w-full bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl py-3 hover:opacity-90 transition-opacity">
                                    Update Skills
                                </button>
                            </div>
                        </form>

                        <!-- Social Media Section -->
                        <form action="{{ route('social-media-info.update') }}" method="POST" class="bg-[#12122b] rounded-2xl p-6 border border-gray-800">
                            @csrf
                            <h2 class="text-xl font-semibold text-white mb-6 flex items-center gap-2 font-oxanium-semibold">
                                <i class="las la-share-alt text-pink-500"></i>
                                Social Media
                            </h2>
                            <div class="space-y-4">
                                @foreach([
                                    'twitter' => 'Twitter',
                                    'facebook' => 'Facebook',
                                    'linkedin' => 'LinkedIn',
                                    'github' => 'GitHub',
                                    'website' => 'Website'
                                ] as $field => $label)
                                    <div>
                                        <label class="text-sm text-gray-400 block mb-1">{{ $label }}</label>
                                        <div class="relative">
                                            <i class="las la-{{ $field }} absolute left-4 top-3.5 text-pink-500"></i>
                                            <input type="text" name="{{ $field }}" value="{{ auth()->user()->$field }}"
                                                   class="w-full bg-white/5 border border-gray-700 rounded-xl pl-10 pr-4 py-2.5 text-white focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                                        </div>
                                    </div>
                                @endforeach
                                <button type="submit"
                                        class="w-full bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl py-3 hover:opacity-90 transition-opacity">
                                    Update Social Media
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('extra-js')
        <script>
            // Add Experience
            document.getElementById('add-experience').addEventListener('click', function() {
                const experienceContainer = document.getElementById('experience-container');
                const experienceForm = document.querySelector('.experience-form');
                const newExperienceForm = experienceForm.cloneNode(true);

                // Clear all inputs in the new form
                const inputs = newExperienceForm.querySelectorAll('input, textarea');
                inputs.forEach(function(input) {
                    input.value = '';
                });

                // Ensure remove button is set up
                setupRemoveExperienceButton(newExperienceForm);

                experienceContainer.appendChild(newExperienceForm);
            });

            // Add Skill
            document.getElementById('add-skill').addEventListener('click', function() {
                const skillContainer = document.getElementById('skills-container');
                const skillEntry = document.querySelector('.skill-entry');

                if (skillEntry) {
                    const newSkillEntry = skillEntry.cloneNode(true);

                    // Clear input value
                    newSkillEntry.querySelector('input[name="skill[]"]').value = '';

                    // Reset select to default
                    newSkillEntry.querySelector('select[name="skill_level[]"]').value = '{{ App\Enums\SkillProficiency::PROFICIENT->value }}';

                    // Ensure remove button is set up
                    setupRemoveSkillButton(newSkillEntry);

                    skillContainer.appendChild(newSkillEntry);
                }
            });

            // Setup Remove Buttons for Initial Elements
            function setupInitialRemoveButtons() {
                // Setup for skills
                document.querySelectorAll('.skill-entry').forEach(function(entry) {
                    setupRemoveSkillButton(entry);
                });

                // Setup for experiences
                document.querySelectorAll('.experience-form').forEach(function(form) {
                    setupRemoveExperienceButton(form);
                });
            }

            // Setup Remove Button for Skill
            function setupRemoveSkillButton(skillEntry) {
                const removeButton = skillEntry.querySelector('.remove-skill');
                if (removeButton) {
                    removeButton.addEventListener('click', function() {
                        // Check if this is not the last skill entry
                        const allSkillEntries = document.querySelectorAll('.skill-entry');
                        if (allSkillEntries.length > 1) {
                            skillEntry.remove();
                        } else {
                            // If last entry, just clear the values
                            skillEntry.querySelector('input[name="skill[]"]').value = '';
                            skillEntry.querySelector('select[name="skill_level[]"]').value = '{{ App\Enums\SkillProficiency::PROFICIENT->value }}';
                        }
                    });
                }
            }

            // Setup Remove Button for Experience
            function setupRemoveExperienceButton(experienceForm) {
                const removeButton = experienceForm.querySelector('.remove-experience');
                if (removeButton) {
                    removeButton.addEventListener('click', function() {
                        // Check if this is not the last experience form
                        const allExperienceForms = document.querySelectorAll('.experience-form');
                        if (allExperienceForms.length > 1) {
                            experienceForm.remove();
                        } else {
                            // If last entry, just clear the values
                            const inputs = experienceForm.querySelectorAll('input, textarea');
                            inputs.forEach(input => input.value = '');
                        }
                    });
                }
            }

            // Initialize remove buttons when the page loads
            document.addEventListener('DOMContentLoaded', setupInitialRemoveButtons);
        </script>
    @endpush
@endsection
