@extends('layouts.auth')

@section('title', __('auth.ui.verify_email_title'))

@section('content')
    <x-auth-card :title="__('auth.ui.verify_email_title')">
        <p class="mk-text mk-text--muted mk-stack-sm">
            {{ __('auth.ui.verify_email_intro') }}
        </p>

        @if (session('status') && !$errors->any())
            <div class="mk-alert mk-alert--success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mk-alert flash-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('verification.otp.verify') }}" class="mk-form" data-auth-form>
            @csrf

            <div class="form-group">
                <label for="otp_code" class="form-label">{{ __('auth.ui.otp_code_label') }}</label>
                <input id="otp_code" type="text" name="otp_code" class="form-control" placeholder="{{ __('auth.ui.otp_code_placeholder') }}" maxlength="6" required autofocus>
            </div>

            <button type="submit" class="mk-btn mk-btn-primary mk-btn-block">
                {{ __('auth.ui.verify_otp') }}
            </button>
        </form>

        <form method="POST" action="{{ route('verification.send') }}" class="mk-form mt-3" data-auth-form>
            @csrf

            <button type="submit" class="mk-btn mk-btn-secondary mk-btn-block" data-loading-text="{{ __('auth.ui.sending') }}">
                {{ __('auth.ui.resend_verification') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="auth-card__link-row mt-3">
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
