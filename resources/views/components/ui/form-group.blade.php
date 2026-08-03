@props(['for' => null, 'label' => null, 'messages' => [], 'required' => false])

<div {{ $attributes->merge(['class' => 'mk-ui-form-group']) }}>
    @if ($label)
        <label @if ($for) for="{{ $for }}" @endif class="mk-ui-label">
            {{ $label }}
            @if ($required)
                <span class="mk-ui-required" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    {{ $slot }}

    @if ($messages)
        <x-ui.input-error :messages="$messages" />
    @endif
</div>
