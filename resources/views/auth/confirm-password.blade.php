@extends('layouts.auth')

@section('title', __('auth.ui.confirm_password_title'))

@section('content')
    <x-auth-card :title="__('auth.ui.confirm_password_title')">
        <p class="mk-text mk-text--muted">
            {{ __('auth.ui.confirm_password_intro') }}
        </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <x-form-field :label="__('auth.ui.password')" for="password" type="password" name="password"
            autocomplete="current-password" required />

        <div class="auth-card__actions">
            <x-primary-button class="w-full">
                {{ __('auth.ui.confirm') }}
            </x-primary-button>
        </div>
    </form>
    </x-auth-card>
@endsection
