<div>
    <x-loading />
    <div class="bg-white dark:bg-[#1a1a3a] p-6 rounded-2xl border border-gray-200 dark:border-gray-700 mt-6">
        <button
            wire:click="saveForLetter()"
            class="w-full px-6 py-3 bg-gray-100 dark:bg-white text-gray-900 dark:text-pink-600 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-200 transition font-medium flex items-center justify-center gap-2 mb-4">
            <i class="las la-file-alt text-xl"></i> Save for later
        </button>
    </div>
    <x-notification />
</div>
