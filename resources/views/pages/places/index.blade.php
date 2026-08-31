@extends('layouts.app')
@section('title', __('places.title'))
@section('content')

    {{-- Page Header --}}
    <div class="listing-hero">
        <div class="container">
            <h1 class="listing-hero__title">{{ __('places.title') }}</h1>
            <p class="listing-hero__text">{{ __('places.subtitle') }}</p>
        </div>
    </div>

    <div class="listing-layout listing-layout--simple">
        <div class="container">
            <div class="listing-discover-cta">
                <div>
                    <h2>{{ __('search.discover_places_title') }}</h2>
                    <p>{{ __('search.discover_places_text') }}</p>
                </div>
                <a href="{{ route('search.index', ['type' => 'place']) }}" class="mk-button mk-button--primary mk-button--md">
                    <i class="fa fa-search" aria-hidden="true"></i>
                    {{ __('navigation.discover') }}
                </a>
            </div>

            <div class="search-summary listing-summary p-6 m-6">
                <h3>
                    {{ __('places.count', ['count' => $places->total()]) }}
                    @if (request('search'))
                        {{ __('search.for_keyword') }} "<mark>{{ request('search') }}</mark>"
                    @endif
                </h3>
            </div>

            <div class="row">
                @forelse($places as $place)
                    @include('components.place-card', ['place' => $place])
                @empty
                    <div class="col-md-12 listing-empty">
                        <div class="mk-empty-icon"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                        <h3 class="mk-heading mk-heading--md">{{ __('places.no_results') }}</h3>
                        <p class="mk-text--muted mk-stack-sm">{{ __('places.adjust') }}</p>
                        <a href="{{ route('places.index') }}" class="mk-button mk-button--primary mk-button--md">{{ __('places.reset_filters') }}</a>
                    </div>
                @endforelse
            </div>

            @if ($places->hasMorePages())
                <div class="listing-load-more">
                    <a href="{{ $places->appends(request()->query())->nextPageUrl() }}" class="mk-button mk-button--primary mk-button--lg">
                        {{ __('places.load_more') }}
                    </a>
                </div>
            @endif
        </div>
    </div>

@endsection
