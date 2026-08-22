@extends('layouts.app')

@section('title', __('content.legal.privacy'))

@section('content')
    <section class="mk-hero">
        <div class="container">
            <h1 class="mk-hero__title">{{ __('content.legal.privacy') }}</h1>
            <p class="mk-hero__text">{{ __('content.legal.privacy_intro') }}</p>
        </div>
    </section>

    <section class="mk-page-section">
        <div class="container">
            <div class="mk-card">
                <h2 class="mk-heading mk-heading--md">{{ __('content.legal.collect') }}</h2>
                <p class="mk-text">Makanyab collects information you provide when creating an account, submitting listings,
                    sending contact messages, writing reviews, or saving favorites.</p>

                <h2 class="mk-heading mk-heading--md">{{ __('content.legal.use') }}</h2>
                <p class="mk-text">We use this information to operate the directory, moderate submissions, respond to
                    support requests, improve search and discovery, and protect the platform from misuse.</p>

                <h2 class="mk-heading mk-heading--md">{{ __('navigation.contact') }}</h2>
                <p class="mk-text">Questions about privacy can be sent through the contact page.</p>
                <x-ui.button :href="route('contact')" variant="primary">{{ __('footer.contact_makanyab') }}</x-ui.button>
            </div>
        </div>
    </section>
@endsection
