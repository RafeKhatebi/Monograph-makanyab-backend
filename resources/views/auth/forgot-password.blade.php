@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <x-auth-card title="Forgot Password">
        <p class="mk-text mk-text--muted">
            Enter your email address and we will send password reset instructions.
        </p>

        <form method="POST" action="{{ route('password.email') }}" class="mk-form" data-auth-form>
            @csrf

            <x-form-field label="{{ __('Email') }}" for="email" type="email" name="email" :value="old('email')"
                autocomplete="username" required autofocus />

            <button type="submit" class="mk-btn mk-btn-primary mk-btn-block" data-loading-text="Sending...">
                Send Reset Instructions
            </button>
        </form>

        <p class="mk-auth-footer">
            Remember your password?
            <a href="{{ route('login') }}" class="mk-link">Sign in</a>
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
