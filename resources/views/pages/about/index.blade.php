@extends('layouts.app')

@section('title', 'About Us')

@section('content')

    {{-- Hero Section --}}
    <div style="background:linear-gradient(135deg,#064e3b,#10B981);padding:60px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                    <h1 style="font-size:38px;font-weight:800;color:#fff;margin-bottom:14px;">
                        About Makanyab
                    </h1>
                    <p style="font-size:17px;color:rgba(255,255,255,.85);line-height:1.8;margin-bottom:28px;">
                        The local discovery platform connecting people with the best services, businesses and places in
                        Afghanistan.
                    </p>
                    <a href="{{ route('search.index') }}"
                        style="display:inline-block;background:#fff;color:#10B981;padding:13px 32px;border-radius:12px;font-weight:700;text-decoration:none;font-size:15px;">
                        Start Searching
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div style="background:#F8FAFC;padding:60px 0;">
        <div class="container">

            {{-- Who We Are --}}
            <div class="row" style="margin-bottom:60px;align-items:center;">
                <div class="col-md-6" style="margin-bottom:30px;">
                    <h2 style="font-size:30px;font-weight:800;color:#111827;margin-bottom:16px;">
                        Who We Are
                    </h2>

                    <p style="font-size:16px;color:#4B5563;line-height:1.9;margin-bottom:16px;">
                        Makanyab is a local discovery platform built to connect people with trusted businesses,
                        services, and places across Afghanistan.
                    </p>

                    <p style="font-size:15px;color:#6B7280;line-height:1.9;">
                        From restaurants and hotels to service providers and shops, we simplify how people find
                        what they need locally.
                    </p>
                </div>

                <div class="col-md-6" style="margin-bottom:30px;">
                    <div
                        style="background:#fff;border-radius:16px;padding:40px;text-align:center;border:1px solid #E5E7EB;">
                        <h3 style="font-size:22px;font-weight:700;color:#111827;margin-bottom:12px;">
                            Local discovery made easy
                        </h3>
                        <p style="color:#6B7280;line-height:1.8;margin:0;">
                            Find verified businesses, trusted services, and top-rated places in your city.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Features --}}
            <div class="row" style="margin-bottom:60px;">
                <div class="col-md-4" style="margin-bottom:24px;">
                    <div
                        style="background:#fff;border-radius:14px;padding:32px;text-align:center;border:1px solid #E5E7EB;height:100%;">
                        <h3 style="font-size:18px;font-weight:700;color:#111827;margin-bottom:10px;">
                            Fast Search
                        </h3>
                        <p style="color:#6B7280;font-size:14px;line-height:1.6;margin:0;">
                            Quickly find businesses and services using smart filters.
                        </p>
                    </div>
                </div>

                <div class="col-md-4" style="margin-bottom:24px;">
                    <div
                        style="background:#fff;border-radius:14px;padding:32px;text-align:center;border:1px solid #E5E7EB;height:100%;">

                        <h3 style="font-size:18px;font-weight:700;color:#111827;margin-bottom:10px;">
                            Real Reviews
                        </h3>
                        <p style="color:#6B7280;font-size:14px;line-height:1.6;margin:0;">
                            Read honest feedback from real customers.
                        </p>
                    </div>
                </div>

                <div class="col-md-4" style="margin-bottom:24px;">
                    <div
                        style="background:#fff;border-radius:14px;padding:32px;text-align:center;border:1px solid #E5E7EB;height:100%;">

                        <h3 style="font-size:18px;font-weight:700;color:#111827;margin-bottom:10px;">
                            Verified Listings
                        </h3>
                        <p style="color:#6B7280;font-size:14px;line-height:1.6;margin:0;">
                            Trusted and verified business information.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Mission & Vision --}}
            <div class="row" style="margin-bottom:60px;">
                <div class="col-md-6" style="margin-bottom:24px;">
                    <div
                        style="background:#fff;border-radius:14px;padding:36px;border:1px solid #E5E7EB;border-left:4px solid #10B981;">
                        <h3 style="font-size:22px;font-weight:700;color:#10B981;margin-bottom:14px;">
                            Our Mission
                        </h3>
                        <p style="color:#374151;line-height:1.8;margin:0;">
                            To make local discovery simple, fast, and reliable for everyone in Afghanistan.
                        </p>
                    </div>
                </div>

                <div class="col-md-6" style="margin-bottom:24px;">
                    <div
                        style="background:#fff;border-radius:14px;padding:36px;border:1px solid #E5E7EB;border-left:4px solid #10B981;">
                        <h3 style="font-size:22px;font-weight:700;color:#10B981;margin-bottom:14px;">
                            Our Vision
                        </h3>
                        <p style="color:#374151;line-height:1.8;margin:0;">
                            To become the leading platform for discovering local businesses and services in Afghanistan.
                        </p>
                    </div>
                </div>
            </div>

            {{-- How It Works --}}
            <div style="background:#fff;border-radius:16px;padding:48px;border:1px solid #E5E7EB;">
                <h3 style="font-size:26px;font-weight:800;color:#111827;text-align:center;margin-bottom:36px;">
                    How It Works
                </h3>

                <div class="row text-center">
                    <div class="col-md-4" style="margin-bottom:24px;">
                        <div style="font-size:40px;font-weight:800;color:#10B981;margin-bottom:10px;">01</div>
                        <h4 style="font-size:18px;font-weight:700;margin-bottom:10px;">Search</h4>
                        <p style="color:#6B7280;margin:0;">Find services and businesses near you.</p>
                    </div>

                    <div class="col-md-4" style="margin-bottom:24px;">
                        <div style="font-size:40px;font-weight:800;color:#10B981;margin-bottom:10px;">02</div>
                        <h4 style="font-size:18px;font-weight:700;margin-bottom:10px;">Compare</h4>
                        <p style="color:#6B7280;margin:0;">Read reviews and compare options easily.</p>
                    </div>

                    <div class="col-md-4" style="margin-bottom:24px;">
                        <div style="font-size:40px;font-weight:800;color:#10B981;margin-bottom:10px;">03</div>
                        <h4 style="font-size:18px;font-weight:700;margin-bottom:10px;">Choose</h4>
                        <p style="color:#6B7280;margin:0;">Pick the best option and connect directly.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
