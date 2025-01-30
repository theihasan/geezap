@props([
    'jobs' => $jobs,
])
<div class="flex justify-between md:justify-center mt-10 font-ubuntu-medium">
    <!-- Previous Button -->
    @if ($jobs->onFirstPage())
        <button class="px-4 py-2 rounded-lg bg-white/10 text-gray-500 cursor-not-allowed" disabled>
            Previous
        </button>
    @else
        <a wire:navigate href="{{ $jobs->previousPageUrl() }}"
           class="px-4 py-2 rounded-lg bg-white/10 text-gray-300 hover:bg-white/20">
            Previous
        </a>
    @endif


    <div class="hidden md:flex gap-2 mx-2">
        @if($jobs->currentPage() > 3)
            <a wire:navigate href="{{ $jobs->url(1) }}"
               class="px-4 py-2 rounded-lg bg-white/10 text-gray-300 hover:bg-white/20">
                1
            </a>
            @if($jobs->currentPage() > 4)
                <span class="px-4 py-2 text-gray-500">...</span>
            @endif
        @endif

        @foreach (range(max($jobs->currentPage() - 2, 1), min($jobs->currentPage() + 2, $jobs->lastPage())) as $page)
            @if ($page == $jobs->currentPage())
                <button class="px-4 py-2 rounded-lg bg-gradient-to-r from-pink-500 to-purple-600 text-white">
                    {{ $page }}
                </button>
            @else
                <a wire:navigate href="{{ $jobs->url($page) }}"
                   class="px-4 py-2 rounded-lg bg-white/10 text-gray-300 hover:bg-white/20">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        @if($jobs->currentPage() < $jobs->lastPage() - 2)
            @if($jobs->currentPage() < $jobs->lastPage() - 3)
                <span class="px-4 py-2 text-gray-500">...</span>
            @endif
            <a wire:navigate href="{{ $jobs->url($jobs->lastPage()) }}"
               class="px-4 py-2 rounded-lg bg-white/10 text-gray-300 hover:bg-white/20">
                {{ $jobs->lastPage() }}
            </a>
        @endif
    </div>

    <!-- Next Button -->
    @if ($jobs->hasMorePages())
        <a wire:navigate href="{{ $jobs->nextPageUrl() }}"
           class="px-4 py-2 rounded-lg bg-white/10 text-gray-300 hover:bg-white/20">
            Next
        </a>
    @else
        <button class="px-4 py-2 rounded-lg bg-white/10 text-gray-500 cursor-not-allowed" disabled>
            Next
        </button>
    @endif
</div>
