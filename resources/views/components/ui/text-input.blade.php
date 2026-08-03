@props(['disabled' => false, 'invalid' => false])

<input @disabled($disabled) aria-invalid="{{ $invalid ? 'true' : 'false' }}"
    {{ $attributes->merge(['class' => 'mk-ui-input']) }}>
