@extends('layouts.app')
@section('title', __('search.title'))
@php use Illuminate\Support\Str; @endphp
@section('content')

    <div class="search-bar">
        <div class="container">
            <form action="{{ route('search.index') }}" method="GET" id="search-form">
                <div class="row search-form-row">
                    <div class="col-md-2 col-sm-6 search-field-col">
                        <label for="search-type" class="search-field-label">{{ __('search.search_in') }}</label>
                        <select id="search-type" name="type" class="search-select" data-search-type>
                            <option value="places" {{ $selectedType === 'places' ? 'selected' : '' }}>{{ __('search.places') }}</option>
                            <option value="services" {{ $selectedType === 'services' ? 'selected' : '' }}>{{ __('search.services') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-12 search-field-col">
                        <label for="search-keyword" class="search-field-label">{{ __('search.what') }}</label>
                        <div class="search-input-wrap">
                            <i class="fa fa-search" aria-hidden="true"></i>
                            <input type="text" id="search-keyword" name="search" value="{{ request('search') }}"
                                placeholder="{{ __('search.keyword_placeholder') }}"
                                class="search-input search-input--icon" maxlength="120" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 search-field-col">
                        <label for="search-location" class="search-field-label">{{ __('search.where') }}</label>
                        <div class="search-input-wrap">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <input type="text" id="search-location" name="location" value="{{ request('location') }}"
                                placeholder="{{ __('search.location_placeholder') }}"
                                class="search-input search-input--location" maxlength="120" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 search-field-col search-actions">
                        <button type="submit" class="mk-button mk-button--primary search-submit">
                            <i class="fa fa-search" aria-hidden="true"></i> {{ __('common.actions.search') }}
                        </button>
                        @if (request()->hasAny(['search', 'location', 'city', 'type', 'place_category', 'service_category', 'status', 'price_level', 'rating', 'open_now', 'verified', 'province', 'district', 'sort']))
                            <a href="{{ route('search.index') }}" class="search-reset" aria-label="{{ __('search.reset') }}">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="search-chip-list" aria-label="{{ __('search.shortcuts') }}">
                    <a href="{{ route('search.index', array_merge(request()->except(['place_category', 'service_category', 'places_page', 'services_page']), ['type' => 'places'])) }}"
                        class="search-chip {{ $selectedType === 'places' && !request('place_category') ? 'is-active' : '' }}">
                        {{ __('search.places') }}
                    </a>
                    <a href="{{ route('search.index', array_merge(request()->except(['place_category', 'service_category', 'places_page', 'services_page']), ['type' => 'services'])) }}"
                        class="search-chip {{ $selectedType === 'services' && !request('service_category') ? 'is-active' : '' }}">
                        {{ __('search.services') }}
                    </a>
                    <span class="search-chip-divider" aria-hidden="true"></span>
                    <span data-category-group="places" class="search-category-group {{ $selectedType === 'services' ? 'is-hidden' : '' }}">
                        @foreach ($placeCategories as $cat)
                            <a href="{{ route('search.index', array_merge(request()->except(['place_category', 'service_category', 'places_page', 'services_page']), ['place_category' => $cat->slug, 'type' => 'places'])) }}"
                                class="search-chip {{ request('place_category') == $cat->slug ? 'is-active' : '' }}">
                                @if ($cat->icon_name)
                                    <i class="fa {{ $cat->icon_name }}" aria-hidden="true"></i>
                                @endif
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </span>
                    <span data-category-group="services" class="search-category-group {{ $selectedType !== 'services' ? 'is-hidden' : '' }}">
                        @foreach ($serviceCategories as $cat)
                            <a href="{{ route('search.index', array_merge(request()->except(['place_category', 'service_category', 'places_page', 'services_page']), ['service_category' => $cat->slug, 'type' => 'services'])) }}"
                                class="search-chip search-chip--service {{ request('service_category') == $cat->slug ? 'is-active' : '' }}">
                                @if ($cat->icon_name)
                                    <i class="fa {{ $cat->icon_name }}" aria-hidden="true"></i>
                                @endif
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </span>
                </div>
            </form>
        </div>
    </div>

    <div class="mk-page-section mk-page-section--compact">
        <div class="container">
            @php
                $activeFilterCount = collect(['location', 'province', 'district', 'place_category', 'service_category', 'status', 'price_level', 'rating', 'open_now', 'verified'])
                    ->filter(fn ($key) => request()->filled($key))
                    ->count()
                    + (request()->filled('sort') && request('sort') !== 'newest' ? 1 : 0);
            @endphp
            <button type="button" class="filter-toggle" aria-controls="search-filter-panel" aria-expanded="false">
                <i class="fa fa-sliders" aria-hidden="true"></i>
                {{ __('search.show_filters') }}
                @if ($activeFilterCount)
                    <span class="filter-active-count">{{ $activeFilterCount }}</span>
                @endif
            </button>

            <div class="row search-layout">
                <aside class="col-md-3 search-sidebar">
                    <div id="search-filter-panel" class="responsive-filter-panel search-filter-panel mk-card">
                        <h4 class="search-filter-title">
                            <i class="fa fa-sliders" aria-hidden="true"></i> {{ __('search.filters') }}
                        </h4>
                        <form action="{{ route('search.index') }}" method="GET">
                            @if (request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if (request('location'))
                                <input type="hidden" name="location" value="{{ request('location') }}">
                            @endif
                            <input type="hidden" name="type" value="{{ $selectedType }}">
                            @if (request('city'))
                                <input type="hidden" name="city" value="{{ request('city') }}">
                            @endif

                            <div class="mk-form-group">
                                <label class="mk-label">{{ __('search.province') }}</label>
                                <input type="text" name="province" value="{{ request('province') }}" placeholder="{{ __('search.province') }}"
                                    class="search-filter-input">
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">{{ __('search.sort') }}</label>
                                <select name="sort" class="search-filter-select">
                                    <option value="relevance" @selected(request('sort', request('search') ? 'relevance' : 'newest') === 'relevance')>{{ __('search.most_relevant') }}</option>
                                    <option value="newest" @selected(request('sort', request('search') ? 'relevance' : 'newest') === 'newest')>{{ __('search.newest') }}</option>
                                    <option value="name_asc" @selected(request('sort') === 'name_asc')>{{ __('search.name_az') }}</option>
                                    <option value="name_desc" @selected(request('sort') === 'name_desc')>{{ __('search.name_za') }}</option>
                                </select>
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">{{ __('search.district') }}</label>
                                <input type="text" name="district" value="{{ request('district') }}" placeholder="{{ __('search.district') }}"
                                    class="search-filter-input">
                            </div>
                            <div class="mk-form-group {{ $selectedType === 'services' ? 'is-hidden' : '' }}" data-filter-category-group="places">
                                <label class="mk-label">{{ __('search.place_category') }}</label>
                                <select name="place_category" class="search-filter-select">
                                    <option value="">{{ __('search.category_all') }}</option>
                                    @foreach ($placeCategories as $cat)
                                        <option value="{{ $cat->slug }}" {{ request('place_category') == $cat->slug ? 'selected' : '' }}>
                                            {{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mk-form-group {{ $selectedType !== 'services' ? 'is-hidden' : '' }}" data-filter-category-group="services">
                                <label class="mk-label">{{ __('search.service_category') }}</label>
                                <select name="service_category" class="search-filter-select">
                                    <option value="">{{ __('search.category_all') }}</option>
                                    @foreach ($serviceCategories as $cat)
                                        <option value="{{ $cat->slug }}" {{ request('service_category') == $cat->slug ? 'selected' : '' }}>
                                            {{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">{{ __('search.status') }}</label>
                                <select name="status" class="search-filter-select">
                                    <option value="">{{ __('search.any_status') }}</option>
                                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>{{ __('common.status.open') }}</option>
                                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>{{ __('common.status.closed') }}</option>
                                    <option value="temporarily_closed" {{ request('status') == 'temporarily_closed' ? 'selected' : '' }}>{{ __('common.status.temporarily_closed') }}</option>
                                </select>
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">{{ __('search.price_level') }}</label>
                                <select name="price_level" class="search-filter-select">
                                    <option value="">{{ __('search.any_price') }}</option>
                                    <option value="low" {{ request('price_level') == 'low' ? 'selected' : '' }}>{{ __('common.price.low') }}</option>
                                    <option value="medium" {{ request('price_level') == 'medium' ? 'selected' : '' }}>{{ __('common.price.medium') }}</option>
                                    <option value="high" {{ request('price_level') == 'high' ? 'selected' : '' }}>{{ __('common.price.high') }}</option>
                                    <option value="luxury" {{ request('price_level') == 'luxury' ? 'selected' : '' }}>{{ __('common.price.luxury') }}</option>
                                </select>
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">{{ __('search.rating') }}</label>
                                <select name="rating" class="search-filter-select">
                                    <option value="">{{ __('search.any_rating') }}</option>
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ __('search.rating_min', ['count' => $i]) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mk-form-group">
                                <label class="search-check">
                                    <input type="checkbox" name="open_now" value="1" {{ request('open_now') ? 'checked' : '' }}>
                                    {{ __('search.open_now') }}
                                </label>
                            </div>
                            <div class="mk-form-group">
                                <label class="search-check">
                                    <input type="checkbox" name="verified" value="1" {{ request('verified') ? 'checked' : '' }}>
                                    {{ __('search.verified') }}
                                </label>
                            </div>
                            <button type="submit" class="mk-button mk-button--primary search-filter-submit">{{ __('search.apply') }}</button>
                            <a href="{{ route('search.index') }}" class="search-filter-reset">{{ __('search.clear') }}</a>
                        </form>
                    </div>
                </aside>

                <div class="col-md-9">
                    @php
                        $placeCount = $places ? $places->total() : 0;
                        $serviceCount = $services ? $services->total() : 0;
                        $totalResults = $placeCount + $serviceCount;
                    @endphp
                 @if ($totalResults > 0)
                        @if ($showPlaces && $places && $places->count())
                            <h4 class="search-section-title">
                                <i class="fa fa-map-marker" aria-hidden="true"></i> {{ __('search.places') }}
                                <span>({{ $placeCount }})</span>
                            </h4>
                            <div class="row search-results-row">
                                @foreach ($places as $place)
                                    @include('components.place-card', ['place' => $place])
                                @endforeach
                            </div>
                            <div class="search-pagination search-see-more">
                                <a href="{{ route('places.index', array_filter([
                                    'search' => request('search'),
                                    'city' => request('location') ?: request('city'),
                                    'category' => request('place_category'),
                                    'status' => request('status'),
                                    'price_level' => request('price_level'),
                                    'rating' => request('rating'),
                                    'open_now' => request('open_now'),
                                    'verified' => request('verified'),
                                ])) }}" class="mk-button mk-button--primary mk-button--md">
                                    {{ __('search.see_more_places') }}
                                </a>
                            </div>
                        @elseif($showPlaces && $placeCount === 0 && request('type') !== 'services')
                            <div class="mk-card mk-card--center mk-stack-sm">
                                <p class="mk-text mk-text--muted">{{ __('search.no_places') }}</p>
                            </div>
                        @endif

                        @if ($showServices && $services && $services->count())
                            <h4 class="search-section-title">
                                <i class="fa fa-briefcase" aria-hidden="true"></i> {{ __('search.services') }}
                                <span>({{ $serviceCount }})</span>
                            </h4>
                            <div class="row">
                                @foreach ($services as $service)
                                    <div class="col-sm-6 col-md-4 search-result-col">
                                        <x-service-card :service="$service" />
                                    </div>
                                @endforeach
                            </div>
                            <div class="mk-pagination search-see-more">
                                <a href="{{ route('services.index', array_filter([
                                    'search' => request('search'),
                                    'city' => request('location') ?: request('city'),
                                    'category' => request('service_category'),
                                    'status' => request('status'),
                                    'price_level' => request('price_level'),
                                    'rating' => request('rating'),
                                    'open_now' => request('open_now'),
                                    'verified' => request('verified'),
                                ])) }}" class="mk-button mk-button--primary mk-button--md">
                                    {{ __('search.see_more_services') }}
                                </a>
                            </div>
                        @elseif($showServices && $serviceCount === 0 && request('type') !== 'places')
                            <div class="mk-card mk-card--center">
                                <p class="mk-text mk-text--muted">{{ __('search.no_services') }}</p>
                            </div>
                        @endif
                    @else
                        <div class="mk-card mk-card--empty">
                            <div class="mk-empty-icon"><i class="fa fa-search" aria-hidden="true"></i></div>
                            <h3 class="mk-heading mk-heading--md">{{ __('search.no_results') }}</h3>
                            <p class="mk-text mk-text--muted mk-stack-sm">
                                {{ __('search.no_results_help') }}
                            </p>
                            <a href="{{ route('search.index') }}" class="mk-button mk-button--primary mk-button--md">
                                {{ __('search.reset_search') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
