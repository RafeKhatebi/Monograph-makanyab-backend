@props(['id', 'name', 'value' => '', 'rows' => 4, 'placeholder' => ''])

@php
    $content = trim($slot) !== '' ? $slot : old($name, $value);
@endphp

<textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}"
    {{ $attributes->merge(['class' => 'form-control']) }}>{{ $content }}</textarea>
