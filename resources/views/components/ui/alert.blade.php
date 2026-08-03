@props(['variant' => 'info', 'role' => null])

@php
    $alertRole = $role ?? ($variant === 'danger' ? 'alert' : 'status');
@endphp

<div role="{{ $alertRole }}" {{ $attributes->merge(['class' => 'mk-ui-alert mk-ui-alert--'.$variant]) }}>
    {{ $slot }}
</div>
