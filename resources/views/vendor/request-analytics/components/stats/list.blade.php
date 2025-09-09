@props([
    'primaryLabel',
    'secondaryLabel',
    'footer' => null
])

<div class="p-6 min-h-[400px] flex flex-col">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">{{ $primaryLabel }}</h3>
        <span class="text-sm font-medium text-gray-500">{{ $secondaryLabel }}</span>
    </div>
    <div class="flex-1 flex flex-col space-y-3">
        {{ $slot }}
    </div>
    @if($footer)
        <div class="mt-4 pt-4 border-t border-gray-100">
            {{ $footer }}
        </div>
    @endif
</div>
