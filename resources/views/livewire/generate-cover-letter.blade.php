<div>
    <x-loading />
    <!-- Chat Modal -->
    <div x-data="{ open: false }"
         x-show="open"
         @open-chat.window="open = true"
         @close-chat.window="open = false"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         x-cloak>

        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-[#1a1a3a] rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <!-- Modal header -->
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium leading-6 text-white">
                            AI Cover Letter Generator
                        </h3>
                        <button @click="open = false"
                                class="text-gray-400 hover:text-gray-200 transition-colors">
                            <i class="las la-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Chat content area -->
                    <div class="space-y-4 h-[calc(100vh-400px)] overflow-y-auto px-4 py-4 bg-[#12122b] rounded-lg"
                         id="chat-messages">
                        @if($isGenerating && empty($answer))
                            <div class="flex justify-start">
                                <div class="bg-gray-700 rounded-lg px-4 py-2 max-w-[80%]">
                                    <p class="text-white">I'm analyzing your profile and the job requirements to create a personalized cover letter. This will take a moment...</p>
                                </div>
                            </div>
                        @endif

                        <!-- Generated Content -->
                        @if(!empty($answer))
                            <div class="flex justify-start">
                                <div class="bg-gray-700 rounded-lg px-4 py-2 max-w-[80%]">
                                    <p class="text-white whitespace-pre-line">{{ $answer }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Loading Indicator -->
                        @if($isGenerating)
                            <div class="flex justify-start items-center gap-2">
                                <div class="bg-gray-700 rounded-lg px-4 py-2">
                                    <div class="flex space-x-2">
                                        <div class="w-2 h-2 bg-white rounded-full animate-bounce"></div>
                                        <div class="w-2 h-2 bg-white rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                        <div class="w-2 h-2 bg-white rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                                    </div>
                                </div>
                                <span class="text-gray-400 text-sm">AI is writing...</span>
                            </div>
                        @endif
                    </div>

                    <!-- Feedback and Actions Area -->
                    @if(!empty($answer) && !$isGenerating)
                        <!-- Feedback Form -->
                        <div class="mt-4 border-t border-gray-700 pt-4">
                            <form wire:submit="regenerateWithFeedback" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">
                                        How would you like to improve this cover letter?
                                    </label>
                                    <input type="text"
                                           wire:model="feedback"
                                           wire:loading.attr="disabled"
                                           wire:target="regenerateWithFeedback"
                                           class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-pink-500 focus:ring-pink-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                           placeholder="Example: Make it more formal, emphasize my leadership skills, etc."
                                    >
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex gap-3">
                                        <button onclick="alert('click to copy not implemented yet. coming soon')" type="button"
                                                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors flex items-center gap-2">
                                            <i class="las la-copy"></i> Copy
                                        </button>
                                    </div>
                                    <button type="submit"
                                            class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                            wire:loading.attr="disabled"
                                            wire:target="regenerateWithFeedback">
                                        <div wire:loading.remove wire:target="regenerateWithFeedback">
                                            <i class="las la-sync"></i> Regenerate
                                        </div>
                                        <div wire:loading wire:target="regenerateWithFeedback">
                                            <i class="las la-spinner animate-spin"></i> Regenerating...
                                        </div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Generate Button -->
    <div class="bg-gradient-to-r from-purple-600 to-pink-500 mt-8 p-6 rounded-2xl border border-gray-700 text-center text-white">
        <h3 class="text-2xl font-semibold mb-4">Generate a Tailored Cover Letter!</h3>
        <p class="text-gray-100 mb-6">Our AI will analyze your profile and create a personalized cover letter that highlights your relevant skills and experience.</p>
        <div class="flex justify-center">
            <button wire:click="startGeneration"
                    class="px-8 py-3 bg-white text-pink-600 rounded-lg font-medium text-lg
                           flex items-center gap-2 hover:bg-gray-100 transition-colors
                           disabled:opacity-50 disabled:cursor-not-allowed"
                {{ $isGenerating ? 'disabled' : '' }}>
                <i class="las la-file-alt text-xl"></i>
                <span>{{ $isGenerating ? 'AI is writing...' : 'Generate Cover Letter' }}</span>
            </button>
        </div>
    </div>
</div>
