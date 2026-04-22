@extends('layouts.auth')

@section('title', 'Login')

@section('content')

    <div class="content-area" style="background-color: #FCFCFC; padding: 55px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="box-two" style="padding: 40px;">
                        <h3
                            style="font-size: 24px; font-weight: 600; color: #111827; margin-bottom: 25px; text-align: center;">
                            Welcome Back
                        </h3>

                        @if (session('status'))
                            <div
                                style="padding: 15px; background: #ecfdf5; border: 1px solid #d1fae5; color: #065f46; border-radius: 8px; margin-bottom: 25px;">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div
                                style="padding: 15px; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; border-radius: 8px; margin-bottom: 25px;">
                                <ul style="margin: 0; padding-left: 20px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group" style="margin-bottom: 20px;">
                                <label
                                    style="font-weight: 500; color: #374151; margin-bottom: 8px; display: block;">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                    class="form-control"
                                    style="padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px;">
                            </div>

                            <div class="form-group" style="margin-bottom: 20px;">
                                <label
                                    style="font-weight: 500; color: #374151; margin-bottom: 8px; display: block;">Password</label>
                                <input type="password" name="password" required class="form-control"
                                    style="padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px;">
                            </div>

                            <div class="form-group" style="margin-bottom: 25px;">
                                <label style="display: flex; align-items: center; cursor: pointer;">
                                    <input type="checkbox" name="remember" style="margin-right: 8px;">
                                    <span style="color: #6B7280; font-size: 14px;">Remember me</span>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-block"
                                style="background: #10B981; color: #FFFFFF; padding: 14px; font-weight: 600; border-radius: 8px; border: none; font-size: 16px; margin-bottom: 20px;">
                                Login
                            </button>

                            <div style="text-align: center; margin-top: 20px;">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        style="color: #10B981; text-decoration: none; font-size: 14px;">
                                        Forgot your password?
                                    </a>
                                @endif
                            </div>

                            <div
                                style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                                <span style="color: #6B7280; font-size: 14px;">Don't have an account?</span>
                                <a href="{{ route('register') }}"
                                    style="color: #10B981; text-decoration: none; font-weight: 600; margin-left: 5px;">
                                    Register here
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
