<div>
    <x-loading />
    @if($coverLetter)
        <div class="bg-gradient-to-r from-purple-600 to-pink-500 mt-8 p-6 rounded-2xl border border-gray-700 text-left text-white">
            {!! nl2br(e($coverLetter)) !!}
        </div>
    @endif
    <div class="bg-gradient-to-r from-purple-600 to-pink-500 mt-8 p-6 rounded-2xl border border-gray-700 text-center text-white">
        <h3 class="text-2xl font-semibold mb-4">Generate a Tailored CV Before Applying!</h3>
        <p class="text-gray-100 mb-6">A customized CV will make your application stand out. Use your profile and this job description to create the perfect CV!</p>
        <div class="flex justify-center">
            <button
                wire:click="generateCoverLetter"
                @disabled($isGenerating)
                class="px-8 py-3 bg-white text-pink-600 rounded-lg
                hover:bg-gray-200 transition font-medium text-lg flex
                items-center gap-2 disabled:opacity-50
                disabled:cursor-not-allowed" >
                <i class="las la-file-alt text-xl"></i>
                <span wire:loading.remove wire:target="generateCoverLetter">
                    Generate CV
                </span>
            </button>
        </div>
    </div>
</div>
