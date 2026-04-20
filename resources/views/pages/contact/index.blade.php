@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
    <!-- Main Content -->
    <div class="content-area" style="background:#F8FAFC; padding:70px 0;">
        <div class="container">

            <div class="row">

                <!-- Contact Info -->
                <div class="col-md-5" style="margin-bottom:30px;">
                    <div class="box-two" style="padding:40px; border-radius:16px; height:100%;">

                        <h2 style="font-size:30px; font-weight:700; color:#111827; margin-bottom:25px;">
                            Get in Touch
                        </h2>

                        <p style="font-size:16px; color:#6B7280; line-height:1.8; margin-bottom:35px;">
                            Need help with listings, business registration, technical issues, or general questions?
                            Our support team is ready to assist you.
                        </p>

                        <!-- Address -->
                        <div style="display:flex; margin-bottom:28px;">
                            <div
                                style="width:50px; height:50px; background:#ECFDF5; color:#10B981; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; margin-right:15px;">
                                📍
                            </div>
                            <div>
                                <h4 style="font-size:17px; font-weight:600; color:#111827; margin-bottom:5px;">Office
                                    Address</h4>
                                <p style="margin:0; color:#6B7280; line-height:1.7;">
                                    Herat, Afghanistan
                                </p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div style="display:flex; margin-bottom:28px;">
                            <div
                                style="width:50px; height:50px; background:#ECFDF5; color:#10B981; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; margin-right:15px;">
                                📞
                            </div>
                            <div>
                                <h4 style="font-size:17px; font-weight:600; color:#111827; margin-bottom:5px;">Call Us</h4>
                                <p style="margin:0;">
                                    <a href="tel:+93728958411" style="color:#10B981; text-decoration:none;">
                                        +93 728 958 411
                                    </a>
                                </p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div style="display:flex; margin-bottom:28px;">
                            <div
                                style="width:50px; height:50px; background:#ECFDF5; color:#10B981; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; margin-right:15px;">
                                ✉️
                            </div>
                            <div>
                                <h4 style="font-size:17px; font-weight:600; color:#111827; margin-bottom:5px;">Email Address
                                </h4>
                                <p style="margin:0;">
                                    <a href="mailto:info@makanyab.com" style="color:#10B981; text-decoration:none;">
                                        info@makanyab.com
                                    </a>
                                </p>
                            </div>
                        </div>

                        <!-- Hours -->
                        <div style="display:flex; margin-bottom:35px;">
                            <div
                                style="width:50px; height:50px; background:#ECFDF5; color:#10B981; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; margin-right:15px;">
                                🕒
                            </div>
                            <div>
                                <h4 style="font-size:17px; font-weight:600; color:#111827; margin-bottom:5px;">Working Hours
                                </h4>
                                <p style="margin:0; color:#6B7280; line-height:1.7;">
                                    Saturday - Thursday<br>
                                    9:00 AM - 6:00 PM
                                </p>
                            </div>
                        </div>

                        <!-- Social -->
                        <div style="border-top:1px solid #E5E7EB; padding-top:25px;">
                            <h4 style="font-size:16px; font-weight:600; color:#111827; margin-bottom:15px;">Follow Us</h4>

                            <div style="display:flex; gap:12px;">
                                <a href="#"
                                    style="width:42px; height:42px; background:#10B981; color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; text-decoration:none;">f</a>

                                <a href="#"
                                    style="width:42px; height:42px; background:#10B981; color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; text-decoration:none;">in</a>

                                <a href="#"
                                    style="width:42px; height:42px; background:#10B981; color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; text-decoration:none;">t</a>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Form -->
                <div class="col-md-7">
                    <div class="box-two" style="padding:40px; border-radius:16px;">

                        <h2 style="font-size:30px; font-weight:700; color:#111827; margin-bottom:30px;">
                            Send Message
                        </h2>

                        @if (session('success'))
                            <div
                                style="padding:15px 18px; background:#ECFDF5; border:1px solid #A7F3D0; color:#065F46; border-radius:10px; margin-bottom:25px;">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom:20px;">
                                        <label style="font-weight:600; margin-bottom:8px; display:block;">Full Name
                                            *</label>
                                        <input type="text" name="name" value="{{ old('name') }}" required
                                            class="form-control"
                                            style="height:50px; border-radius:10px; border:1px solid #D1D5DB;">
                                        @error('name')
                                            <small style="color:#DC2626;">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom:20px;">
                                        <label style="font-weight:600; margin-bottom:8px; display:block;">Phone *</label>
                                        <input type="text" name="telephone" value="{{ old('telephone') }}" required
                                            class="form-control"
                                            style="height:50px; border-radius:10px; border:1px solid #D1D5DB;">
                                        @error('telephone')
                                            <small style="color:#DC2626;">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom:20px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Email *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="form-control" style="height:50px; border-radius:10px; border:1px solid #D1D5DB;">
                                @error('email')
                                    <small style="color:#DC2626;">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group" style="margin-bottom:20px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Subject</label>
                                <input type="text" name="subject" value="{{ old('subject') }}" class="form-control"
                                    style="height:50px; border-radius:10px; border:1px solid #D1D5DB;">
                            </div>

                            <div class="form-group" style="margin-bottom:25px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Message *</label>
                                <textarea name="message" rows="6" required class="form-control"
                                    style="border-radius:10px; border:1px solid #D1D5DB; resize:none;">{{ old('message') }}</textarea>
                                @error('message')
                                    <small style="color:#DC2626;">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit"
                                style="background:#10B981; color:#fff; border:none; padding:14px 35px; font-size:16px; font-weight:600; border-radius:10px;">
                                Send Message
                            </button>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
