@props([
    'label',
    'value' => 0,
    'badgeText' => null,
    'badgeColor' => 'green',
])

<div class="text-center sm:text-left">
    <div class="text-3xl font-bold text-gray-900 mb-1">{{ $value }}</div>
    <div class="flex items-center justify-center sm:justify-start gap-2">
        <p class="text-sm font-medium text-gray-600">{{ $label }}</p>
        @if (!is_null($badgeText))
            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-{{ $badgeColor }}-100 text-{{ $badgeColor }}-700">
                {{ $badgeText }}
            </span>
        @endif
    </div>
</div>
