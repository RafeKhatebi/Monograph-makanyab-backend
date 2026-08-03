@props(['name', 'value' => '', 'rows' => 4, 'placeholder' => '', 'invalid' => false])

@php
    $content = trim($slot) !== '' ? $slot : old($name, $value);
@endphp

<textarea name="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}"
    aria-invalid="{{ $invalid ? 'true' : 'false' }}"
    {{ $attributes->merge(['class' => 'mk-ui-textarea']) }}>{{ $content }}</textarea>
