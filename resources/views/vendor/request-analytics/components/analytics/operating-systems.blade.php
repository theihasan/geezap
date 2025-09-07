@props([
    'operatingSystems' => [],
])

@php
    if (!function_exists('getOperatingSystemImage')) {
        function getOperatingSystemImage($os): string {
            return match(strtolower($os)){
                'windows' => asset('operating-systems/windows-logo.png'),
                'linux' => asset('operating-systems/linux.png'),
                'macos' => asset('operating-systems/mac-logo.png'),
                'android' => asset('operating-systems/android-os.png'),
                'ios' => asset('operating-systems/iphone.png'),
                default => asset('operating-systems/unknown.png'),
            };
        }
    }
@endphp
<x-request-analytics::stats.list primaryLabel="Os" secondaryLabel="Visitors">
    @forelse($operatingSystems as $os)
        <x-request-analytics::stats.item
            label="{{ $os['name'] }}"
            count="{{ $os['count'] }}"
            percentage="{{ $os['percentage'] }}"
            imgSrc="{{ getOperatingSystemImage($os['name']) }}"
        />
    @empty
        <p class="text-sm text-gray-500 text-center py-5">No operating systems</p>
    @endforelse
</x-request-analytics::stats.list>
