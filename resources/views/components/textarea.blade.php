@props(['id', 'name', 'value' => '', 'rows' => 4, 'placeholder' => ''])

<x-ui.textarea id="{{ $id }}" :name="$name" :value="$value" :rows="$rows" :placeholder="$placeholder"
    :invalid="$errors->has($name)" {{ $attributes }}>
    {{ $slot }}
</x-ui.textarea>
