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
    @if ($type === 'password')
        <div class="auth-password-field">
            <x-ui.text-input id="{{ $for }}" name="{{ $fieldName }}" type="password" :value="$value"
                autocomplete="{{ $autocomplete }}" :required="$required" :autofocus="$autofocus"
                :invalid="$errors->has($fieldName)" data-password-input {{ $attributes }} />
            <button type="button" class="auth-password-toggle" data-password-toggle aria-label="Show password"
                aria-controls="{{ $for }}" aria-pressed="false">
                <i class="fa fa-eye" aria-hidden="true"></i>
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
            </button>
        </div>
    @else
        <x-ui.text-input id="{{ $for }}" name="{{ $fieldName }}" type="{{ $type }}" :value="$value"
            autocomplete="{{ $autocomplete }}" :required="$required" :autofocus="$autofocus"
            :invalid="$errors->has($fieldName)" {{ $attributes }} />
    @endif
</x-ui.form-group>
