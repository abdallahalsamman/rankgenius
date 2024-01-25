@if ($enabled)
    <x-toggle
        checked
        {{ $attributes }}
    />
@else
    <x-toggle
        {{ $attributes }}
    />
@endif
