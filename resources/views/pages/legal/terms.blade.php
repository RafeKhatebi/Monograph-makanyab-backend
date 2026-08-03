@extends('layouts.app')

@section('title', 'Terms of Service')

@section('content')
    <section class="mk-hero">
        <div class="container">
            <h1 class="mk-hero__title">Terms of Service</h1>
            <p class="mk-hero__text">Guidelines for using Makanyab responsibly.</p>
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

                <h2 class="mk-heading mk-heading--md">Support</h2>
                <p class="mk-text">Questions about these terms can be sent through the contact page.</p>
                <x-ui.button :href="route('contact')" variant="primary">Contact Support</x-ui.button>
            </div>
        </div>
    </section>
@endsection
