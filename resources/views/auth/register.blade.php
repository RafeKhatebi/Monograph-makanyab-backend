@extends('layouts.auth')

@section('title', 'Register')

@section('content')

<div class="content-area" style="background-color: #FCFCFC; padding: 55px 0;">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box-two" style="padding: 40px;">
                    <h3 style="font-size: 24px; font-weight: 600; color: #111827; margin-bottom: 25px; text-align: center;">
                        Join Makanyab Today
                    </h3>

                    @if ($errors->any())
                        <div style="padding: 15px; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; border-radius: 8px; margin-bottom: 25px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="font-weight: 500; color: #374151; margin-bottom: 8px; display: block;">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                                class="form-control" style="padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px;">
                        </div>
                        <div class="form-group" style="margin-bottom:20px;">
                            <label style="font-weight:500; color: #374151; margin-bottom:8px; display:block;">User Name</label>
                            <input type="text" name="username" value="{{ old('username') }}" required autofocus class="form-control" style="padding:12px 16px; border:1px solid #d1d5db; border-radius:8px; font-size:15px;">
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="font-weight: 500; color: #374151; margin-bottom: 8px; display: block;">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="form-control" style="padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px;">
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="font-weight: 500; color: #374151; margin-bottom: 8px; display: block;">Password</label>
                            <input type="password" name="password" required
                                class="form-control" style="padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px;">
                        </div>

                        <div class="form-group" style="margin-bottom: 25px;">
                            <label style="font-weight: 500; color: #374151; margin-bottom: 8px; display: block;">Confirm Password</label>
                            <input type="password" name="password_confirmation" required
                                class="form-control" style="padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px;">
                        </div>

                        <button type="submit" class="btn btn-block" style="background: #10B981; color: #FFFFFF; padding: 14px; font-weight: 600; border-radius: 8px; border: none; font-size: 16px; margin-bottom: 20px;">
                            Create Account
                        </button>

                        <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                            <span style="color: #6B7280; font-size: 14px;">Already have an account?</span>
                            <a href="{{ route('login') }}" style="color: #10B981; text-decoration: none; font-weight: 600; margin-left: 5px;">
                                Login here
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
