@props(['id', 'name', 'options' => [], 'placeholder' => null])

<select id="{{ $id }}" name="{{ $name }}" {{ $attributes->merge(['class' => 'form-control']) }}>
    @if ($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif
    @foreach ($options as $value => $label)
        <option value="{{ $value }}" {{ old($name) == $value ? 'selected' : '' }}>{{ $label }}</option>
    @endforeach
</select>
