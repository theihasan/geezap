@props(['pages'])

@if($pages->hasPages())
    <div class="mt-10">
        <div class="flex items-center justify-center gap-2 font-ubuntu-medium">
            <button
                wire:click="previousPage"
                wire:loading.attr="disabled"
                @if(!$pages->onFirstPage())
                    class="px-4 py-2 rounded-lg bg-white/10 text-gray-300 hover:bg-white/20 transition-all"
                @else
                    class="px-4 py-2 rounded-lg bg-white/5 text-gray-500 cursor-not-allowed"
                @endif
                {{ $pages->onFirstPage() ? 'disabled' : '' }}>
                Previous
            </button>

            @foreach($pages->getUrlRange(1, $pages->lastPage()) as $page => $url)
                <button
                    wire:click="gotoPage({{ $page }})"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 rounded-lg {{ $page == $pages->currentPage()
                        ? 'bg-gradient-to-r from-pink-500 to-purple-600 text-white'
                        : 'bg-white/10 text-gray-300 hover:bg-white/20' }} transition-all">
                    {{ $page }}
                </button>
            @endforeach

            <button
                wire:click="nextPage"
                wire:loading.attr="disabled"
                @if(!$pages->onLastPage())
                    class="px-4 py-2 rounded-lg bg-white/10 text-gray-300 hover:bg-white/20 transition-all"
                @else
                    class="px-4 py-2 rounded-lg bg-white/5 text-gray-500 cursor-not-allowed"
                @endif
                {{ $pages->onLastPage() ? 'disabled' : '' }}>
                Next
            </button>
        </div>
        <div class="text-center mt-4 text-sm text-gray-400">
            Showing {{ $pages->firstItem() ?? 0 }} to {{ $pages->lastItem() ?? 0 }} of {{ $pages->total() }} results
        </div>
    </div>
@endif
