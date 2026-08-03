@props(['name', 'label', 'value', 'checked' => false])

<label {{ $attributes->merge(['class' => 'mk-ui-check']) }}>
    <input type="radio" name="{{ $name }}" value="{{ $value }}" @checked((string) old($name, $checked ? $value : null) === (string) $value)>
    <span>{{ $label }}</span>
</label>
