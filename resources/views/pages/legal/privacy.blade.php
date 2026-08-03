@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
    <section class="mk-hero">
        <div class="container">
            <h1 class="mk-hero__title">Privacy Policy</h1>
            <p class="mk-hero__text">How Makanyab handles account, listing, and contact information.</p>
        </div>
    </section>

    <section class="mk-page-section">
        <div class="container">
            <div class="mk-card">
                <h2 class="mk-heading mk-heading--md">Information We Collect</h2>
                <p class="mk-text">Makanyab collects information you provide when creating an account, submitting listings,
                    sending contact messages, writing reviews, or saving favorites.</p>

                <h2 class="mk-heading mk-heading--md">How We Use Information</h2>
                <p class="mk-text">We use this information to operate the directory, moderate submissions, respond to
                    support requests, improve search and discovery, and protect the platform from misuse.</p>

                <h2 class="mk-heading mk-heading--md">Contact</h2>
                <p class="mk-text">Questions about privacy can be sent through the contact page.</p>
                <x-ui.button :href="route('contact')" variant="primary">Contact Makanyab</x-ui.button>
            </div>
        </div>
    </section>
@endsection
