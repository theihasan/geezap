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
                disabled
                class="px-8 py-3 bg-white text-pink-600 rounded-lg
                font-medium text-lg flex items-center gap-2
                opacity-50 cursor-not-allowed relative"
            >
                <i class="las la-file-alt text-xl"></i>
                <span>Generate CV</span>
                <span class="absolute -top-3 -right-3 bg-pink-500 text-white text-xs px-2 py-1 rounded-full">
                    Coming Soon
                </span>
            </button>
        </div>
    </div>
</div>
