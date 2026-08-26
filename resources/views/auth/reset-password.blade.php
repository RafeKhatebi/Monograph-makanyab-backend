@extends('layouts.auth')

@section('title', __('auth.ui.reset_password_title'))

@section('content')
    <x-auth-card :title="__('auth.ui.reset_password_title')">
        <form method="POST" action="{{ route('password.store') }}" class="mk-form" data-auth-form>
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <x-form-field :label="__('auth.ui.email')" for="email" type="email" name="email" :value="old('email', $request->email)"
                autocomplete="username" required autofocus />

            <x-form-field :label="__('auth.ui.password')" for="password" type="password" name="password"
                autocomplete="new-password" required />

            <x-form-field :label="__('auth.ui.confirm_password')" for="password_confirmation" type="password"
                name="password_confirmation" autocomplete="new-password" required />

            <button type="submit" class="mk-btn mk-btn-primary mk-btn-block" data-loading-text="{{ __('auth.ui.resetting') }}">
                {{ __('auth.ui.reset_password') }}
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
