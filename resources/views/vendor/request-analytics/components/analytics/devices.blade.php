@props([
    'devices' => [],
])

@php
    if (!function_exists('getDeviceImage')) {
        function getDeviceImage($device): string {
            return match(strtolower($device)){
                'mobile' => asset('devices/smartphone.png'),
                'tablet' => asset('devices/ipad.png'),
                'desktop' => asset('devices/laptop.png'),
                'tv' => asset('devices/tv.png'),
                default => asset('devices/unknown.png'),
            };
        }
    }
@endphp
<x-request-analytics::stats.list primaryLabel="Devices" secondaryLabel="Visitors">
    @forelse($devices as $device)
        <x-request-analytics::stats.item
            label="{{ $device['name'] }}"
            count="{{ $device['count'] }}"
            percentage="{{ $device['percentage'] }}"
            imgSrc="{{ getDeviceImage($device['name']) }}"
        />
    @empty
        <p class="text-sm text-gray-500 text-center py-5">No devices</p>
    @endforelse
</x-request-analytics::stats.list>
