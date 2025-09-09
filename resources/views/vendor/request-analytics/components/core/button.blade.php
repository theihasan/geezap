@props([
    'color' => 'primary',
    'type' => 'button',
])

<button type="{{ $type }}"
    @class([
    "inline-flex items-center justify-center text-sm font-medium tracking-wide transition-colors duration-100 rounded-md px-5 py-2.5 h-10",
        'bg-blue-100 text-blue-500 hover:bg-blue-200' => $color === 'primary',
        'bg-gray-100 text-gray-500 hover:bg-gray-200' => $color === 'secondary',
        'bg-red-100 text-red-500 hover:bg-red-200' => $color === 'danger',
        'bg-yellow-100 text-yellow-500 hover:bg-yellow-200' => $color === 'warning',
        'bg-green-100 text-green-500 hover:bg-green-200' => $color === 'success',
    ])
>
    {{ $slot }}
</button>
