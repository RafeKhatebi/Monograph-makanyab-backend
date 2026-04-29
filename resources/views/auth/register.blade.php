@extends('layouts.auth')

@section('title', 'Register')

@section('content')

<x-auth-card title="Join Makanyab Today">
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <x-form-field
            label="Name"
            for="name"
            name="name"
            :value="old('name')"
            autocomplete="name"
            required
            autofocus
        />

        <x-form-field
            label="User Name"
            for="username"
            name="username"
            :value="old('username')"
            autocomplete="username"
            required
        />

        <x-form-field
            label="Email"
            for="email"
            type="email"
            name="email"
            :value="old('email')"
            autocomplete="email"
            required
        />

        <x-form-field
            label="Password"
            for="password"
            type="password"
            name="password"
            autocomplete="new-password"
            required
        />

        <x-form-field
            label="Confirm Password"
            for="password_confirmation"
            type="password"
            name="password_confirmation"
            autocomplete="new-password"
            required
        />

        <x-primary-button class="w-full text-center mb-5">
            Create Account
        </x-primary-button>

        <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <span style="color: #6B7280; font-size: 14px;">Already have an account?</span>
            <a href="{{ route('login') }}" style="color: #10B981; text-decoration: none; font-weight: 600; margin-left: 5px;">
                Login here
            </a>
        </div>
    </form>
</x-auth-card>

@endsection
