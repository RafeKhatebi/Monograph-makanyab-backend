@extends('layouts.auth')

@section('title', __('auth.ui.register'))

@section('content')

    <x-auth-card :title="__('auth.ui.join')">
        @include('auth.partials.social-buttons', ['context' => 'register'])

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <x-form-field :label="__('validation.attributes.name')" for="name" name="name" :value="old('name')" autocomplete="name" required
                autofocus />

            <x-form-field :label="__('validation.attributes.username')" for="username" name="username" :value="old('username')" autocomplete="username"
                required />

            <x-form-field :label="__('auth.ui.email')" for="email" type="email" name="email" :value="old('email')" autocomplete="email"
                required />

            <x-form-field :label="__('auth.ui.password')" for="password" type="password" name="password" autocomplete="new-password"
                required />

            <x-form-field :label="__('auth.ui.confirm_password')" for="password_confirmation" type="password" name="password_confirmation"
                autocomplete="new-password" required />

            <x-primary-button class="w-full text-center mb-5">
                {{ __('auth.ui.create_account') }}
            </x-primary-button>

            <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <span style="color: #6B7280; font-size: 14px;">{{ __('auth.ui.has_account') }}</span>
                <a href="{{ route('login') }}"
                    style="color: #10B981; text-decoration: none; font-weight: 600; margin-left: 5px;">
                    {{ __('auth.ui.sign_in') }}
                </a>
            </div>
        </form>
    </x-auth-card>

@endsection
