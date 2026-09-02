@extends('layouts.app')

@section('title', __('home.title'))

@section('content')
    @php
        $heroSlides = [
            [
                'image' => asset('assets/img/slide1/bg-01.jpg'),
                'label' => __('home.hero_label'),
                'title' => __('home.hero_title'),
                'text' => __('home.hero_text'),
            ],
            [
                'image' => asset('assets/img/slide1/bg-02.jpg'),
                'label' => __('home.hero_label'),
                'title' => __('home.services_title'),
                'text' => __('home.services_text'),
            ],
            [
                'image' => asset('assets/img/slide1/bg-03.jpg'),
                'label' => __('home.community_label'),
                'title' => __('home.community_title'),
                'text' => __('home.community_text'),
            ],
            [
                'image' => asset('assets/img/slide1/bg-04.jpg'),
                'label' => __('home.places_label'),
                'title' => __('home.places_slide_title'),
                'text' => __('home.places_slide_text'),
            ],
            [
                'image' => asset('assets/img/slide1/bg-05.jpg'),
                'label' => __('home.verified_label'),
                'title' => __('home.verified_slide_title'),
                'text' => __('home.verified_slide_text'),
            ],
            [
                'image' => asset('assets/img/slide1/bg-06.jpg'),
                'label' => __('home.local_label'),
                'title' => __('home.local_slide_title'),
                'text' => __('home.local_slide_text'),
            ],
        ];
    @endphp

    <section class="home-hero" aria-label="{{ __('home.search_label') }}" data-home-hero data-autoplay="true">
        <div class="home-hero__slides" aria-roledescription="carousel" aria-label="{{ __('home.highlights') }}">
            @foreach ($heroSlides as $index => $slide)
                <article class="home-hero__slide{{ $index === 0 ? ' is-active' : '' }}" data-hero-slide
                    aria-hidden="{{ $index === 0 ? 'false' : 'true' }}">
                    <img class="home-hero__image" src="{{ $slide['image'] }}" alt="" aria-hidden="true">
                    <div class="home-hero__shade"></div>
                    <div class="container home-hero__content">
                        <div class="home-hero__copy">
                            <span class="home-hero__eyebrow">{{ $slide['label'] }}</span>
                            <h1 class="home-hero__title">{{ $slide['title'] }}</h1>
                            <p class="home-hero__text">{{ $slide['text'] }}</p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="container home-hero__search-wrap">
            <dl class="home-hero__stats" aria-label="{{ __('home.summary') }}">
                <div>
                    <dt>{{ number_format($homeStats['places']) }}</dt>
                    <dd>{{ __('home.places') }}</dd>
                </div>
                <div>
                    <dt>{{ number_format($homeStats['services']) }}</dt>
                    <dd>{{ __('home.services') }}</dd>
                </div>
                <div>
                    <dt>{{ number_format($homeStats['verified']) }}</dt>
                    <dd>{{ __('home.verified') }}</dd>
                </div>
            </dl>
        </div>

        <div class="home-hero__pagination" role="tablist" aria-label="{{ __('home.highlight_slides') }}">
            @foreach ($heroSlides as $index => $slide)
                <button class="home-hero__dot{{ $index === 0 ? ' is-active' : '' }}" type="button"
                    data-hero-dot="{{ $index }}" aria-label="{{ __('home.show_slide', ['number' => $index + 1]) }}"
                    aria-current="{{ $index === 0 ? 'true' : 'false' }}"></button>
            @endforeach
        </div>
    </section>

    <div class="home-section home-section--muted">
        <div class="container">
            <div class="row">
                <div class="col-md-12 home-section__header">
                    <h2 class="home-section__title">{{ __('home.featured_places_title') }}</h2>
                </div>
            </div>
            <div class="row home-feature-row">
                @forelse($featuredPlaces ?? [] as $place)
                    @include('components.place-card', ['place' => $place])
                @empty
                    <div class="col-md-12 home-empty">
                        <p class="mk-text--muted">{{ __('home.empty_featured_places') }}</p>
                    </div>
                @endforelse
            </div>
            <div class="row">
                <div class="col-md-12 home-more">
                    <a href="{{ route('places.index') }}" class="home-view-more-link">
                        {{ __('home.see_more') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="home-section home-section--plain">
        <div class="container">
            <div class="row">
                <div class="col-md-12 home-section__header">
                    <h2 class="home-section__title">{{ __('home.featured_services_title') }}</h2>
                </div>
            </div>
            <div class="row home-feature-row">
                @forelse($featuredServices ?? [] as $service)
                    <x-service-card :service="$service" />
                @empty
                    <div class="col-md-12 home-empty">
                        <p class="mk-text--muted">{{ __('home.empty_featured_services') }}</p>
                    </div>
                @endforelse
            </div>
            <div class="row">
                <div class="col-md-12 home-more">
                    <a href="{{ route('services.index') }}" class="home-view-more-link">
                        {{ __('home.see_more') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="home-section home-section--plain">
        <div class="container">
            <div class="row">
                <div class="col-md-12 home-section__header">
                    <h2 class="home-section__title">{{ __('home.recently_verified_title') }}</h2>
                </div>
            </div>
            <div class="row home-feature-row">
                @forelse($verifiedPlaces ?? [] as $place)
                    @include('components.place-card', [
                        'place' => $place,
                        'dateLabel' => __('common.dates.verified_on'),
                        'dateValue' => $place->updated_at,
                    ])
                @empty
                    <div class="col-md-12 home-empty">
                        <p class="mk-text--muted">{{ __('home.empty_verified_places') }}</p>
                    </div>
                @endforelse
            </div>
            <div class="row">
                <div class="col-md-12 home-more">
                    <a href="{{ route('places.index', ['verified' => 1]) }}" class="home-view-more-link">
                        {{ __('home.see_more') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="home-section home-section--muted">
        <div class="container">
            <div class="row">
                <div class="col-md-12 home-section__header">
                    <h2 class="home-section__title">{{ __('home.latest_title') }}</h2>
                </div>
            </div>
            <div class="row home-feature-row">
                @forelse($latestPosts ?? [] as $post)
                    <div class="col-sm-6 col-md-4 mk-stack-sm home-card-col">
                        <x-listing-card :item="$post" type="post" />
                    </div>
                @empty
                    <div class="col-md-12 home-empty">
                        <p class="mk-text--muted">{{ __('home.empty_latest') }}</p>
                    </div>
                @endforelse
            </div>
            <div class="row">
                <div class="col-md-12 home-more">
                    <a href="{{ route('posts.index') }}" class="home-view-more-link">
                        {{ __('home.see_more') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="home-section home-section--cta">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="home-cta-card">
                        <div class="home-cta-card__body">
                            <h3 class="home-cta-card__title">{{ __('home.cta_search_title') }}</h3>
                            <p class="home-cta-card__text">{{ __('home.cta_search_text') }}</p>
                            <a href="{{ route('search.index') }}" class="mk-button mk-button--secondary mk-button--md">
                                {{ __('home.search_now') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="home-cta-card">
                        <div class="home-cta-card__body">
                            <h3 class="home-cta-card__title">{{ __('home.cta_business_title') }}</h3>
                            <p class="home-cta-card__text">{{ __('home.cta_business_text') }}</p>
                            <a href="{{ route('register') }}" class="mk-button mk-button--secondary mk-button--md">
                                {{ __('home.get_listed') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/home-hero.js') }}"></script>
@endpush
