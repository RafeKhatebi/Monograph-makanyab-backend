@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
    <div class="content-area" style="background:#F8FAFC; padding:70px 0;">
        <div class="container">
            <div class="row" style="margin-bottom:40px;">
                <div class="col-md-12">
                    <div class="box-two" style="padding:40px; border-radius:16px; text-align:center;">
                        <h1 style="font-size:38px; font-weight:700; margin-bottom:15px; color:#111827;">Contact Us</h1>
                        <p style="font-size:17px; color:#6B7280; line-height:1.8; max-width:760px; margin:0 auto;">
                            Have a question about listings, support, or partnerships? Reach out and our team will respond
                            quickly with the information you need.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-5" style="margin-bottom:30px;">
                    <div class="box-two" style="padding:40px; border-radius:16px; height:100%;">
                        <h2 style="font-size:30px; font-weight:700; color:#111827; margin-bottom:20px;">Contact Information</h2>
                        <p style="font-size:16px; color:#6B7280; line-height:1.8; margin-bottom:35px;">
                            We are here to help business owners and users get the best local experience. Use the form to send
                            your message or contact us directly through phone or email.
                        </p>

                        <div style="display:flex; align-items:flex-start; margin-bottom:28px;">
                            <div style="width:52px; height:52px; background:#ECFDF5; color:#10B981; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:22px; margin-right:16px;">📍</div>
                            <div>
                                <h4 style="font-size:17px; font-weight:600; color:#111827; margin-bottom:6px;">Office Address</h4>
                                <p style="margin:0; color:#6B7280; line-height:1.7;">Herat, Afghanistan</p>
                            </div>
                        </div>

                        <div style="display:flex; align-items:flex-start; margin-bottom:28px;">
                            <div style="width:52px; height:52px; background:#ECFDF5; color:#10B981; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:22px; margin-right:16px;">📞</div>
                            <div>
                                <h4 style="font-size:17px; font-weight:600; color:#111827; margin-bottom:6px;">Phone</h4>
                                <p style="margin:0; color:#6B7280; line-height:1.7;"><a href="tel:+93728958411" style="color:#10B981; text-decoration:none;">+93 728 958 411</a></p>
                            </div>
                        </div>

                        <div style="display:flex; align-items:flex-start; margin-bottom:28px;">
                            <div style="width:52px; height:52px; background:#ECFDF5; color:#10B981; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:22px; margin-right:16px;">✉️</div>
                            <div>
                                <h4 style="font-size:17px; font-weight:600; color:#111827; margin-bottom:6px;">Email</h4>
                                <p style="margin:0; color:#6B7280; line-height:1.7;"><a href="mailto:info@makanyab.com" style="color:#10B981; text-decoration:none;">info@makanyab.com</a></p>
                            </div>
                        </div>

                        <div style="display:flex; align-items:flex-start; margin-bottom:28px;">
                            <div style="width:52px; height:52px; background:#ECFDF5; color:#10B981; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:22px; margin-right:16px;">🕒</div>
                            <div>
                                <h4 style="font-size:17px; font-weight:600; color:#111827; margin-bottom:6px;">Working Hours</h4>
                                <p style="margin:0; color:#6B7280; line-height:1.7;">Saturday - Thursday<br>9:00 AM - 6:00 PM</p>
                            </div>
                        </div>

                        <div style="border-top:1px solid #E5E7EB; padding-top:25px;">
                            <h4 style="font-size:16px; font-weight:600; color:#111827; margin-bottom:15px;">Follow Us</h4>
                            <div style="display:flex; gap:12px;">
                                <a href="#" style="width:42px; height:42px; background:#10B981; color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; text-decoration:none;">f</a>
                                <a href="#" style="width:42px; height:42px; background:#10B981; color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; text-decoration:none;">in</a>
                                <a href="#" style="width:42px; height:42px; background:#10B981; color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; text-decoration:none;">t</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="box-two" style="padding:40px; border-radius:16px;">
                        <h2 style="font-size:30px; font-weight:700; color:#111827; margin-bottom:30px;">Send Us a Message</h2>

                        @if (session('success'))
                            <div style="padding:18px; background:#ECFDF5; border:1px solid #A7F3D0; color:#065F46; border-radius:12px; margin-bottom:25px;">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom:18px;">
                                        <label style="font-weight:600; margin-bottom:8px; display:block;">Full Name *</label>
                                        <input type="text" name="name" value="{{ old('name') }}" required class="form-control"
                                            style="height:50px; border-radius:10px; border:1px solid #D1D5DB;">
                                        @error('name')<small style="color:#DC2626;">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom:18px;">
                                        <label style="font-weight:600; margin-bottom:8px; display:block;">Phone *</label>
                                        <input type="text" name="telephone" value="{{ old('telephone') }}" required class="form-control"
                                            style="height:50px; border-radius:10px; border:1px solid #D1D5DB;">
                                        @error('telephone')<small style="color:#DC2626;">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom:18px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Email *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required class="form-control"
                                    style="height:50px; border-radius:10px; border:1px solid #D1D5DB;">
                                @error('email')<small style="color:#DC2626;">{{ $message }}</small>@enderror
                            </div>
                            <div class="form-group" style="margin-bottom:18px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Subject</label>
                                <input type="text" name="subject" value="{{ old('subject') }}" class="form-control"
                                    style="height:50px; border-radius:10px; border:1px solid #D1D5DB;">
                            </div>
                            <div class="form-group" style="margin-bottom:22px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Message *</label>
                                <textarea name="message" rows="6" required class="form-control"
                                    style="border-radius:10px; border:1px solid #D1D5DB; resize:none;">{{ old('message') }}</textarea>
                                @error('message')<small style="color:#DC2626;">{{ $message }}</small>@enderror
                            </div>
                            <button type="submit" style="background:#10B981; color:#fff; border:none; padding:14px 35px; font-size:16px; font-weight:600; border-radius:12px;">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top:40px;">
                <div class="col-md-12">
                    <div class="box-two" style="border-radius:16px; overflow:hidden;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31119.506003890427!2d62.21074765598502!3d34.33735505799986!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x38dcb80c8ebfb51f%3A0x9618cd21c33e93b0!2sHerat%2C%20Afghanistan!5e0!3m2!1sen!2sus!4v0000000000000000"
                            style="border:0; width:100%; min-height:360px;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
