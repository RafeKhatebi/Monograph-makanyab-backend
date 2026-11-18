@extends('layouts.auth')

@section('title', __('auth.ui.verify_email_title'))

@section('content')
    <x-auth-card :title="__('auth.ui.verify_email_title')" :description="__('auth.ui.verify_email_intro')">

        <p class="text-center mb-3">{{ request()->user()->email }}</p>

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
                <input id="otp_code" type="text" name="otp_code" class="form-control auth-otp-input" placeholder="{{ __('auth.ui.otp_code_placeholder') }}" inputmode="numeric" pattern="[0-9]{6}" autocomplete="one-time-code" maxlength="6" dir="ltr" required autofocus>
            </div>

            <button type="submit" class="mk-btn mk-btn-primary mk-btn-block">
                {{ __('auth.ui.verify_otp') }}
            </button>
        </form>

        <form method="POST" action="{{ route('verification.send') }}" class="mk-form mt-3" data-auth-form>
            @csrf

            <button type="submit" class="mk-btn mk-btn-secondary mk-btn-block" data-loading-text="{{ __('auth.ui.sending') }}" data-resend-button>
                {{ __('auth.ui.resend_verification') }}
            </button>
            <p class="text-center mt-2" data-resend-countdown hidden aria-live="polite"></p>
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
        (() => {
            const button = document.querySelector('[data-resend-button]');
            const countdown = document.querySelector('[data-resend-countdown]');
            if (!button || !countdown) return;

            const storageKey = 'verification-resend-available-at';
            const update = () => {
                const remaining = Math.max(0, Number(localStorage.getItem(storageKey)) - Date.now());
                if (!remaining) {
                    button.disabled = false;
                    countdown.hidden = true;
                    localStorage.removeItem(storageKey);
                    return;
                }

                button.disabled = true;
                countdown.hidden = false;
                countdown.textContent = `You can resend a code in ${Math.ceil(remaining / 1000)} seconds.`;
                window.setTimeout(update, 1000);
            };

            button.form.addEventListener('submit', () => {
                localStorage.setItem(storageKey, String(Date.now() + 60000));
            });
            update();
        })();
    </script>
@endpush
