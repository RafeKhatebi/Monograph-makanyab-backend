@props(['title' => null, 'compact' => false, 'flat' => false])

@php
    $classes = trim('mk-ui-card '.
        ($compact ? 'mk-ui-card--compact ' : '').
        ($flat ? 'mk-ui-card--flat ' : ''));
@endphp

<section {{ $attributes->merge(['class' => $classes]) }}>
    @if ($title)
        <h2 class="mk-heading mk-heading--md">{{ $title }}</h2>
    @endif

    {{ $slot }}
</section>
