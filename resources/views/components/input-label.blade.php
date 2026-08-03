@props(['value'])

<label {{ $attributes->merge(['class' => 'mk-ui-label']) }}>
    {{ $value ?? $slot }}
</label>
