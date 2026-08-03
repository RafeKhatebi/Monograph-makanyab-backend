@extends('layouts.auth')

@section('title', $title)

@section('content')
    <x-auth-card :title="$title">
        <div class="mk-alert mk-alert--warning">
            Local development preview only. Do not share links from this page.
        </div>

        <div class="mk-card" style="box-shadow: none; border: 1px solid var(--mk-border); margin-top: 20px;">
            <h4 class="mk-heading mk-heading--sm">{{ $preview['subject'] }}</h4>

            @foreach ($preview['introLines'] as $line)
                <p class="mk-text mk-text--muted">{{ $line }}</p>
            @endforeach

            @if ($preview['actionText'] && $preview['actionUrl'])
                <p style="margin: 20px 0;">
                    <a class="mk-btn mk-btn-primary" href="{{ $preview['actionUrl'] }}">
                        {{ $preview['actionText'] }}
                    </a>
                </p>

                <p class="mk-text mk-text--muted" style="word-break: break-all;">
                    {{ $preview['actionUrl'] }}
                </p>
            @endif

            @foreach ($preview['outroLines'] as $line)
                <p class="mk-text mk-text--muted">{{ $line }}</p>
            @endforeach
        </div>

        <p style="margin-top: 20px;">
            <a class="mk-link" href="{{ route('dev.auth-mail-preview.index') }}">Back to previews</a>
        </p>
    </x-auth-card>
@endsection
