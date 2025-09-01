<div>
    <!-- Cover Letter Generation Button -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 dark:from-purple-600 dark:to-pink-500 mt-8 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 text-center text-white">
        <h3 class="text-2xl font-semibold mb-4">Generate a Tailored Cover Letter!</h3>
        <p class="text-blue-100 dark:text-gray-100 mb-6">Our AI will analyze your profile and create a personalized cover letter that highlights your relevant skills and experience.</p>
        <div class="flex justify-center">
            <button 
                wire:click="openChat"
                wire:loading.attr="disabled"
                class="px-8 py-3 bg-white text-blue-600 dark:text-pink-600 rounded-lg font-medium text-lg flex items-center gap-2 hover:bg-gray-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                {{ $isGenerating ? 'disabled' : '' }}>
                @if($isGenerating)
                    <i class="las la-spinner animate-spin text-xl"></i>
                    <span>Generating...</span>
                @else
                    <i class="las la-comment-dots text-xl"></i>
                    <span>Generate Cover Letter</span>
                @endif
            </button>
        </div>
    </div>

    <!-- Sidebar Modal -->
    <div 
        x-data="{ show: @entangle('isOpen') }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-[9999] overflow-hidden"
        aria-labelledby="modal-title">
        
        <!-- Background overlay -->
        <div 
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 transition-opacity z-[9998]"
            @click="$wire.closeChat()"></div>

        <!-- Sidebar Panel -->
        <div class="fixed inset-y-0 right-0 flex max-w-full pl-10 z-[9999]">
            <div 
                x-show="show"
                x-transition:enter="transform transition ease-in-out duration-300"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="w-screen max-w-md">
                
                <div class="flex h-full flex-col bg-white dark:bg-[#1a1a3a] shadow-2xl border-l border-gray-200 dark:border-gray-700">
                    <!-- Header -->
                    <div class="flex items-center justify-between px-6 py-6 bg-gradient-to-r from-blue-600 to-blue-700 dark:from-purple-600 dark:to-pink-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                    <i class="las la-file-alt text-2xl text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-white text-opacity-85 font-medium">{{ $job->employer_name }}</p>
                                <h2 class="text-lg font-semibold text-white">{{ $job->job_title }}</h2>
                                <p class="text-xs text-white text-opacity-70">AI Cover Letter Generator</p>
                            </div>
                        </div>
                        <button 
                            wire:click="closeChat"
                            class="rounded-md text-white hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-white">
                            <span class="sr-only">Close panel</span>
                            <i class="las la-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Generation Status -->
                    @if($isGenerating)
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="bg-gray-100 dark:bg-[#2d2d5f] rounded-lg p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-600 dark:bg-pink-600 rounded-full flex items-center justify-center">
                                        <i class="las la-spinner animate-spin text-white"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-gray-900 dark:text-white text-sm font-medium">Generating your cover letter...</p>
                                        <p class="text-gray-600 dark:text-gray-400 text-xs mt-1">This may take a few moments</p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="w-full bg-gray-300 dark:bg-gray-700 rounded-full h-2 relative overflow-hidden progress-shimmer">
                                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 dark:from-purple-600 dark:to-pink-500 h-2 rounded-full w-full animate-pulse"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Chat Messages Area -->
                    <div class="flex-1 overflow-y-auto bg-gray-50 dark:bg-[#12122b]" 
                         id="chat-messages"
                         x-data="{ 
                            scrollToBottom() { 
                                this.$el.scrollTop = this.$el.scrollHeight; 
                            } 
                         }"
                         x-effect="scrollToBottom()">
                        <div class="px-6 py-6 space-y-4">
                            @forelse($chatHistory as $index => $message)
                                <div class="flex {{ $message['type'] === 'user' ? 'justify-end' : 'justify-start' }} fade-in">
                                    <div class="max-w-[90%]">
                                        @if($message['type'] === 'user')
                                            <div class="bg-blue-600 dark:bg-pink-600 rounded-lg px-4 py-3 text-white">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <i class="las la-user text-sm"></i>
                                                    <span class="text-xs opacity-75">You</span>
                                                </div>
                                                <p class="text-sm">{{ $message['message'] }}</p>
                                            </div>
                                        @else
                                            <div class="bg-white dark:bg-gray-700 rounded-lg px-4 py-3 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-600">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <i class="las la-robot text-sm text-blue-600 dark:text-pink-400"></i>
                                                    <span class="text-xs opacity-75">AI Assistant</span>
                                                </div>
                                                <div class="text-sm {{ isset($message['isError']) ? 'text-red-600 dark:text-red-300' : '' }}">
                                                    @if(isset($message['isStreaming']))
                                                        <div class="space-y-3">
                                                            <!-- Loading indicator always shows when streaming -->
                                                            <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-lg border border-blue-200 dark:border-purple-500/20">
                                                                <div class="flex items-center gap-1">
                                                                    <div class="w-3 h-3 bg-blue-500 dark:bg-pink-500 rounded-full animate-bounce"></div>
                                                                    <div class="w-3 h-3 bg-blue-600 dark:bg-purple-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                                                    <div class="w-3 h-3 bg-blue-500 dark:bg-pink-500 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                                                                </div>
                                                                <div class="flex-1">
                                                                    <p class="text-sm text-gray-900 dark:text-white font-medium">Generating Cover Letter</p>
                                                                    <p class="text-xs text-gray-600 dark:text-gray-300">AI is analyzing the job and crafting your personalized letter...</p>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Show generated content if any -->
                                                            @if(!empty($message['message']) && $message['message'] !== 'Starting to generate your personalized cover letter...')
                                                                <div class="p-3 bg-gray-100 dark:bg-gray-800/50 rounded-lg">
                                                                    <div class="whitespace-pre-line text-gray-900 dark:text-gray-100">{{ $message['message'] }}</div>
                                                                    <div class="mt-2 flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                                                                        <i class="las la-circle animate-pulse"></i>
                                                                        <span>Still generating...</span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                     @else
                                                        @if(!empty($message['message']))
                                                            <div class="whitespace-pre-line">{{ $message['message'] }}</div>
                                                            <!-- Copy button for completed AI messages -->
                                                            <div class="mt-3 pt-2 border-t border-gray-200 dark:border-gray-600">
                                                                <button 
                                                                    wire:click="copyMessage({{ json_encode($message['message']) }})"
                                                                    class="text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white flex items-center gap-1 transition-colors">
                                                                    <i class="las la-copy"></i>
                                                                    <span>Copy message</span>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-gray-500 dark:text-gray-400 py-16">
                                    <div class="w-20 h-20 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <i class="las la-file-alt text-3xl text-gray-600 dark:text-gray-400"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Ready to Generate</h3>
                                    <p class="text-sm">Click the button below to create your personalized cover letter</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Action Area -->
                    <div class="px-6 py-4 bg-white dark:bg-[#1a1a3a] border-t border-gray-200 dark:border-gray-700">
                        @if(empty($chatHistory) && !$isGenerating)
                            <!-- Initial generation button -->
                            <button 
                                wire:click="generateInitialLetter"
                                wire:loading.attr="disabled"
                                wire:target="generateInitialLetter"
                                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 dark:from-purple-600 dark:to-pink-500 text-white px-4 py-3 rounded-lg font-medium text-sm flex items-center justify-center gap-2 hover:from-blue-700 hover:to-blue-800 dark:hover:from-purple-700 dark:hover:to-pink-600 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ $isGenerating ? 'disabled' : '' }}>
                                <span wire:loading.remove wire:target="generateInitialLetter">
                                    <i class="las la-magic text-lg"></i>
                                    <span>Generate a cover letter for this position</span>
                                </span>
                                <span wire:loading wire:target="generateInitialLetter" class="flex items-center gap-2">
                                    <i class="las la-spinner animate-spin text-lg"></i>
                                    <span>Generating...</span>
                                </span>
                            </button>
                        @elseif(!empty($currentLetter) && !$isGenerating)
                            <!-- Actions for completed letter -->
                            <div class="space-y-3">
                                <!-- Feedback input -->
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Improve this cover letter</label>
                                        <textarea 
                                            wire:model="feedback"
                                            placeholder="Tell me how to make this cover letter better..."
                                            rows="3"
                                            class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 text-sm focus:border-blue-500 dark:focus:border-pink-500 focus:ring-1 focus:ring-blue-500 dark:focus:ring-pink-500 resize-none"
                                            {{ $isGenerating ? 'disabled' : '' }}></textarea>
                                    </div>
                                    <button 
                                        wire:click="submitFeedback"
                                        wire:loading.attr="disabled"
                                        wire:target="submitFeedback"
                                        type="button"
                                        class="w-full bg-blue-600 dark:bg-pink-600 text-white px-4 py-3 rounded-lg font-medium text-sm flex items-center justify-center gap-2 hover:bg-blue-700 dark:hover:bg-pink-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ $isGenerating ? 'disabled' : '' }}>
                                        <span wire:loading.remove wire:target="submitFeedback">
                                            <i class="las la-paper-plane text-lg"></i>
                                            <span>Send Feedback & Regenerate</span>
                                        </span>
                                        <span wire:loading wire:target="submitFeedback" class="flex items-center gap-2">
                                            <i class="las la-spinner animate-spin text-lg"></i>
                                            <span>Regenerating...</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        @elseif($isGenerating)
                            <!-- Show generating status -->
                            <div class="text-center py-4">
                                <div class="flex items-center justify-center gap-3 text-gray-600 dark:text-gray-300">
                                    <i class="las la-spinner animate-spin text-xl"></i>
                                    <span class="text-sm">Generating your cover letter...</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        // Copy to clipboard functionality
        $wire.on('copy-to-clipboard', (event) => {
            navigator.clipboard.writeText(event.text).then(() => {
                // Show success feedback
                const button = document.querySelector('[wire\\:click="copyCoverLetter"]');
                if (button) {
                    const originalText = button.innerHTML;
                    button.innerHTML = '<i class="las la-check mr-2"></i><span>Copied!</span>';
                    button.classList.add('bg-green-600');
                    button.classList.remove('bg-gray-600');
                    
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.classList.remove('bg-green-600');
                        button.classList.add('bg-gray-600');
                    }, 2000);
                }
            }).catch((err) => {
                console.error('Failed to copy text: ', err);
            });
        });

        // Listen for Livewire updates and scroll chat to bottom
        document.addEventListener('livewire:updated', () => {
            const chatMessages = document.getElementById('chat-messages');
            if (chatMessages) {
                setTimeout(() => {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }, 100);
            }
        });

        // Force UI updates during generation
        document.addEventListener('livewire:init', () => {
            $wire.on('generation-started', () => {
                console.log('Generation started');
                // Force a small delay to ensure UI updates
                setTimeout(() => {
                    const chatMessages = document.getElementById('chat-messages');
                    if (chatMessages) {
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    }
                }, 500);
            });
        });
    </script>
    @endscript

    <style>
        /* Custom scrollbar for chat messages */
        #chat-messages::-webkit-scrollbar {
            width: 6px;
        }
        
        #chat-messages::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 3px;
        }
        
        .dark #chat-messages::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        #chat-messages::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 3px;
        }
        
        .dark #chat-messages::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
        }
        
        #chat-messages::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.5);
        }
        
        .dark #chat-messages::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Smooth transitions for messages */
        .chat-message {
            transition: all 0.3s ease-in-out;
        }

        /* Animation for typing indicators */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        /* Smooth progress bar animation */
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        .progress-shimmer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0,0,0,0.2), transparent);
            animation: shimmer 2s infinite;
        }
        
        .dark .progress-shimmer::before {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        }
    </style>
</div>