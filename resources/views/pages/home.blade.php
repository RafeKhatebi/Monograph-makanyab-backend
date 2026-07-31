@extends('layouts.app')

@section('title', 'Home')

@section('content')

    {{-- Hero --}}
    <div class="home-hero">
        <div class="home-hero__bg"
            style="background-image:url('{{ asset('assets/img/slide1/slider-image-1.jpg') }}');">
        </div>
        <div class="container home-hero__content">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                    <h1 class="home-hero__title">Discover the
                        Best Places Near You</h1>
                    <p class="home-hero__text">Find restaurants, cafes, shops,
                        hotels and more — all in one place.</p>
                    <form action="{{ route('search.index') }}" method="GET" class="home-search">
                        <div class="home-search__box">
                            <div class="home-search__field">
                                <i class="fa fa-search" aria-hidden="true"></i>
                                <input type="text" name="search" placeholder="Search places, services..."
                                    class="home-search__input">
                            </div>
                            <button type="submit" class="home-search__button">
                                Search
                            </button>
                        </div>
                    </form>
                    <div class="home-hero__chips">
                        @foreach ($categories->take(4) as $cat)
                            <a href="{{ route('search.index', ['place_category' => $cat->slug, 'type' => 'places']) }}"
                                class="home-hero__chip">
                                @if ($cat->icon_name)
                                    <i class="fa {{ $cat->icon_name }}" aria-hidden="true"></i>
                                @endif{{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Featured Places --}}
    <div class="home-section home-section--muted">
        <div class="container">
            <div class="row">
                <div class="col-md-12 home-section__header">
                    <h2 class="home-section__title">Featured Places</h2>
                    <p class="home-section__text">Handpicked top-rated places loved by our community.</p>
                </div>
            </div>
            <div class="row">
                @forelse($featuredPlaces ?? [] as $place)
                    @include('components.place-card', ['place' => $place])
                @empty
                    <div class="col-md-12 home-empty">
                        <p class="mk-text--muted">No featured places yet.</p>
                    </div>
                @endforelse
            </div>
            <div class="row">
                <div class="col-md-12 home-actions">
                    <a href="{{ route('places.index') }}" class="mk-button mk-button--primary mk-button--lg">
                        Browse All Places
                    </a>
                    <a href="{{ route('place-suggestions.create') }}"
                        class="mk-button mk-button--secondary mk-button--lg">
                        Suggest a Place
                    </a>
                    <a href="{{ route('service-suggestions.create') }}"
                        class="mk-button mk-button--secondary mk-button--lg">
                        Suggest a Service
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Categories --}}
    <div class="home-section home-section--plain">
        <div class="container">
            <div class="row">
                <div class="col-md-12 home-section__header">
                    <h2 class="home-section__title">Browse by Category</h2>
                    <p class="home-section__text">Explore popular categories and find what you need.</p>
                </div>
            </div>
            <div class="row">
                @forelse($categories ?? [] as $category)
                    <div class="col-sm-6 col-md-4 mk-stack-sm">
                        <a href="{{ route('search.index', ['place_category' => $category->slug, 'type' => 'places']) }}"
                            class="home-category-link">
                            <div class="home-category-card">
                                <div class="home-category-card__icon"
                                    style="color:{{ $category->color_code ?? '#10B981' }};">
                                    <i class="fa {{ $category->icon_name ?? 'fa-folder' }}"></i>
                                </div>
                                <h5 class="home-category-card__title">
                                    {{ $category->name }}</h5>
                                <p class="home-category-card__text">{{ $category->places_count }} places</p>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-sm-12 text-center">
                        <p class="mk-text--muted">No categories available yet.</p>
                    </div>
                @endforelse
            </div>
            <div class="row mk-stack-sm">
                <div class="col-md-12 text-center">
                    <a href="{{ route('categories.index') }}" class="mk-button mk-button--secondary mk-button--lg">
                        View All Categories
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- CTA  --}}
    <div class="home-section home-section--cta">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="home-cta-card">
                        <div class="home-cta-card__body">
                            <h3 class="home-cta-card__title">Looking for a Place?
                            </h3>
                            <p class="home-cta-card__text">Search thousands of
                                places by category, location, and rating.</p>
                            <a href="{{ route('search.index') }}" class="mk-button mk-button--secondary mk-button--md">Search
                                Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="home-cta-card">
                        <div class="home-cta-card__body">
                            <h3 class="home-cta-card__title">Own a Business?</h3>
                            <p class="home-cta-card__text">List your place and
                                reach thousands of potential customers.</p>
                            <a href="{{ route('register') }}" class="mk-button mk-button--secondary mk-button--md">Get
                                Listed</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
