<div>
    <div class="bg-[#1a1a3a] p-6 rounded-2xl border border-gray-700 mt-6">
        {{-- Show session flash message --}}
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('message') }}
            </div>
        @endif

        <button
            wire:click="saveForLetter()"
            class="w-full px-6 py-3 bg-white text-pink-600 rounded-lg hover:bg-gray-200 transition font-medium flex items-center justify-center gap-2 mb-4"
        >
            <i class="las la-file-alt text-xl"></i> Save for letter
        </button>
    </div>
</div>
