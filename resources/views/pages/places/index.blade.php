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
            <form action="{{ route('places.index') }}" method="GET" class="listing-filter-top">
                <div class="listing-filter-top__grid">
                    <div class="listing-filter-top__field listing-filter-top__field--wide">
                        <label class="search-field-label" for="places-search">{{ __('places.keywords') }}</label>
                        <div class="search-input-wrap">
                            <i class="fa fa-search" aria-hidden="true"></i>
                            <input id="places-search" type="search" name="search" value="{{ request('search') }}"
                                placeholder="{{ __('places.keyword_placeholder') }}" class="search-input search-input--icon">
                        </div>
                    </div>
                    <div class="listing-filter-top__field">
                        <label class="search-field-label" for="places-city">{{ __('places.city') }}</label>
                        <div class="search-input-wrap">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <input id="places-city" type="text" name="city" value="{{ request('city') }}"
                                placeholder="{{ __('places.city') }}" class="search-input search-input--location">
                        </div>
                    </div>
                    <div class="listing-filter-top__field">
                        <label class="search-field-label" for="places-category">{{ __('search.place_category') }}</label>
                        <select id="places-category" name="category" class="search-select">
                            <option value="">{{ __('search.category_all') }}</option>
                            @foreach ($categories ?? [] as $cat)
                                <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="listing-filter-top__field">
                        <label class="search-field-label" for="places-status">{{ __('search.status') }}</label>
                        <select id="places-status" name="status" class="search-select">
                            <option value="">{{ __('search.any_status') }}</option>
                            <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>{{ __('common.status.open') }}</option>
                            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>{{ __('common.status.closed') }}</option>
                            <option value="temporarily_closed" {{ request('status') === 'temporarily_closed' ? 'selected' : '' }}>{{ __('common.status.temporarily_closed') }}</option>
                        </select>
                    </div>
                    <button type="submit" class="mk-button mk-button--primary listing-filter-top__submit">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        {{ __('places.apply') }}
                    </button>
                </div>
                <div class="listing-filter-top__checks">
                    <label class="search-check">
                        <input type="checkbox" name="open_now" value="1" {{ request('open_now') ? 'checked' : '' }}>
                        {{ __('places.open_now') }}
                    </label>
                    <label class="search-check">
                        <input type="checkbox" name="verified" value="1" {{ request('verified') ? 'checked' : '' }}>
                        {{ __('places.verified_only') }}
                    </label>
                    @if (request()->anyFilled(['search', 'city', 'category', 'status', 'open_now', 'verified']))
                        <a href="{{ route('places.index') }}" class="listing-filter-top__reset">{{ __('places.reset') }}</a>
                    @endif
                </div>
            </form>

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
