@extends('layouts.auth')

@section('title', __('auth.ui.verify_email_title'))

@section('content')
    <x-auth-card :title="__('auth.ui.verify_email_title')">
        <p class="mk-text mk-text--muted">
            {{ __('auth.ui.verify_email_intro') }}
        </p>

        <form method="POST" action="{{ route('verification.send') }}" class="mk-form" data-auth-form>
            @csrf

            <button type="submit" class="mk-btn mk-btn-primary mk-btn-block" data-loading-text="{{ __('auth.ui.sending') }}">
                {{ __('auth.ui.resend_verification') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="auth-card__link-row">
            @csrf

            <button type="submit" class="mk-link mk-link-button">
                {{ __('auth.ui.sign_out') }}
            </button>
        </form>
    </x-auth-card>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-auth-form]').forEach(function(form) {
            form.addEventListener('submit', function() {
                var button = form.querySelector('[data-loading-text]');

                if (!button) {
                    return;
                }

                button.dataset.defaultText = button.textContent.trim();
                button.textContent = button.dataset.loadingText;
                button.disabled = true;
            });
        });
    </script>
@endpush
