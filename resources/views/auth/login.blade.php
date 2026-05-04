@extends('layouts.auth')

@section('title', 'Login')

@section('content')

    <x-auth-card title="Welcome Back">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <x-form-field label="Email" for="email" type="email" name="email" :value="old('email')" autocomplete="username"
                required autofocus />

            <x-form-field label="Password" for="password" type="password" name="password" autocomplete="current-password"
                required />

            <div class="form-group" style="margin-bottom: 25px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="remember" style="margin-right: 8px;">
                    <span style="color: #6B7280; font-size: 14px;">Remember me</span>
                </label>
            </div>

            <x-primary-button class="w-full text-center mb-5">
                Login
            </x-primary-button>

            <div style="text-align: center; margin-top: 20px;">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        style="color: #10B981; text-decoration: none; font-size: 14px;">
                        Forgot your password?
                    </a>
                @endif
            </div>

            <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <span style="color: #6B7280; font-size: 14px;">Don't have an account?</span>
                <a href="{{ route('register') }}"
                    style="color: #10B981; text-decoration: none; font-weight: 600; margin-left: 5px;">
                    Register here
                </a>
            </div>
        </form>
    </x-auth-card>

@endsection
