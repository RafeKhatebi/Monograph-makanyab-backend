@extends('layouts.app')

@section('title', __('home.title'))

@section('content')
    @php
        $heroSlides = [
            [
                'image' => asset('assets/img/slide1/slider-image-1.jpg'),
                'label' => __('home.hero_label'),
                'title' => __('home.hero_title'),
                'text' => __('home.hero_text'),
            ],
            [
                'image' => asset('assets/img/demo/property-1.jpg'),
                'label' => __('home.hero_label'),
                'title' => __('home.services_title'),
                'text' => __('home.services_text'),
            ],
            [
                'image' => asset('assets/img/slide1/slider-image-1.jpg'),
                'label' => __('home.community_label'),
                'title' => __('home.community_title'),
                'text' => __('home.community_text'),
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
            <form action="{{ route('search.index') }}" method="GET" class="home-search" data-home-search>
                <input type="hidden" name="place_category" data-place-category-input>
                <input type="hidden" name="service_category" data-service-category-input>

                <div class="home-search__grid">
                    <div class="home-search__field home-search__field--keyword">
                        <label for="home-search-keyword" class="sr-only">{{ __('home.keyword') }}</label>
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <input id="home-search-keyword" type="search" name="search"
                            placeholder="{{ __('home.search_placeholder') }}" class="home-search__input"
                            autocomplete="off">
                    </div>

                    <div class="home-search__field">
                        <label for="home-search-province" class="sr-only">{{ __('home.choose_location') }}</label>
                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                        <select id="home-search-province" name="province" class="home-search__select"
                            aria-label="{{ __('home.choose_location') }}">
                            <option value="">{{ __('home.any_location') }}</option>
                            @foreach ($searchProvinces as $province)
                                <option value="{{ $province }}">{{ $province }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="home-search__field">
                        <label for="home-search-category" class="sr-only">{{ __('home.category') }}</label>
                        <i class="fa fa-folder-open" aria-hidden="true"></i>
                        <select id="home-search-category" class="home-search__select" data-category-picker
                            aria-label="{{ __('home.category') }}">
                            <option value="">{{ __('home.any_category') }}</option>
                            @if ($categories->isNotEmpty())
                                <optgroup label="{{ __('home.places') }}">
                                    @foreach ($categories as $category)
                                        <option value="place:{{ $category->slug }}">{{ $category->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                            @if ($serviceCategories->isNotEmpty())
                                <optgroup label="{{ __('home.services') }}">
                                    @foreach ($serviceCategories as $category)
                                        <option value="service:{{ $category->slug }}">{{ $category->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                    </div>

                    <div class="home-search__field">
                        <label for="home-search-type" class="sr-only">{{ __('home.result_type') }}</label>
                        <i class="fa fa-list-ul" aria-hidden="true"></i>
                        <select id="home-search-type" name="type" class="home-search__select"
                            aria-label="{{ __('home.result_type') }}">
                            <option value="all">{{ __('home.places_services') }}</option>
                            <option value="places">{{ __('home.places_only') }}</option>
                            <option value="services">{{ __('home.services_only') }}</option>
                        </select>
                    </div>

                    <button type="submit" class="home-search__button">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        {{ __('common.actions.search') }}
                    </button>
                </div>
            </form>

            <div class="home-hero__quick-links" aria-label="{{ __('home.popular') }}">
                @foreach ($categories->take(4) as $cat)
                    <a href="{{ route('search.index', ['place_category' => $cat->slug, 'type' => 'places']) }}"
                        class="home-hero__chip">
                        @if ($cat->icon_name)
                            <i class="fa {{ $cat->icon_name }}" aria-hidden="true"></i>
                        @endif{{ $cat->name }}
                    </a>
                @endforeach
            </div>

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

        <button class="home-hero__control home-hero__control--prev" type="button" data-hero-prev
            aria-label="{{ __('home.previous_slide') }}">
            <i class="fa fa-chevron-left" aria-hidden="true"></i>
        </button>
        <button class="home-hero__control home-hero__control--next" type="button" data-hero-next
            aria-label="{{ __('home.next_slide') }}">
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </button>

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
                    <p class="home-section__text">{{ __('home.featured_places_text') }}</p>
                </div>
            </div>
            <div class="row">
                @forelse($featuredPlaces ?? [] as $place)
                    @include('components.place-card', ['place' => $place])
                @empty
                    <div class="col-md-12 home-empty">
                        <p class="mk-text--muted">{{ __('home.empty_featured_places') }}</p>
                    </div>
                @endforelse
            </div>
            <div class="row">
                <div class="col-md-12 home-actions">
                    <a href="{{ route('places.index') }}" class="mk-button mk-button--primary mk-button--lg">
                        {{ __('home.browse_all_places') }}
                    </a>
                    <a href="{{ route('place-suggestions.create') }}"
                        class="mk-button mk-button--secondary mk-button--lg">
                        {{ __('home.suggest_place') }}
                    </a>
                    <a href="{{ route('service-suggestions.create') }}"
                        class="mk-button mk-button--secondary mk-button--lg">
                        {{ __('home.suggest_service') }}
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
                    <p class="home-section__text">{{ __('home.featured_services_text') }}</p>
                </div>
            </div>
            <div class="row">
                @forelse($featuredServices ?? [] as $service)
                    <div class="col-sm-6 col-md-4 p-2 home-card-col">
                        @include('components.service-card', ['service' => $service])
                    </div>
                @empty
                    <div class="col-md-12 home-empty">
                        <p class="mk-text--muted">{{ __('home.empty_featured_services') }}</p>
                    </div>
                @endforelse
            </div>
            <div class="row">
                <div class="col-md-12 home-actions">
                    <a href="{{ route('services.index') }}" class="mk-button mk-button--primary mk-button--lg">
                        {{ __('home.browse_all_services') }}
                    </a>
                    <a href="{{ route('service-suggestions.create') }}"
                        class="mk-button mk-button--secondary mk-button--lg">
                        {{ __('home.suggest_service') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="home-section home-section--muted">
        <div class="container">
            <div class="row">
                <div class="col-md-12 home-section__header">
                    <h2 class="home-section__title">{{ __('home.categories_title') }}</h2>
                    <p class="home-section__text">{{ __('home.categories_text') }}</p>
                </div>
            </div>
            <div class="row">
                @forelse($categories ?? [] as $category)
                    <div class="col-sm-6 col-md-4 mk-stack-sm home-card-col">
                        <a href="{{ route('search.index', ['place_category' => $category->slug, 'type' => 'places']) }}"
                            class="home-category-link">
                            <div class="home-category-card">
                                <div class="home-category-card__icon"
                                    style="color:{{ $category->color_code ?? '#10B981' }};">
                                    <i class="fa {{ $category->icon_name ?? 'fa-folder' }}"></i>
                                </div>
                                <h5 class="home-category-card__title">
                                    {{ $category->name }}</h5>
                                <p class="home-category-card__text">
                                    {{ __('home.places_count', ['count' => number_format($category->places_count)]) }}
                                </p>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-sm-12 text-center">
                        <p class="mk-text--muted">{{ __('home.empty_categories') }}</p>
                    </div>
                @endforelse
            </div>
            <div class="row mk-stack-sm">
                <div class="col-md-12 text-center">
                    <a href="{{ route('categories.index') }}" class="mk-button mk-button--secondary mk-button--lg">
                        {{ __('home.view_all_categories') }}
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
                    <p class="home-section__text">{{ __('home.recently_verified_text') }}</p>
                </div>
            </div>
            <div class="row">
                @forelse($verifiedPlaces ?? [] as $place)
                    @include('components.place-card', ['place' => $place])
                @empty
                    <div class="col-md-12 home-empty">
                        <p class="mk-text--muted">{{ __('home.empty_verified_places') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="home-section home-section--muted">
        <div class="container">
            <div class="row">
                <div class="col-md-12 home-section__header">
                    <h2 class="home-section__title">{{ __('home.latest_title') }}</h2>
                    <p class="home-section__text">{{ __('home.latest_text') }}</p>
                </div>
            </div>
            <div class="row">
                @forelse($latestPosts ?? [] as $post)
                    <div class="col-sm-6 col-md-4 mk-stack-sm home-card-col">
                        <article class="home-post-card">
                            <a href="{{ route('posts.show', $post->slug) }}" class="home-post-card__media">
                                <img src="{{ $post->image ? asset('storage/' . $post->image) : asset('assets/img/demo/property-1.jpg') }}"
                                    alt="{{ $post->title }}" loading="lazy">
                            </a>
                            <div class="home-post-card__body">
                                <p class="home-post-card__date">
                                    {{ optional($post->published_at ?? $post->created_at)->translatedFormat(__('home.date_format')) }}
                                </p>
                                <h3 class="home-post-card__title">
                                    <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                                </h3>
                                <p class="home-post-card__text">
                                    {{ Str::limit($post->excerpt ?: strip_tags($post->content), 110) }}
                                </p>
                                <a href="{{ route('posts.show', $post->slug) }}" class="home-post-card__link">
                                    {{ __('home.read_article') }}
                                </a>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-md-12 home-empty">
                        <p class="mk-text--muted">{{ __('home.empty_latest') }}</p>
                    </div>
                @endforelse
            </div>
            <div class="row mk-stack-sm">
                <div class="col-md-12 text-center">
                    <a href="{{ route('posts.index') }}" class="mk-button mk-button--secondary mk-button--lg">
                        {{ __('home.view_all_articles') }}
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
