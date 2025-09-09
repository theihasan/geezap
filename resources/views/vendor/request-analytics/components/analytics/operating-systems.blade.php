@props([
    'operatingSystems' => [],
])

@php
    if (!function_exists('getOperatingSystemImage')) {
        function getOperatingSystemImage($os): string {
            $os = str_replace(' ', '', strtolower($os));
            return match($os){
                'windows' => asset('vendor/request-analytics/operating-systems/windows-logo.png'),
                'linux' => asset('vendor/request-analytics/operating-systems/linux.png'),
                'macos', 'macosx' => asset('vendor/request-analytics/operating-systems/mac-logo.png'),
                'android' => asset('vendor/request-analytics/operating-systems/android-os.png'),
                'ios' => asset('vendor/request-analytics/operating-systems/iphone.png'),
                default => asset('vendor/request-analytics/operating-systems/unknown.png'),
            };
        }
    }
@endphp
<x-request-analytics::stats.list primaryLabel="Operating Systems" secondaryLabel="">
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
