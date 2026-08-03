@props(['variant' => 'neutral'])

<span {{ $attributes->merge(['class' => 'mk-ui-badge mk-ui-badge--'.$variant]) }}>
    {{ $slot }}
</span>
