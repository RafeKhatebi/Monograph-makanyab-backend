@props(['name', 'label', 'checked' => false, 'value' => '1'])

<label {{ $attributes->merge(['class' => 'mk-ui-check']) }}>
    <input type="checkbox" name="{{ $name }}" value="{{ $value }}" @checked(old($name, $checked))>
    <span>{{ $label }}</span>
</label>
