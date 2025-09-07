@props([
    'pages' => [],
])

<x-request-analytics::stats.list primaryLabel="Pages" secondaryLabel="Views">
    @forelse($pages as $page)
        <x-request-analytics::stats.item
            label="{{ $page['path'] }}"
            count="{{ $page['views'] }}"
            percentage="{{ $page['percentage'] }}"
        />
    @empty
        <p class="text-sm text-gray-500 text-center py-5">No pages</p>
    @endforelse
</x-request-analytics::stats.list>
