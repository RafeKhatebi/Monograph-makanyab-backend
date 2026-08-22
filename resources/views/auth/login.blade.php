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

            <div class="form-group" style="margin-bottom: 25px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="remember" style="margin-right: 8px;">
                    <span style="color: #6B7280; font-size: 14px;">{{ __('auth.ui.remember') }}</span>
                </label>
            </div>

            <x-primary-button class="w-full text-center mb-5">
                {{ __('auth.ui.sign_in') }}
            </x-primary-button>

            <div style="text-align: center; margin-top: 20px;">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        style="color: #10B981; text-decoration: none; font-size: 14px;">
                        {{ __('auth.ui.forgot_password') }}
                    </a>
                @endif
            </div>

            <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <span style="color: #6B7280; font-size: 14px;">{{ __('auth.ui.no_account') }}</span>
                <a href="{{ route('register') }}"
                    style="color: #10B981; text-decoration: none; font-weight: 600; margin-left: 5px;">
                    {{ __('auth.ui.create_one') }}
                </a>
            </div>
        </form>
    </x-auth-card>

@endsection
