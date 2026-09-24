@extends('layouts.auth')

@section('title', __('auth.ui.login'))

@section('content')

    <x-auth-card :title="__('auth.ui.welcome')">
        @include('auth.partials.social-buttons')

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <x-form-field :label="__('auth.ui.email')" for="email" type="email" name="email" :value="old('email')" autocomplete="username"
                required autofocus />

            <x-form-field :label="__('auth.ui.password')" for="password" type="password" name="password" autocomplete="current-password"
                required />

            <div class="form-group auth-inline-control">
                <label class="auth-check">
                    <input type="checkbox" name="remember">
                    <span>{{ __('auth.ui.remember') }}</span>
                </label>
            </div>

            <x-primary-button class="w-full text-center mb-4">
                {{ __('auth.ui.sign_in') }}
            </x-primary-button>

            <div class="auth-card__link-row">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        {{ __('auth.ui.forgot_password') }}
                    </a>
                @endif
            </div>

            <div class="auth-card__footer">
                <span>{{ __('auth.ui.no_account') }}</span>
                <a href="{{ route('register') }}">
                    {{ __('auth.ui.create_one') }}
                </a>
            </div>
        </form>
    </x-auth-card>

@endsection
