<div>
    <div id="applyjob" class="bg-[#1a1a3a] mt-8 p-6 rounded-2xl border border-gray-700 text-center">
        <h3 class="text-2xl font-semibold mb-4 text-white">Ready to Apply?</h3>
        <p class="text-gray-300 mb-6">Choose your preferred application method below.</p>
        
        @if(auth()->check())
            @if($job->applyOptions->isNotEmpty())
                <div class="space-y-4">
                    @foreach($job->applyOptions as $option)
                        <a href="{{ $option->apply_link }}" 
                           target="_blank"
                           wire:click="apply" 
                           class="w-full px-8 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg hover:opacity-90 transition-opacity font-medium text-lg flex items-center justify-center gap-2">
                            <i class="las la-paper-plane text-xl"></i> Apply via {{ $option->publisher }}
                        </a>
                    @endforeach
                </div>
            @else
                <div class="flex justify-center">
                    <a href="{{ $job->apply_link }}"
                       target="_blank"
                       wire:click="apply" 
                       class="px-8 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg hover:opacity-90 transition-opacity font-medium text-lg flex items-center gap-2">
                        <i class="las la-paper-plane text-xl"></i> Apply Now
                    </a>
                </div>
            @endif
        @else
            <div class="flex justify-center">
                <a href="{{ route('login') }}"
                   class="px-8 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg hover:opacity-90 transition-opacity font-medium text-lg flex items-center gap-2">
                    Please login to apply
                </a>
            </div>
        @endif
    </div>
</div>
