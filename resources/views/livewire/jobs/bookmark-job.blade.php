<button 
    wire:click="toggleBookmark" 
    class="p-2 lg:p-3 text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg lg:rounded-xl transition-all duration-200 {{ $isBookmarked ? 'text-red-600 dark:text-red-400' : '' }}" 
    title="{{ $isBookmarked ? 'Remove from bookmarks' : 'Bookmark this job' }}"
    wire:loading.attr="disabled"
    wire:loading.class="opacity-50 cursor-not-allowed"
>
    <div wire:loading.remove wire:target="toggleBookmark">
        @if($isBookmarked)
            <i class="las la-bookmark text-xl"></i>
        @else
            <i class="lar la-bookmark text-xl"></i>
        @endif
    </div>
    <div wire:loading wire:target="toggleBookmark">
        <i class="las la-spinner animate-spin text-xl"></i>
    </div>
</button>