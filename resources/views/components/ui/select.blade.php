@props(['name', 'options' => [], 'placeholder' => null, 'selected' => null, 'invalid' => false])

<select name="{{ $name }}" aria-invalid="{{ $invalid ? 'true' : 'false' }}"
    {{ $attributes->merge(['class' => 'mk-ui-select']) }}>
    @if ($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif

    @foreach ($options as $value => $label)
        <option value="{{ $value }}" @selected((string) old($name, $selected) === (string) $value)>
            {{ $label }}
        </option>
    @endforeach
</select>
