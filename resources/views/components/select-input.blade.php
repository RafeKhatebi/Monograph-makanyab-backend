@props(['id', 'name', 'options' => [], 'placeholder' => null])

<select id="{{ $id }}" name="{{ $name }}"
    {{ $attributes->merge(['class' => 'border-gray-300 rounded-xl px-4 py-3 shadow-sm focus:border-primary focus:ring-primary w-full']) }}>
    @if($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif
    @foreach($options as $value => $label)
        <option value="{{ $value }}" {{ old($name) == $value ? 'selected' : '' }}>{{ $label }}</option>
    @endforeach
</select>
