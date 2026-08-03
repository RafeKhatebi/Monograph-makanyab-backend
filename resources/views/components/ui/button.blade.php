@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'block' => false,
    'icon' => false,
])

@php
    $classes = trim('mk-ui-button mk-ui-button--'.$variant.' mk-ui-button--'.$size.' '.
        ($block ? 'mk-ui-button--block ' : '').
        ($icon ? 'mk-ui-button--icon ' : ''));
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
