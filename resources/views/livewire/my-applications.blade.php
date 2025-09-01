<div class="min-h-screen">
    <x-loading />
    <x-notification />
    <section class="py-20 font-ubuntu">
        <div class="max-w-4xl mx-auto px-6">
            <div class="w-full mb-8 bg-gray-50 dark:bg-[#1a1a3a] rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex justify-center">
                    <div class="flex flex-wrap gap-2 text-center">
                        <button
                            wire:click="setTab('all')"
                            wire:loading.attr="disabled"
                            class="px-4 py-2 rounded-xl font-ubuntu-medium {{ $activeTab === 'all' ? 'bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white' : 'bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/10' }} transition-all">
                            All Applications
                        </button>
                        <button
                            wire:click="setTab('applied')"
                            wire:loading.attr="disabled"
                            class="px-4 py-2 rounded-xl font-ubuntu-medium {{ $activeTab === \App\Enums\JobSavedStatus::APPLIED->value ? 'bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white' : 'bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/10' }} transition-all">
                            Applied
                        </button>
                        <button
                            wire:click="setTab('saved')"
                            wire:loading.attr="disabled"
                            class="px-4 py-2 rounded-xl font-ubuntu-medium {{ $activeTab === \App\Enums\JobSavedStatus::SAVED->value ? 'bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white' : 'bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/10' }} transition-all">
                            Saved
                        </button>
                    </div>
                </div>
            </div>

            <h1 wire:loading.class="opacity:0" class="text-4xl font-bold text-gray-900 dark:text-white text-center mb-8 font-oxanium-bold">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600">
                    My Applications
                </span>
            </h1>

            <div class="space-y-6">
                @if($this->applications->isEmpty())
                    <div class="text-center text-gray-600 dark:text-gray-400 py-8">
                        No applications found for {{ $activeTab }} status
                    </div>
                @else
                    @foreach($this->applications as $application)
                        <div class="bg-white dark:bg-[#1a1a3a] p-6 rounded-2xl border border-gray-200 dark:border-gray-700 font-ubuntu-regular hover:border-blue-500/50 dark:hover:border-pink-500/50 transition-all">
                            <div class="flex justify-between items-start">
                                <div class="space-y-2">
                                    <a target="__blank" href="{{route('job.show', $application->slug)}}" class="group">
                                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white font-oxanium-semibold group-hover:text-blue-600 dark:group-hover:text-pink-400 transition-colors">{{ $application->job_title }}</h2>
                                    </a>
                                    <p class="text-gray-600 dark:text-gray-300 font-ubuntu-light">{{ $application->employer_name }} {{ $application->city }} • {{ $application->country }}</p>
                                    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                        <i class="las la-calendar text-blue-600 dark:text-pink-400"></i>
                                        Applied on {{ $application->created_at->format('jS M Y') }}
                                    </div>
                                </div>
                                <button
                                    wire:click="removeSavedJob({{$application->id}})"
                                    wire:loading.attr="disabled"
                                    class="px-4 py-2 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition-all text-sm font-ubuntu-medium">
                                    Remove
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Pagination -->
            <x-v2.pagination :jobs="$this->applications"></x-v2.pagination>
        </div>
    </section>
</div>
