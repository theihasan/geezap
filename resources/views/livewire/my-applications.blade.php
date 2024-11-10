<div class="min-h-screen">
    <div wire:loading class="fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
        <div class="flex items-center justify-center min-h-screen">
            <div class="text-center">
                <div class="inline-block h-12 w-12 animate-spin rounded-full border-4 border-solid border-purple-500 border-r-transparent motion-reduce:animate-[spin_1.5s_linear_infinite] mb-4"></div>
                <p class="text-white font-ubuntu-medium text-lg">Loading...</p>
            </div>
        </div>
    </div>
    <x-notification />
    <section class="py-20 font-ubuntu">
        <div class="max-w-4xl mx-auto px-6">
            <div class="w-full mb-8 bg-[#1a1a3a] rounded-2xl border border-gray-700 p-4">
                <div class="flex justify-center">
                    <div class="flex flex-wrap gap-2 text-center">
                        <button
                            wire:click="setTab('all')"
                            wire:loading.attr="disabled"
                            class="px-4 py-2 rounded-xl font-ubuntu-medium {{ $activeTab === 'all' ? 'bg-gradient-to-r from-pink-500 to-purple-600 text-white' : 'bg-white/5 text-gray-300 hover:bg-white/10' }} transition-all">
                            All Applications
                        </button>
                        <button
                            wire:click="setTab('applied')"
                            wire:loading.attr="disabled"
                            class="px-4 py-2 rounded-xl font-ubuntu-medium {{ $activeTab === \App\Enums\JobSavedStatus::APPLIED->value ? 'bg-gradient-to-r from-pink-500 to-purple-600 text-white' : 'bg-white/5 text-gray-300 hover:bg-white/10' }} transition-all">
                            Applied
                        </button>
                        <button
                            wire:click="setTab('saved')"
                            wire:loading.attr="disabled"
                            class="px-4 py-2 rounded-xl font-ubuntu-medium {{ $activeTab === \App\Enums\JobSavedStatus::SAVED->value ? 'bg-gradient-to-r from-pink-500 to-purple-600 text-white' : 'bg-white/5 text-gray-300 hover:bg-white/10' }} transition-all">
                            Saved
                        </button>
                    </div>
                </div>
            </div>

            <h1 wire:loading.class="opacity:0" class="text-4xl font-bold text-white text-center mb-8 font-oxanium-bold">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-purple-600">
                    My Applications
                </span>
            </h1>

            <div class="space-y-6">
                @if($this->applications->isEmpty())
                    <div class="text-center text-gray-400 py-8">
                        No applications found for {{ $activeTab }} status
                    </div>
                @else
                    @foreach($this->applications as $application)
                        <div class="bg-[#1a1a3a] p-6 rounded-2xl border border-gray-700 font-ubuntu-regular">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h2 class="text-2xl font-semibold text-white font-oxanium-semibold">{{ $application->job_title }}</h2>
                                    <p class="text-gray-300 mb-2 font-ubuntu-light">{{ $application->employer_name }} {{ $application->city }} • {{ $application->country }}</p>
                                </div>
                                <button
                                    wire:click="removeSavedJob({{$application->id}})"
                                    wire:loading.attr="disabled"
                                    class="px-4 py-2 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 transition-all text-sm font-ubuntu-medium">
                                    Remove
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Pagination remains the same -->
            @if($this->applications->hasPages())
                <div class="mt-10">
                    <div class="flex items-center justify-center gap-2 font-ubuntu-medium">
                        <!-- Previous Page -->
                        <button
                            wire:click="previousPage"
                            wire:loading.attr="disabled"
                            @if(!$this->applications->onFirstPage())
                                class="px-4 py-2 rounded-lg bg-white/10 text-gray-300 hover:bg-white/20 transition-all"
                            @else
                                class="px-4 py-2 rounded-lg bg-white/5 text-gray-500 cursor-not-allowed"
                            @endif
                            {{ $this->applications->onFirstPage() ? 'disabled' : '' }}>
                            Previous
                        </button>

                        <!-- Page Numbers -->
                        @foreach($this->applications->getUrlRange(1, $this->applications->lastPage()) as $page => $url)
                            <button
                                wire:click="gotoPage({{ $page }})"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 rounded-lg {{ $page == $this->applications->currentPage()
                                    ? 'bg-gradient-to-r from-pink-500 to-purple-600 text-white'
                                    : 'bg-white/10 text-gray-300 hover:bg-white/20' }} transition-all">
                                {{ $page }}
                            </button>
                        @endforeach

                        <!-- Next Page -->
                        <button
                            wire:click="nextPage"
                            wire:loading.attr="disabled"
                            @if(!$this->applications->onLastPage())
                                class="px-4 py-2 rounded-lg bg-white/10 text-gray-300 hover:bg-white/20 transition-all"
                            @else
                                class="px-4 py-2 rounded-lg bg-white/5 text-gray-500 cursor-not-allowed"
                            @endif
                            {{ $this->applications->onLastPage() ? 'disabled' : '' }}>
                            Next
                        </button>
                    </div>
                    <div class="text-center mt-4 text-sm text-gray-400">
                        Showing {{ $this->applications->firstItem() ?? 0 }} to {{ $this->applications->lastItem() ?? 0 }} of {{ $this->applications->total() }} results
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
