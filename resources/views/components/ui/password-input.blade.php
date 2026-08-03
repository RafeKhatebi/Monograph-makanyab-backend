@props(['disabled' => false, 'invalid' => false])

<x-ui.text-input type="password" :disabled="$disabled" :invalid="$invalid" {{ $attributes }} />
