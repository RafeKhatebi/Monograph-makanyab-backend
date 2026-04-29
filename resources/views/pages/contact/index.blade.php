@extends('layouts.app')
@section('title', 'Contact Us')
@section('content')

    {{-- Header --}}
    <div style="background:linear-gradient(135deg,#064e3b,#10B981);padding:50px 0;">
        <div class="container text-center">
            <h1 style="font-size:34px;font-weight:800;color:#fff;margin-bottom:10px;">Contact Us</h1>
            <p
                style="color:rgba(255,255,255,.85);font-size:16px;margin:0;max-width:600px;margin-left:auto;margin-right:auto;">
                Have a question about listings, support, or partnerships? Reach out and our team will respond quickly.
            </p>
        </div>
    </div>

    <div style="background:#F8FAFC;padding:50px 0 70px;">
        <div class="container">
            <div class="row">

                {{-- Contact Info --}}
                <div class="col-md-4" style="margin-bottom:30px;">
                    <div style="background:#fff;border-radius:14px;padding:32px;border:1px solid #E5E7EB;height:100%;">
                        <h3 style="font-size:20px;font-weight:700;color:#111827;margin-bottom:20px;">Get in Touch</h3>
                        <p style="font-size:14px;color:#6B7280;line-height:1.8;margin-bottom:28px;">
                            We're here to help business owners and users get the best local experience.
                        </p>

                        <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:22px;">
                            <div
                                style="width:44px;height:44px;background:#ECFDF5;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fa fa-map-marker" style="color:#10B981;font-size:18px;"></i>
                            </div>
                            <div>
                                <p style="font-size:13px;font-weight:600;color:#111827;margin:0 0 3px;">Office Address</p>
                                <p style="font-size:14px;color:#6B7280;margin:0;">Herat, Afghanistan</p>
                            </div>
                        </div>

                        <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:22px;">
                            <div
                                style="width:44px;height:44px;background:#ECFDF5;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fa fa-phone" style="color:#10B981;font-size:18px;"></i>
                            </div>
                            <div>
                                <p style="font-size:13px;font-weight:600;color:#111827;margin:0 0 3px;">Phone</p>
                                <a href="tel:+93728958411" style="font-size:14px;color:#10B981;text-decoration:none;">+93
                                    728 958 411</a>
                            </div>
                        </div>

                        <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:22px;">
                            <div
                                style="width:44px;height:44px;background:#ECFDF5;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fa fa-envelope" style="color:#10B981;font-size:18px;"></i>
                            </div>
                            <div>
                                <p style="font-size:13px;font-weight:600;color:#111827;margin:0 0 3px;">Email</p>
                                <a href="mailto:info@makanyab.com"
                                    style="font-size:14px;color:#10B981;text-decoration:none;">info@makanyab.com</a>
                            </div>
                        </div>

                        <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:28px;">
                            <div
                                style="width:44px;height:44px;background:#ECFDF5;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fa fa-clock-o" style="color:#10B981;font-size:18px;"></i>
                            </div>
                            <div>
                                <p style="font-size:13px;font-weight:600;color:#111827;margin:0 0 3px;">Working Hours</p>
                                <p style="font-size:14px;color:#6B7280;margin:0;">Sat – Thu, 9:00 AM – 6:00 PM</p>
                            </div>
                        </div>

                        <div style="border-top:1px solid #E5E7EB;padding-top:20px;">
                            <p style="font-size:13px;font-weight:600;color:#111827;margin-bottom:12px;">Follow Us</p>
                            <div style="display:flex;gap:10px;">
                                <a href="#"
                                    style="width:38px;height:38px;background:#1F2937;color:#9CA3AF;border-radius:10px;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:16px;transition:background .2s;"
                                    onmouseover="this.style.background='#10B981';this.style.color='#fff'"
                                    onmouseout="this.style.background='#1F2937';this.style.color='#9CA3AF'"><i
                                        class="fa fa-facebook"></i></a>
                                <a href="#"
                                    style="width:38px;height:38px;background:#1F2937;color:#9CA3AF;border-radius:10px;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:16px;transition:background .2s;"
                                    onmouseover="this.style.background='#10B981';this.style.color='#fff'"
                                    onmouseout="this.style.background='#1F2937';this.style.color='#9CA3AF'"><i
                                        class="fa fa-instagram"></i></a>
                                <a href="#"
                                    style="width:38px;height:38px;background:#1F2937;color:#9CA3AF;border-radius:10px;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:16px;transition:background .2s;"
                                    onmouseover="this.style.background='#10B981';this.style.color='#fff'"
                                    onmouseout="this.style.background='#1F2937';this.style.color='#9CA3AF'"><i
                                        class="fa fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Form --}}
                <div class="col-md-8" style="margin-bottom:30px;">
                    <div style="background:#fff;border-radius:14px;padding:36px;border:1px solid #E5E7EB;">
                        <h3 style="font-size:22px;font-weight:700;color:#111827;margin-bottom:24px;">Send Us a Message</h3>

                        @if (session('success'))
                            <div
                                style="padding:14px 18px;background:#ECFDF5;border:1px solid #A7F3D0;color:#065F46;border-radius:10px;margin-bottom:22px;font-size:14px;">
                                <i class="fa fa-check-circle" style="margin-right:6px;"></i> {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div style="margin-bottom:16px;">
                                        <label
                                            style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Full
                                            Name *</label>
                                        <input type="text" name="name" value="{{ old('name') }}" required
                                            style="width:100%;height:46px;padding:0 14px;border:1.5px solid #D1D5DB;border-radius:10px;font-size:14px;outline:none;transition:border-color .2s;"
                                            onfocus="this.style.borderColor='#10B981'"
                                            onblur="this.style.borderColor='#D1D5DB'">
                                        @error('name')
                                            <small style="color:#DC2626;font-size:12px;">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div style="margin-bottom:16px;">
                                        <label
                                            style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Phone
                                            *</label>
                                        <input type="text" name="telephone" value="{{ old('telephone') }}" required
                                            style="width:100%;height:46px;padding:0 14px;border:1.5px solid #D1D5DB;border-radius:10px;font-size:14px;outline:none;transition:border-color .2s;"
                                            onfocus="this.style.borderColor='#10B981'"
                                            onblur="this.style.borderColor='#D1D5DB'">
                                        @error('telephone')
                                            <small style="color:#DC2626;font-size:12px;">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div style="margin-bottom:16px;">
                                <label
                                    style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Email
                                    *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    style="width:100%;height:46px;padding:0 14px;border:1.5px solid #D1D5DB;border-radius:10px;font-size:14px;outline:none;transition:border-color .2s;"
                                    onfocus="this.style.borderColor='#10B981'" onblur="this.style.borderColor='#D1D5DB'">
                                @error('email')
                                    <small style="color:#DC2626;font-size:12px;">{{ $message }}</small>
                                @enderror
                            </div>
                            <div style="margin-bottom:16px;">
                                <label
                                    style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Subject</label>
                                <input type="text" name="subject" value="{{ old('subject') }}"
                                    style="width:100%;height:46px;padding:0 14px;border:1.5px solid #D1D5DB;border-radius:10px;font-size:14px;outline:none;transition:border-color .2s;"
                                    onfocus="this.style.borderColor='#10B981'" onblur="this.style.borderColor='#D1D5DB'">
                            </div>
                            <div style="margin-bottom:22px;">
                                <label
                                    style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Message
                                    *</label>
                                <textarea name="message" rows="5" required
                                    style="width:100%;padding:12px 14px;border:1.5px solid #D1D5DB;border-radius:10px;font-size:14px;outline:none;resize:none;transition:border-color .2s;"
                                    onfocus="this.style.borderColor='#10B981'" onblur="this.style.borderColor='#D1D5DB'">{{ old('message') }}</textarea>
                                @error('message')
                                    <small style="color:#DC2626;font-size:12px;">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit"
                                style="background:#10B981;color:#fff;border:none;padding:13px 32px;font-size:15px;font-weight:700;border-radius:10px;cursor:pointer;transition:background .2s;"
                                onmouseover="this.style.background='#059669'"
                                onmouseout="this.style.background='#10B981'">
                                <i class="fa fa-paper-plane" style="margin-right:6px;"></i> Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Map --}}
            <div class="row" style="margin-top:10px;">
                <div class="col-md-12">
                    <div style="border-radius:14px;overflow:hidden;border:1px solid #E5E7EB;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31119.506003890427!2d62.21074765598502!3d34.33735505799986!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x38dcb80c8ebfb51f%3A0x9618cd21c33e93b0!2sHerat%2C%20Afghanistan!5e0!3m2!1sen!2sus!4v0000000000000000"
                            style="border:0;width:100%;min-height:340px;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
