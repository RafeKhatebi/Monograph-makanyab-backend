@extends('layouts.auth')

@section('title', 'Register')

@section('content')

<x-auth-card title="Join Makanyab Today">
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group" style="margin-bottom: 20px;">
            <label style="font-weight: 500; color: #374151; margin-bottom: 8px; display: block;">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus class="form-control"
                style="padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px;">
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label style="font-weight: 500; color: #374151; margin-bottom: 8px; display: block;">User Name</label>
            <input type="text" name="username" value="{{ old('username') }}" required class="form-control"
                style="padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px;">
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label style="font-weight: 500; color: #374151; margin-bottom: 8px; display: block;">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="form-control"
                style="padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px;">
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label style="font-weight: 500; color: #374151; margin-bottom: 8px; display: block;">Password</label>
            <input type="password" name="password" required class="form-control"
                style="padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px;">
        </div>

        <div class="form-group" style="margin-bottom: 25px;">
            <label style="font-weight: 500; color: #374151; margin-bottom: 8px; display: block;">Confirm Password</label>
            <input type="password" name="password_confirmation" required class="form-control"
                style="padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px;">
        </div>

        <button type="submit" class="btn btn-block"
            style="background: #10B981; color: #FFFFFF; padding: 14px; font-weight: 600; border-radius: 8px; border: none; font-size: 16px; margin-bottom: 20px;">
            Create Account
        </button>

        <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <span style="color: #6B7280; font-size: 14px;">Already have an account?</span>
            <a href="{{ route('login') }}" style="color: #10B981; text-decoration: none; font-weight: 600; margin-left: 5px;">
                Login here
            </a>
        </div>
    </form>
</x-auth-card>

@endsection
