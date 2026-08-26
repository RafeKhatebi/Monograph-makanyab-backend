@extends('layouts.auth')

@section('title', 'Authentication Email Preview')

@section('content')
    <x-auth-card title="Authentication Email Preview">
        <p class="mk-text mk-text--muted">
            Preview authentication emails for local development.
        </p>

        <div class="auth-preview-actions">
            <a class="mk-btn mk-btn-primary mk-btn-block" href="{{ route('dev.auth-mail-preview.verification') }}">
                Preview Verification Email
            </a>

            <a class="mk-btn mk-btn-secondary mk-btn-block" href="{{ route('dev.auth-mail-preview.password-reset') }}">
                Preview Password Reset Email
            </a>
        </div>
    </x-auth-card>
@endsection
