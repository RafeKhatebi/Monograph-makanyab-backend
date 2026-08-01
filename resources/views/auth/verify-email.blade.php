@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
    <x-auth-card title="Verify Your Email">
        <p class="mk-text mk-text--muted">
            A verification email has been sent. Please check your inbox to continue.
        </p>

        <form method="POST" action="{{ route('verification.send') }}" class="mk-form" data-auth-form>
            @csrf

            <button type="submit" class="mk-btn mk-btn-primary mk-btn-block" data-loading-text="Sending...">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" style="margin-top: 16px;">
            @csrf

            <button type="submit" class="mk-link mk-link-button">
                Sign out
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
