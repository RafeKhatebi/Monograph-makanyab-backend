@extends('layouts.app')

@section('title', 'About Us')

@section('content')
    <div class="content-area" style="background:#F8FAFC; padding:70px 0;">
        <div class="container">
            <div class="row" style="margin-bottom:60px;">
                <div class="col-md-6" style="margin-bottom:30px;">
                    <h1 style="font-size:42px; font-weight:700; color:#111827; margin-bottom:20px;">About Makanyab</h1>
                    <p style="font-size:18px; color:#4B5563; line-height:1.9; margin-bottom:25px;">
                        Makanyab is the local discovery platform built to connect people with the best services,
                        businesses and places in Afghanistan. We make it easy for users to search, compare and choose
                        trusted local options.
                    </p>
                    <p style="font-size:16px; color:#6B7280; line-height:1.9; margin-bottom:25px;">
                        From restaurants and hotels to service providers and shopping, our goal is to bring
                        accurate local data and real customer feedback together in one simple experience.
                    </p>
                    <a href="{{ route('search.index') }}" style="display:inline-block; background:#10B981; color:#fff; padding:14px 30px; border-radius:12px; font-weight:700; text-decoration:none;">
                        Start Searching
                    </a>
                </div>
                <div class="col-md-6" style="margin-bottom:30px;">
                    <div class="box-two" style="padding:45px; border-radius:16px; text-align:center;">
                        <div style="font-size:72px; margin-bottom:20px;">📍</div>
                        <h3 style="font-size:26px; font-weight:700; color:#111827; margin-bottom:15px;">Local discovery made easy</h3>
                        <p style="color:#6B7280; line-height:1.8; margin:0;">Find verified businesses, trusted services, and top-rated places in your city with one search.</p>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-bottom:60px;">
                <div class="col-md-4" style="margin-bottom:30px;">
                    <div class="box-two" style="padding:35px 30px; text-align:center; height:100%; border-radius:16px;">
                        <div style="font-size:50px; margin-bottom:18px;">🔎</div>
                        <h3 style="font-size:22px; font-weight:700; color:#111827; margin-bottom:15px;">Search Fast</h3>
                        <p style="color:#6B7280; line-height:1.7; margin:0;">Use smart filters to narrow your search by category, city, or service type.</p>
                    </div>
                </div>
                <div class="col-md-4" style="margin-bottom:30px;">
                    <div class="box-two" style="padding:35px 30px; text-align:center; height:100%; border-radius:16px;">
                        <div style="font-size:50px; margin-bottom:18px;">💬</div>
                        <h3 style="font-size:22px; font-weight:700; color:#111827; margin-bottom:15px;">Real Reviews</h3>
                        <p style="color:#6B7280; line-height:1.7; margin:0;">Read real customer feedback to make confident decisions.</p>
                    </div>
                </div>
                <div class="col-md-4" style="margin-bottom:30px;">
                    <div class="box-two" style="padding:35px 30px; text-align:center; height:100%; border-radius:16px;">
                        <div style="font-size:50px; margin-bottom:18px;">🏢</div>
                        <h3 style="font-size:22px; font-weight:700; color:#111827; margin-bottom:15px;">Verified Business</h3>
                        <p style="color:#6B7280; line-height:1.7; margin:0;">Explore listings that are verified and maintained for accuracy.</p>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-bottom:60px;">
                <div class="col-md-6" style="margin-bottom:30px;">
                    <div class="box-two" style="padding:40px; border-radius:16px; height:100%;">
                        <h3 style="font-size:28px; font-weight:700; color:#10B981; margin-bottom:20px;">Our Mission</h3>
                        <p style="color:#374151; line-height:1.9; font-size:16px; margin:0;">To empower local customers and business owners with a trusted discovery platform that simplifies finding services and places in every city.</p>
                    </div>
                </div>
                <div class="col-md-6" style="margin-bottom:30px;">
                    <div class="box-two" style="padding:40px; border-radius:16px; height:100%;">
                        <h3 style="font-size:28px; font-weight:700; color:#10B981; margin-bottom:20px;">Our Vision</h3>
                        <p style="color:#374151; line-height:1.9; font-size:16px; margin:0;">To become the go-to platform for local discovery in Afghanistan, where every user can access the best businesses and services near them.</p>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-bottom:60px;">
                <div class="col-md-12">
                    <div class="box-two" style="padding:45px; border-radius:16px;">
                        <h3 style="font-size:30px; font-weight:700; color:#111827; text-align:center; margin-bottom:30px;">How It Works</h3>
                        <div class="row text-center">
                            <div class="col-md-4" style="margin-bottom:25px;">
                                <div style="font-size:42px; margin-bottom:15px;">1</div>
                                <h4 style="font-size:20px; color:#10B981; font-weight:700; margin-bottom:12px;">Search</h4>
                                <p style="color:#6B7280; margin:0;">Browse categories, services, and local businesses in your area.</p>
                            </div>
                            <div class="col-md-4" style="margin-bottom:25px;">
                                <div style="font-size:42px; margin-bottom:15px;">2</div>
                                <h4 style="font-size:20px; color:#10B981; font-weight:700; margin-bottom:12px;">Compare</h4>
                                <p style="color:#6B7280; margin:0;">Read reviews, check details, and compare options with ease.</p>
                            </div>
                            <div class="col-md-4" style="margin-bottom:25px;">
                                <div style="font-size:42px; margin-bottom:15px;">3</div>
                                <h4 style="font-size:20px; color:#10B981; font-weight:700; margin-bottom:12px;">Choose</h4>
                                <p style="color:#6B7280; margin:0;">Pick the best place or service and connect directly to get started.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
