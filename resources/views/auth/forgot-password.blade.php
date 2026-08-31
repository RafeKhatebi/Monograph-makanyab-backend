@extends('layouts.auth')

@section('title', __('auth.ui.forgot_password_title'))

@section('content')
    <x-auth-card :title="__('auth.ui.forgot_password_title')">
        <p class="mk-text mk-text--muted">
            {{ __('auth.ui.forgot_password_intro') }}
        </p>

        <form method="POST" action="{{ route('password.email') }}" class="mk-form" data-auth-form>
            @csrf

            <x-form-field :label="__('auth.ui.email')" for="email" type="email" name="email" :value="old('email')"
                autocomplete="username" required autofocus />

            <button type="submit" class="mk-btn mk-btn-primary mk-btn-block" data-loading-text="{{ __('auth.ui.sending') }}">
                {{ __('auth.ui.send_reset_instructions') }}
            </button>
        </form>

        <p class="mk-auth-footer">
            {{ __('auth.ui.remember_password') }}
            <a href="{{ route('login') }}" class="mk-link">{{ __('auth.ui.sign_in') }}</a>
        </p>
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
