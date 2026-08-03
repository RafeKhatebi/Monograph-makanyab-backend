@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'mk-ui-error']) }}>
        <ul>
            @foreach ((array) $messages as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
@endif
