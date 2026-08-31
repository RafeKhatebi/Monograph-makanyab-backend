@extends('layouts.app')

@section('title', __('content.about.title'))

@section('content')

    {{-- Hero Section --}}
    <div class="mk-hero mk-hero--large">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                    <img src="{{ asset('assets/img/branding/makanyab-logo-primary.svg') }}" alt="Makanyab" class="about-branding__logo">
                    <h1 class="mk-hero__title mk-hero__title--large">
                        {{ __('content.about.title') }}
                    </h1>
                    <p class="mk-hero__text mk-hero__text--center mk-stack-sm">
                        {{ __('content.about.intro') }}
                    </p>
                    <a href="{{ route('search.index') }}" class="mk-button mk-button--secondary mk-button--lg">
                        {{ __('content.about.start') }}
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
                        {{ __('content.about.who') }}
                    </h2>

                    <p class="mk-text">
                        {{ __('content.about.who_text') }}
                    </p>

                    <p class="mk-text mk-text--muted">
                        {{ __('content.about.who_muted') }}
                    </p>
                </div>

                <div class="col-md-6 mk-stack-md">
                    <div class="mk-card mk-card--center">
                        <h3 class="mk-heading mk-heading--md">
                            {{ __('content.about.easy') }}
                        </h3>
                        <p class="mk-text mk-text--muted">
                            {{ __('content.about.easy_text') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Features --}}
            <div class="row mk-stack-lg">
                <div class="col-md-4 mk-stack-sm">
                    <div class="mk-card mk-card--center mk-card--feature">
                        <h3 class="mk-heading mk-heading--sm">
                            {{ __('content.about.fast') }}
                        </h3>
                        <p class="mk-text mk-text--muted mk-text--compact">
                            {{ __('content.about.fast_text') }}
                        </p>
                    </div>
                </div>

                <div class="col-md-4 mk-stack-sm">
                    <div class="mk-card mk-card--center mk-card--feature">

                        <h3 class="mk-heading mk-heading--sm">
                            {{ __('content.about.reviews') }}
                        </h3>
                        <p class="mk-text mk-text--muted mk-text--compact">
                            {{ __('content.about.reviews_text') }}
                        </p>
                    </div>
                </div>

                <div class="col-md-4 mk-stack-sm">
                    <div class="mk-card mk-card--center mk-card--feature">

                        <h3 class="mk-heading mk-heading--sm">
                            {{ __('content.about.verified') }}
                        </h3>
                        <p class="mk-text mk-text--muted mk-text--compact">
                            {{ __('content.about.verified_text') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Mission & Vision --}}
            <div class="row mk-stack-lg">
                <div class="col-md-6 mk-stack-sm">
                    <div class="mk-card mk-card--accent">
                        <h3 class="mk-heading mk-heading--md mk-heading--primary">
                            {{ __('content.about.mission') }}
                        </h3>
                        <p class="mk-text">
                            {{ __('content.about.mission_text') }}
                        </p>
                    </div>
                </div>

                <div class="col-md-6 mk-stack-sm">
                    <div class="mk-card mk-card--accent">
                        <h3 class="mk-heading mk-heading--md mk-heading--primary">
                            {{ __('content.about.vision') }}
                        </h3>
                        <p class="mk-text">
                            {{ __('content.about.vision_text') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- How It Works --}}
            <div class="mk-card mk-card--spacious">
                <h3 class="mk-heading mk-heading--lg text-center mk-stack-md">
                    {{ __('content.about.how') }}
                </h3>

                <div class="row text-center">
                    <div class="col-md-4 mk-stack-sm">
                        <div class="mk-step-number">01</div>
                        <h4 class="mk-heading mk-heading--sm">{{ __('content.about.search') }}</h4>
                        <p class="mk-text mk-text--muted">{{ __('content.about.search_text') }}</p>
                    </div>

                    <div class="col-md-4 mk-stack-sm">
                        <div class="mk-step-number">02</div>
                        <h4 class="mk-heading mk-heading--sm">{{ __('content.about.compare') }}</h4>
                        <p class="mk-text mk-text--muted">{{ __('content.about.compare_text') }}</p>
                    </div>

                    <div class="col-md-4 mk-stack-sm">
                        <div class="mk-step-number">03</div>
                        <h4 class="mk-heading mk-heading--sm">{{ __('content.about.choose') }}</h4>
                        <p class="mk-text mk-text--muted">{{ __('content.about.choose_text') }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
