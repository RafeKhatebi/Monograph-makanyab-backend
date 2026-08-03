@props(['name', 'multiple' => false])

<input type="file" name="{{ $name }}" @if ($multiple) multiple @endif
    {{ $attributes->merge(['class' => 'mk-ui-file']) }}>
