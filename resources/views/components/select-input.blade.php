@props(['id', 'name', 'options' => [], 'placeholder' => null, 'selected' => null])

<x-ui.select id="{{ $id }}" :name="$name" :options="$options" :placeholder="$placeholder"
    :selected="$selected" :invalid="$errors->has($name)" {{ $attributes }} />
