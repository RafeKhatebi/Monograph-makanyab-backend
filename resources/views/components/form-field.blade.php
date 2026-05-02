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

<div class="form-group" style="margin-bottom: 20px;">
    <x-input-label :for="$for" :value="$label" />

    <x-text-input
        id="{{ $for }}"
        name="{{ $name ?? $for }}"
        type="{{ $type }}"
        :value="$value"
        autocomplete="{{ $autocomplete }}"
        :required="$required"
        :autofocus="$autofocus"
        class="form-control mt-2"
    />

    <x-input-error :messages="$errors->get($name ?? $for)" class="mt-2" />
</div>
