@extends('layouts.app')

@section('title', __('content.legal.terms'))

@section('content')
    <section class="mk-hero">
        <div class="container">
            <h1 class="mk-hero__title">{{ __('content.legal.terms') }}</h1>
            <p class="mk-hero__text">{{ __('content.legal.terms_intro') }}</p>
        </div>
    </section>

    <section class="mk-page-section">
        <div class="container">
            <div class="mk-card">
                <h2 class="mk-heading mk-heading--md">Use of Makanyab</h2>
                <p class="mk-text">Makanyab helps people discover places and services. Users must provide accurate
                    information and avoid submitting misleading, harmful, or unlawful content.</p>

                <h2 class="mk-heading mk-heading--md">Listings and Reviews</h2>
                <p class="mk-text">Listings, suggestions, and reviews may be moderated for quality, safety, and relevance.
                    Makanyab may remove content that violates platform standards.</p>

                <h2 class="mk-heading mk-heading--md">{{ __('content.legal.support') }}</h2>
                <p class="mk-text">Questions about these terms can be sent through the contact page.</p>
                <x-ui.button :href="route('contact')" variant="primary">{{ __('content.posts.contact_support') }}</x-ui.button>
            </div>
        </div>
    </section>
@endsection
