@if ($paginator->hasPages())
    <div class="flex justify-center mt-10 space-x-2 font-ubuntu-medium">
        <!-- Previous Page Link -->
        @if ($paginator->onFirstPage())
            <button class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-white/10 text-gray-500 cursor-not-allowed" disabled>
                Previous
            </button>
        @else
            <a wire:navigate href="{{ $paginator->previousPageUrl() }}"
                class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/20">
                Previous
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-4 py-2 text-gray-500">...</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <button class="px-4 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 dark:from-pink-500 dark:to-purple-600 text-white">
                            {{ $page }}
                        </button>
                    @else
                        <a wire:navigate href="{{ $url }}"
                            class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/20">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        <!-- Next Page Link -->
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" wire:navigate
                class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/20">
                Next
            </a>
        @else
            <button class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-white/10 text-gray-500 cursor-not-allowed" disabled>
                Next
            </button>
        @endif
    </div>
@endif
