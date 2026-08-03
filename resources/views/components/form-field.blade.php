@props([
    'label',
    'for',
    'name' => null,
    'type' => 'text',
    'value' => null,
    'autocomplete' => null,
    'required' => false,
    'autofocus' => false,
])

@php
    $fieldName = $name ?? $for;
@endphp

<x-ui.form-group :for="$for" :label="$label" :messages="$errors->get($fieldName)" :required="$required">
    <x-ui.text-input id="{{ $for }}" name="{{ $fieldName }}" type="{{ $type }}" :value="$value"
        autocomplete="{{ $autocomplete }}" :required="$required" :autofocus="$autofocus"
        :invalid="$errors->has($fieldName)" />
</x-ui.form-group>
