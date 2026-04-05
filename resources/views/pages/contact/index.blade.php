@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')

<div class="page-head">
    <div class="container">
        <div class="row">
            <div class="page-head-content">
                <h1 class="page-title">Contact Us</h1>
            </div>
        </div>
    </div>
</div>

<div class="content-area" style="background-color: #FCFCFC; padding: 55px 0;">
    <div class="container">
        
        <!-- Intro Section -->
        <div class="row" style="margin-bottom: 50px;">
            <div class="col-md-12 text-center">
                <h2 style="font-size: 36px; font-weight: 700; color: #111827; margin-bottom: 20px;">
                    Get in Touch
                </h2>
                <p style="font-size: 18px; color: #6B7280; max-width: 700px; margin: 0 auto; line-height: 1.8;">
                    Have a question or feedback? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
                </p>
            </div>
        </div>

        <div class="row">
            <!-- Contact Form -->
            <div class="col-md-8" style="margin-bottom: 30px;">
                <div class="box-two" style="padding: 40px;">
                    <h3 style="font-size: 24px; font-weight: 600; color: #111827; margin-bottom: 25px;">Send us a Message</h3>
                    
                    @if(session('success'))
                        <div style="padding: 15px; background: #ecfdf5; border: 1px solid #d1fae5; color: #065f46; border-radius: 8px; margin-bottom: 25px;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="font-weight: 500; color: #374151; margin-bottom: 8px; display: block;">Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="form-control" style="padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px;">
                            @error('name')
                                <span style="color: #dc2626; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="font-weight: 500; color: #374151; margin-bottom: 8px; display: block;">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="form-control" style="padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px;">
                            @error('email')
                                <span style="color: #dc2626; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="font-weight: 500; color: #374151; margin-bottom: 8px; display: block;">Subject *</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" required
                                class="form-control" style="padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px;">
                            @error('subject')
                                <span style="color: #dc2626; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" style="margin-bottom: 25px;">
                            <label style="font-weight: 500; color: #374151; margin-bottom: 8px; display: block;">Message *</label>
                            <textarea name="message" rows="6" required
                                class="form-control" style="padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px; resize: vertical;">{{ old('message') }}</textarea>
                            @error('message')
                                <span style="color: #dc2626; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-lg" style="background: #10B981; color: #FFFFFF; padding: 14px 40px; font-weight: 600; border-radius: 8px; border: none; font-size: 16px;">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="col-md-4" style="margin-bottom: 30px;">
                <div class="box-two" style="padding: 40px; height: 100%;">
                    <h3 style="font-size: 24px; font-weight: 600; color: #111827; margin-bottom: 25px;">Contact Information</h3>
                    
                    <div style="margin-bottom: 30px;">
                        <div style="display: flex; align-items: flex-start; margin-bottom: 20px;">
                            <div style="font-size: 24px; margin-right: 15px; color: #10B981;">📍</div>
                            <div>
                                <h4 style="font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 5px;">Address</h4>
                                <p style="color: #6B7280; margin: 0; line-height: 1.6;">
                                    Herat, Afghanistan
                                </p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: flex-start; margin-bottom: 20px;">
                            <div style="font-size: 24px; margin-right: 15px; color: #10B981;">📧</div>
                            <div>
                                <h4 style="font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 5px;">Email</h4>
                                <p style="color: #6B7280; margin: 0; line-height: 1.6;">
                                    <a href="mailto:info@makanyab.com" style="color: #10B981; text-decoration: none;">info@makanyab.com</a>
                                </p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: flex-start; margin-bottom: 20px;">
                            <div style="font-size: 24px; margin-right: 15px; color: #10B981;">📱</div>
                            <div>
                                <h4 style="font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 5px;">Phone</h4>
                                <p style="color: #6B7280; margin: 0; line-height: 1.6;">
                                    <a href="tel:+93728958411" style="color: #10B981; text-decoration: none;">+93 728 958 411</a>
                                </p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: flex-start;">
                            <div style="font-size: 24px; margin-right: 15px; color: #10B981;">🕐</div>
                            <div>
                                <h4 style="font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 5px;">Working Hours</h4>
                                <p style="color: #6B7280; margin: 0; line-height: 1.6;">
                                    Saturday - Thursday<br>
                                    9:00 AM - 6:00 PM
                                </p>
                            </div>
                        </div>
                    </div>

                    <div style="padding-top: 25px; border-top: 1px solid #e5e7eb;">
                        <h4 style="font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 15px;">Follow Us</h4>
                        <div style="display: flex; gap: 15px;">
                            <a href="#" style="width: 40px; height: 40px; background: #10B981; color: #FFFFFF; display: flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none; font-size: 18px;">
                                f
                            </a>
                            <a href="#" style="width: 40px; height: 40px; background: #10B981; color: #FFFFFF; display: flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none; font-size: 18px;">
                                t
                            </a>
                            <a href="#" style="width: 40px; height: 40px; background: #10B981; color: #FFFFFF; display: flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none; font-size: 18px;">
                                in
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="row" style="margin-top: 50px;">
            <div class="col-md-12">
                <div class="box-two" style="padding: 40px;">
                    <h3 style="font-size: 28px; font-weight: 700; color: #111827; margin-bottom: 30px; text-align: center;">
                        Frequently Asked Questions
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <h4 style="font-size: 18px; font-weight: 600; color: #10B981; margin-bottom: 10px;">
                                How do I add my business to Makanyab?
                            </h4>
                            <p style="color: #6B7280; line-height: 1.6; margin: 0;">
                                You can register as a business owner and submit your place for review. Our team will verify and publish it within 24-48 hours.
                            </p>
                        </div>

                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <h4 style="font-size: 18px; font-weight: 600; color: #10B981; margin-bottom: 10px;">
                                Is Makanyab free to use?
                            </h4>
                            <p style="color: #6B7280; line-height: 1.6; margin: 0;">
                                Yes! Makanyab is completely free for users to browse, review, and discover places. Business listings are also free.
                            </p>
                        </div>

                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <h4 style="font-size: 18px; font-weight: 600; color: #10B981; margin-bottom: 10px;">
                                How can I report incorrect information?
                            </h4>
                            <p style="color: #6B7280; line-height: 1.6; margin: 0;">
                                You can use the contact form above or email us directly with the details of the incorrect information.
                            </p>
                        </div>

                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <h4 style="font-size: 18px; font-weight: 600; color: #10B981; margin-bottom: 10px;">
                                Can I edit my reviews?
                            </h4>
                            <p style="color: #6B7280; line-height: 1.6; margin: 0;">
                                Currently, reviews cannot be edited after submission. Please contact us if you need to modify a review.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
