@props(['title', 'message' => null, 'action' => null])

<div {{ $attributes->merge(['class' => 'mk-ui-empty']) }}>
    <h2 class="mk-ui-empty__title">{{ $title }}</h2>

    @if ($message)
        <p class="mk-ui-empty__text">{{ $message }}</p>
    @endif

    @if ($action ?? false)
        <div style="margin-top: 20px;">
            {{ $action }}
        </div>
    @endif
</div>
