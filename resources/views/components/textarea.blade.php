@props(['id', 'name', 'value' => '', 'rows' => 4, 'placeholder' => ''])

@php
    $content = trim($slot) !== '' ? $slot : old($name, $value);
@endphp

<textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}"
    {{ $attributes->merge(['class' => 'border-gray-300 rounded-xl px-4 py-3 shadow-sm focus:border-primary focus:ring-primary w-full']) }}>{{ $content }}</textarea>
