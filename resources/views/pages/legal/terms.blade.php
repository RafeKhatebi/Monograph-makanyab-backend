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
            <div class="static-content">
                @foreach (__('content.legal.terms_sections') as $section)
                    <section class="static-content__section">
                        <h2 class="mk-heading mk-heading--md">{{ $section['title'] }}</h2>
                        <p class="mk-text">{{ $section['body'] }}</p>
                    </section>
                @endforeach

                <section class="static-content__section static-content__section--contact">
                    <h2 class="mk-heading mk-heading--md">{{ __('content.legal.support') }}</h2>
                    <p class="mk-text">{{ __('content.legal.questions') }}</p>
                    <x-ui.button :href="route('contact')" variant="primary">{{ __('content.posts.contact_support') }}</x-ui.button>
                </section>
            </div>
        </div>
    </section>
@endsection
