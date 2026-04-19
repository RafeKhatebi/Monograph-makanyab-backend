@extends('layouts.app')
@section('title', 'About Us')
@section('content')
    <div class="page-head">
        <div class="container">
            <div class="row">
                <div class="page-head-content">
                    <h1 class="page-title">About Makanyab</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="content-area" style="background-color: #FCFCFC; padding: 55px 0;">
        <div class="container">
            <!-- Hero Section -->
            <div class="row" style="margin-bottom: 50px;">
                <div class="col-md-12 text-center">
                    <h2 style="font-size: 36px; font-weight: 700; color: #111827; margin-bottom: 20px;">
                        Discover the Best Places in Afghanistan
                    </h2>
                    <p style="font-size: 18px; color: #6B7280; max-width: 800px; margin: 0 auto; line-height: 1.8;">
                        Makanyab is your trusted guide to finding restaurants, cafes, hotels, and entertainment venues
                        across Afghanistan.
                        We help you discover amazing places and share your experiences with the community.
                    </p>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="row" style="margin-bottom: 50px;">
                <div class="col-md-4" style="margin-bottom: 30px;">
                    <div class="box-two" style="text-align: center; padding: 40px 30px; height: 100%;">
                        <div style="font-size: 48px; margin-bottom: 20px;">🔍</div>
                        <h3 style="font-size: 22px; font-weight: 600; color: #111827; margin-bottom: 15px;">Easy Discovery
                        </h3>
                        <p style="color: #6B7280; line-height: 1.6; margin: 0;">
                            Search and filter through thousands of places to find exactly what you're looking for in your
                            city.
                        </p>
                    </div>
                </div>

                <div class="col-md-4" style="margin-bottom: 30px;">
                    <div class="box-two" style="text-align: center; padding: 40px 30px; height: 100%;">
                        <div style="font-size: 48px; margin-bottom: 20px;">⭐</div>
                        <h3 style="font-size: 22px; font-weight: 600; color: #111827; margin-bottom: 15px;">Trusted Reviews
                        </h3>
                        <p style="color: #6B7280; line-height: 1.6; margin: 0;">
                            Read authentic reviews from real customers and share your own experiences to help others.
                        </p>
                    </div>
                </div>

                <div class="col-md-4" style="margin-bottom: 30px;">
                    <div class="box-two" style="text-align: center; padding: 40px 30px; height: 100%;">
                        <div style="font-size: 48px; margin-bottom: 20px;">📍</div>
                        <h3 style="font-size: 22px; font-weight: 600; color: #111827; margin-bottom: 15px;">Detailed
                            Information</h3>
                        <p style="color: #6B7280; line-height: 1.6; margin: 0;">
                            Get complete details including location, contact info, opening hours, and photos for every
                            place.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Mission Section -->
            <div class="row" style="margin-bottom: 50px;">
                <div class="col-md-6" style="margin-bottom: 30px;">
                    <div class="box-two" style="padding: 40px; height: 100%;">
                        <h3 style="font-size: 28px; font-weight: 700; color: #10B981; margin-bottom: 20px;">Our Mission</h3>
                        <p style="color: #374151; line-height: 1.8; font-size: 16px; margin-bottom: 15px;">
                            At Makanyab, we believe that everyone deserves to discover great places and experiences.
                            Our mission is to connect people with the best restaurants, cafes, hotels, and entertainment
                            venues across Afghanistan.
                        </p>
                        <p style="color: #374151; line-height: 1.8; font-size: 16px; margin: 0;">
                            We're building a community where locals and visitors can share their experiences,
                            discover hidden gems, and make informed decisions about where to eat, stay, and explore.
                        </p>
                    </div>
                </div>

                <div class="col-md-6" style="margin-bottom: 30px;">
                    <div class="box-two" style="padding: 40px; height: 100%;">
                        <h3 style="font-size: 28px; font-weight: 700; color: #10B981; margin-bottom: 20px;">Why Choose Us
                        </h3>
                        <ul style="color: #374151; line-height: 2; font-size: 16px; margin: 0; padding-left: 20px;">
                            <li><strong>Local Expertise:</strong> We know Afghanistan and its cities inside out</li>
                            <li><strong>Verified Places:</strong> All listings are verified for accuracy</li>
                            <li><strong>Community Driven:</strong> Real reviews from real people</li>
                            <li><strong>Always Updated:</strong> Fresh content and new places added regularly</li>
                            <li><strong>Easy to Use:</strong> Simple, intuitive interface in your language</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- CTA Section -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box-two"
                        style="text-align: center; padding: 60px 40px; background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
                        <h3 style="font-size: 32px; font-weight: 700; color: #6B7280; margin-bottom: 20px;">
                            Join Our Community Today
                        </h3>
                        <p style="font-size: 18px; color: #6B7280; margin-bottom: 30px; opacity: 0.9;">
                            Start discovering amazing places and sharing your experiences
                        </p>
                        <div>
                            @guest
                                <a href="{{ route('register') }}" class="btn btn-lg"
                                    style="background: #; color:#6B7280 #10B981; padding: 15px 40px; font-weight: 600; border-radius: 8px; margin-right: 15px;">
                                    Sign Up Free
                                </a>
                                <a href="{{ route('places.index') }}" class="btn btn-lg"
                                    style="background: transparent; color: #6B7280; border: 2px solid #ffffff; padding: 15px 40px; font-weight: 600; border-radius: 8px;">
                                    Explore Places
                                </a>
                            @else
                                <a href="{{ route('places.index') }}" class="btn btn-lg"
                                    style="background: #6B7280; color: #10B981; padding: 15px 40px; font-weight: 600; border-radius: 8px;">
                                    Explore Places
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
