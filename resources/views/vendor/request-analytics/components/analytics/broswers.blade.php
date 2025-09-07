@props([
    'browsers' => [],
])

@php
    if (!function_exists('getBrowserImage')) {
        function getBrowserImage($browser): string {
            return match(strtolower($browser)){
                'chrome' =>  asset('browsers/chrome.png'),
                'firefox' => asset('browsers/firefox.png'),
                'safari' => asset('browsers/safari.png'),
                'edge' => asset('browsers/ms-edge.png'),
                default => asset('browsers/unknown.png'),
            };
        }
    }
@endphp

<x-request-analytics::stats.list primaryLabel="Browser" secondaryLabel="Visitors">
    @forelse($browsers as $browser)
        <x-request-analytics::stats.item
            label="{{ $browser['browser'] }}"
            count="{{ $browser['count'] }}"
            percentage="{{ $browser['percentage'] }}"
            imgSrc="{{ getBrowserImage($browser['browser']) }}"
        />
    @empty
        <p class="text-sm text-gray-500 text-center py-5">No browsers</p>
    @endforelse
</x-request-analytics::stats.list>
