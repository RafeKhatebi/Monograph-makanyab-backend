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
        class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary"
    />

    <x-input-error :messages="$errors->get($name ?? $for)" class="mt-2" />
</div>
