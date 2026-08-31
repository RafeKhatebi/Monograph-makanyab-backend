@extends('layouts.auth')

@section('title', $title)

@section('content')
    <x-auth-card :title="$title">
        <div class="mk-alert mk-alert--warning">
            Local development preview only. Do not share links from this page.
        </div>

        <div class="mk-card auth-preview-card">
            <h4 class="mk-heading mk-heading--sm">{{ $preview['subject'] }}</h4>

            @foreach ($preview['introLines'] as $line)
                <p class="mk-text mk-text--muted">{{ $line }}</p>
            @endforeach

            @if ($preview['actionText'] && $preview['actionUrl'])
                <p class="auth-preview-action">
                    <a class="mk-btn mk-btn-primary" href="{{ $preview['actionUrl'] }}">
                        {{ $preview['actionText'] }}
                    </a>
                </p>

                <p class="mk-text mk-text--muted auth-preview-url">
                    {{ $preview['actionUrl'] }}
                </p>
            @endif

            @foreach ($preview['outroLines'] as $line)
                <p class="mk-text mk-text--muted">{{ $line }}</p>
            @endforeach
        </div>

        <p class="auth-preview-back">
            <a class="mk-link" href="{{ route('dev.auth-mail-preview.index') }}">Back to previews</a>
        </p>
    </x-auth-card>
@endsection
