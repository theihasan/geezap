@props([
    'referrers' => [],
])

<x-request-analytics::stats.list primaryLabel="Referrers" secondaryLabel="Views">
    @forelse($referrers as $referrer)
        <x-request-analytics::stats.item
            label="{{ $referrer['domain'] }}"
            count="{{ $referrer['visits'] }}"
            percentage="{{ $referrer['percentage'] }}"
        />
    @empty
        <p class="text-sm text-gray-500 text-center py-5">No referrers</p>
    @endforelse
</x-request-analytics::stats.list>
