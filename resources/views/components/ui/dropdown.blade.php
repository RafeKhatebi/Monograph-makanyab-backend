@props(['id' => null])

<div {{ $attributes->merge(['class' => 'mk-ui-dropdown']) }} @if ($id) id="{{ $id }}" @endif>
    {{ $slot }}
</div>
