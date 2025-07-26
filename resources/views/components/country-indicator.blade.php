@if($jobCountry)
    @if($type === 'badge')
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $getIndicatorClasses() }}">
            {{ $getCountryFlag() }} {{ $getIndicatorText() }}
        </span>
    @elseif($type === 'icon')
        <span class="inline-flex items-center text-sm" title="{{ $getIndicatorText() }} - {{ $jobCountry }}">
            {{ $getCountryFlag() }}
        </span>
    @else
        <span class="text-sm text-gray-600">
            {{ $getCountryFlag() }} {{ $jobCountry }}
        </span>
    @endif
@endif 