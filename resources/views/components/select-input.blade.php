@props(['id', 'name', 'options' => [], 'placeholder' => null])

<x-ui.select id="{{ $id }}" :name="$name" :options="$options" :placeholder="$placeholder"
    :invalid="$errors->has($name)" {{ $attributes }} />
