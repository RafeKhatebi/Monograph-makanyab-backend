@extends('layouts.app')

@section('title', 'About Us')

@section('content')

    {{-- Hero Section --}}
    <div class="mk-hero mk-hero--large">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                    <h1 class="mk-hero__title mk-hero__title--large">
                        About Makanyab
                    </h1>
                    <p class="mk-hero__text mk-hero__text--center mk-stack-sm">
                        The local discovery platform connecting people with the best services, businesses and places in
                        Afghanistan.
                    </p>
                    <a href="{{ route('search.index') }}" class="mk-button mk-button--secondary mk-button--lg">
                        Start Searching
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="mk-page-section">
        <div class="container">

            {{-- Who We Are --}}
            <div class="row mk-stack-lg">
                <div class="col-md-6 mk-stack-md">
                    <h2 class="mk-heading mk-heading--xl">
                        Who We Are
                    </h2>

                    <p class="mk-text">
                        Makanyab is a local discovery platform built to connect people with trusted businesses,
                        services, and places across Afghanistan.
                    </p>

                    <p class="mk-text mk-text--muted">
                        From restaurants and hotels to service providers and shops, we simplify how people find
                        what they need locally.
                    </p>
                </div>

                <div class="col-md-6 mk-stack-md">
                    <div class="mk-card mk-card--center">
                        <h3 class="mk-heading mk-heading--md">
                            Local discovery made easy
                        </h3>
                        <p class="mk-text mk-text--muted">
                            Find verified businesses, trusted services, and top-rated places in your city.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Features --}}
            <div class="row mk-stack-lg">
                <div class="col-md-4 mk-stack-sm">
                    <div class="mk-card mk-card--center mk-card--feature">
                        <h3 class="mk-heading mk-heading--sm">
                            Fast Search
                        </h3>
                        <p class="mk-text mk-text--muted mk-text--compact">
                            Quickly find businesses and services using smart filters.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 mk-stack-sm">
                    <div class="mk-card mk-card--center mk-card--feature">

                        <h3 class="mk-heading mk-heading--sm">
                            Real Reviews
                        </h3>
                        <p class="mk-text mk-text--muted mk-text--compact">
                            Read honest feedback from real customers.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 mk-stack-sm">
                    <div class="mk-card mk-card--center mk-card--feature">

                        <h3 class="mk-heading mk-heading--sm">
                            Verified Listings
                        </h3>
                        <p class="mk-text mk-text--muted mk-text--compact">
                            Trusted and verified business information.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Mission & Vision --}}
            <div class="row mk-stack-lg">
                <div class="col-md-6 mk-stack-sm">
                    <div class="mk-card mk-card--accent">
                        <h3 class="mk-heading mk-heading--md mk-heading--primary">
                            Our Mission
                        </h3>
                        <p class="mk-text">
                            To make local discovery simple, fast, and reliable for everyone in Afghanistan.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 mk-stack-sm">
                    <div class="mk-card mk-card--accent">
                        <h3 class="mk-heading mk-heading--md mk-heading--primary">
                            Our Vision
                        </h3>
                        <p class="mk-text">
                            To become the leading platform for discovering local businesses and services in Afghanistan.
                        </p>
                    </div>
                </div>
            </div>

            {{-- How It Works --}}
            <div class="mk-card mk-card--spacious">
                <h3 class="mk-heading mk-heading--lg text-center mk-stack-md">
                    How It Works
                </h3>

                <div class="row text-center">
                    <div class="col-md-4 mk-stack-sm">
                        <div class="mk-step-number">01</div>
                        <h4 class="mk-heading mk-heading--sm">Search</h4>
                        <p class="mk-text mk-text--muted">Find services and businesses near you.</p>
                    </div>

                    <div class="col-md-4 mk-stack-sm">
                        <div class="mk-step-number">02</div>
                        <h4 class="mk-heading mk-heading--sm">Compare</h4>
                        <p class="mk-text mk-text--muted">Read reviews and compare options easily.</p>
                    </div>

                    <div class="col-md-4 mk-stack-sm">
                        <div class="mk-step-number">03</div>
                        <h4 class="mk-heading mk-heading--sm">Choose</h4>
                        <p class="mk-text mk-text--muted">Pick the best option and connect directly.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
