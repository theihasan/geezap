<div>
    <div id="applyjob" class="bg-[#1a1a3a] mt-8 p-6 rounded-2xl border border-gray-700 text-center">
        <h3 class="text-2xl font-semibold mb-4 text-white">Ready to Apply?</h3>
        <p class="text-gray-300 mb-6">Choose your preferred application method below.</p>
        
        @if(auth()->check())
            @if($job->applyOptions->isNotEmpty())
                <div class="flex flex-wrap justify-center gap-4">
                    @foreach($job->applyOptions as $option)
                        <a href="{{ $option->apply_link }}" 
                           target="_blank"
                           wire:click="apply" 
                           class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#2a2a4a] hover:bg-[#3a3a5a] border border-pink-500/30 hover:border-pink-500/70 text-white rounded-lg transition-all duration-200 group relative overflow-hidden shadow-lg">
                            <span class="absolute inset-0 bg-gradient-to-r from-pink-500/20 to-purple-600/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                            <i class="las la-paper-plane text-pink-400 group-hover:text-pink-300 transition-colors"></i> 
                            <span class="font-medium">{{ $option->publisher }}</span>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="flex justify-center">
                    <a href="{{ $job->apply_link }}"
                       target="_blank"
                       wire:click="apply" 
                       class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#2a2a4a] hover:bg-[#3a3a5a] border border-pink-500/30 hover:border-pink-500/70 text-white rounded-lg transition-all duration-200 group relative overflow-hidden shadow-lg">
                        <span class="absolute inset-0 bg-gradient-to-r from-pink-500/20 to-purple-600/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        <i class="las la-paper-plane text-pink-400 group-hover:text-pink-300 transition-colors"></i>
                        <span class="font-medium">Apply Now</span>
                    </a>
                </div>
            @endif
        @else
            <div class="flex justify-center">
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#2a2a4a] hover:bg-[#3a3a5a] border border-pink-500/30 hover:border-pink-500/70 text-white rounded-lg transition-all duration-200 group relative overflow-hidden shadow-lg">
                    <span class="absolute inset-0 bg-gradient-to-r from-pink-500/20 to-purple-600/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    <i class="las la-user-lock text-pink-400 group-hover:text-pink-300 transition-colors"></i>
                    <span class="font-medium">Login to Apply</span>
                </a>
            </div>
        @endif
    </div>
</div>
