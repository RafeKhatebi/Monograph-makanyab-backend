@extends('layouts.app')
@section('title', 'Search')
@php use Illuminate\Support\Str; @endphp
@section('content')

    <div class="search-bar">
        <div class="container">
            <form action="{{ route('search.index') }}" method="GET" id="search-form">
                <div class="row search-form-row">
                    <div class="col-md-4 col-sm-12 search-field-col">
                        <label for="search-keyword" class="search-field-label">What to search</label>
                        <div class="search-input-wrap">
                            <i class="fa fa-search" aria-hidden="true"></i>
                            <input type="text" id="search-keyword" name="search" value="{{ request('search') }}"
                                placeholder="Place, service, category, or keyword"
                                class="search-input search-input--icon" maxlength="120" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 search-field-col">
                        <label for="search-location" class="search-field-label">Where</label>
                        <div class="search-input-wrap">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <input type="text" id="search-location" name="location" value="{{ request('location') }}"
                                placeholder="City, district, province, or address"
                                class="search-input search-input--location" maxlength="120" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 search-field-col">
                        <label for="search-type" class="search-field-label">Search in</label>
                        <select id="search-type" name="type" class="search-select">
                            <option value="all" {{ request('type', 'all') == 'all' ? 'selected' : '' }}>All Types</option>
                            <option value="places" {{ request('type') == 'places' ? 'selected' : '' }}>Places</option>
                            <option value="services" {{ request('type') == 'services' ? 'selected' : '' }}>Services</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-12 search-field-col search-actions">
                        <button type="submit" class="mk-button mk-button--primary search-submit">
                            <i class="fa fa-search" aria-hidden="true"></i> Search
                        </button>
                        @if (request()->hasAny(['search', 'location', 'city', 'type', 'place_category', 'service_category', 'status', 'price_level', 'rating', 'open_now', 'verified', 'province', 'district', 'sort']))
                            <a href="{{ route('search.index') }}" class="search-reset" aria-label="Reset search">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="search-chip-list" aria-label="Search shortcuts">
                    <a href="{{ route('search.index', request()->except(['type', 'place_category', 'service_category', 'places_page', 'services_page'])) }}"
                        class="search-chip {{ request('type', 'all') === 'all' && !request('place_category') && !request('service_category') ? 'is-active' : '' }}">
                        All Results
                    </a>
                    <a href="{{ route('search.index', array_merge(request()->except(['place_category', 'service_category', 'places_page', 'services_page']), ['type' => 'places'])) }}"
                        class="search-chip {{ request('type') === 'places' && !request('place_category') ? 'is-active' : '' }}">
                        Places
                    </a>
                    <a href="{{ route('search.index', array_merge(request()->except(['place_category', 'service_category', 'places_page', 'services_page']), ['type' => 'services'])) }}"
                        class="search-chip {{ request('type') === 'services' && !request('service_category') ? 'is-active' : '' }}">
                        Services
                    </a>
                    @foreach ($placeCategories as $cat)
                        <a href="{{ route('search.index', array_merge(request()->except(['place_category', 'service_category', 'places_page', 'services_page']), ['place_category' => $cat->slug, 'type' => 'places'])) }}"
                            class="search-chip {{ request('place_category') == $cat->slug ? 'is-active' : '' }}">
                            @if ($cat->icon_name)
                                <i class="fa {{ $cat->icon_name }}" aria-hidden="true"></i>
                            @endif
                            {{ $cat->name }}
                        </a>
                    @endforeach
                    @foreach ($serviceCategories as $cat)
                        <a href="{{ route('search.index', array_merge(request()->except(['place_category', 'service_category', 'places_page', 'services_page']), ['service_category' => $cat->slug, 'type' => 'services'])) }}"
                            class="search-chip search-chip--service {{ request('service_category') == $cat->slug ? 'is-active' : '' }}">
                            @if ($cat->icon_name)
                                <i class="fa {{ $cat->icon_name }}" aria-hidden="true"></i>
                            @endif
                            {{ $cat->name }}
                        </a>
                    @endforeach
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
                Show filters
                @if ($activeFilterCount)
                    <span class="filter-active-count">{{ $activeFilterCount }}</span>
                @endif
            </button>

            <div class="row search-layout">
                <aside class="col-md-3 search-sidebar">
                    <div id="search-filter-panel" class="responsive-filter-panel search-filter-panel mk-card">
                        <h4 class="search-filter-title">
                            <i class="fa fa-sliders" aria-hidden="true"></i> Filters
                        </h4>
                        <form action="{{ route('search.index') }}" method="GET">
                            @if (request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if (request('location'))
                                <input type="hidden" name="location" value="{{ request('location') }}">
                            @endif
                            @if (request('type'))
                                <input type="hidden" name="type" value="{{ request('type') }}">
                            @endif
                            @if (request('city'))
                                <input type="hidden" name="city" value="{{ request('city') }}">
                            @endif

                            <div class="mk-form-group">
                                <label class="mk-label">Province</label>
                                <input type="text" name="province" value="{{ request('province') }}" placeholder="e.g. Herat"
                                    class="search-filter-input">
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">Sort</label>
                                <select name="sort" class="search-filter-select">
                                    <option value="relevance" @selected(request('sort', request('search') ? 'relevance' : 'newest') === 'relevance')>Most relevant</option>
                                    <option value="newest" @selected(request('sort', request('search') ? 'relevance' : 'newest') === 'newest')>Newest first</option>
                                    <option value="name_asc" @selected(request('sort') === 'name_asc')>Name A-Z</option>
                                    <option value="name_desc" @selected(request('sort') === 'name_desc')>Name Z-A</option>
                                </select>
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">District</label>
                                <input type="text" name="district" value="{{ request('district') }}" placeholder="District"
                                    class="search-filter-input">
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">Place Category</label>
                                <select name="place_category" class="search-filter-select">
                                    <option value="">All Categories</option>
                                    @foreach ($placeCategories as $cat)
                                        <option value="{{ $cat->slug }}" {{ request('place_category') == $cat->slug ? 'selected' : '' }}>
                                            {{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">Service Category</label>
                                <select name="service_category" class="search-filter-select">
                                    <option value="">All Categories</option>
                                    @foreach ($serviceCategories as $cat)
                                        <option value="{{ $cat->slug }}" {{ request('service_category') == $cat->slug ? 'selected' : '' }}>
                                            {{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">Status</label>
                                <select name="status" class="search-filter-select">
                                    <option value="">Any Status</option>
                                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                    <option value="temporarily_closed" {{ request('status') == 'temporarily_closed' ? 'selected' : '' }}>Temporarily Closed</option>
                                </select>
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">Price Level</label>
                                <select name="price_level" class="search-filter-select">
                                    <option value="">Any Price</option>
                                    <option value="low" {{ request('price_level') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ request('price_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ request('price_level') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="luxury" {{ request('price_level') == 'luxury' ? 'selected' : '' }}>Luxury</option>
                                </select>
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">Rating</label>
                                <select name="rating" class="search-filter-select">
                                    <option value="">Any Rating</option>
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} star{{ $i > 1 ? 's' : '' }}+</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mk-form-group">
                                <label class="search-check">
                                    <input type="checkbox" name="open_now" value="1" {{ request('open_now') ? 'checked' : '' }}>
                                    Open Now
                                </label>
                            </div>
                            <div class="mk-form-group">
                                <label class="search-check">
                                    <input type="checkbox" name="verified" value="1" {{ request('verified') ? 'checked' : '' }}>
                                    Verified Only
                                </label>
                            </div>
                            <button type="submit" class="mk-button mk-button--primary search-filter-submit">Apply Filters</button>
                            <a href="{{ route('search.index') }}" class="search-filter-reset">Reset All</a>
                        </form>
                    </div>
                </aside>

                <div class="col-md-9">
                    @php
                        $placeCount = $places ? $places->total() : 0;
                        $serviceCount = $services ? $services->total() : 0;
                        $totalResults = $placeCount + $serviceCount;
                    @endphp

                    <div class="search-summary">
                        <div>
                            <h3>
                                {{ $totalResults }} Result{{ $totalResults !== 1 ? 's' : '' }}
                                @if (request('search'))
                                    for "<mark>{{ request('search') }}</mark>"
                                @endif
                                @if (request('location') || request('city'))
                                    in <mark>{{ request('location') ?: request('city') }}</mark>
                                @endif
                            </h3>
                        </div>
                    </div>

                    @if ($totalResults > 0)
                        @if ($showPlaces && $places && $places->count())
                            <h4 class="search-section-title">
                                <i class="fa fa-map-marker" aria-hidden="true"></i> Places
                                <span>({{ $placeCount }})</span>
                            </h4>
                            <div class="row search-results-row">
                                @foreach ($places as $place)
                                    @php
                                        $placeMedia = $place->media->firstWhere('is_cover', true) ?? $place->media->sortBy('sort_order')->first();
                                        $placeImage = $placeMedia && Storage::disk($placeMedia->disk ?: 'public')->exists($placeMedia->file_path)
                                            ? asset('storage/' . $placeMedia->file_path)
                                            : asset('assets/img/demo/property-1.jpg');
                                    @endphp
                                    <div class="col-sm-6 col-md-4 search-result-col">
                                        <div class="search-card">
                                            <div class="search-card__media">
                                                <a href="{{ route('places.show', $place) }}">
                                                    <img src="{{ $placeImage }}" alt="{{ $place->name }}">
                                                </a>
                                                @if ($place->is_verified)
                                                    <span class="search-card__badge search-card__badge--verified">
                                                        <i class="fa fa-check-circle" aria-hidden="true"></i> Verified
                                                    </span>
                                                @endif
                                                @if ($place->status === 'open')
                                                    <span class="search-card__badge search-card__badge--status search-card__badge--open">Open</span>
                                                @elseif($place->status === 'closed')
                                                    <span class="search-card__badge search-card__badge--status search-card__badge--closed">Closed</span>
                                                @else
                                                    <span class="search-card__badge search-card__badge--status search-card__badge--warning">Temp. Closed</span>
                                                @endif
                                            </div>
                                            <div class="search-card__body">
                                                <div class="search-card__header">
                                                    <h5 class="search-card__title">
                                                        <a href="{{ route('places.show', $place) }}">{{ $place->name }}</a>
                                                    </h5>
                                                    @if ($place->category)
                                                        <span class="search-card__category">{{ $place->category->name }}</span>
                                                    @endif
                                                </div>
                                                <p class="search-card__location">
                                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                                    {{ $place->city }}@if ($place->district), {{ $place->district }}@endif
                                                </p>
                                                @if ($place->tagline)
                                                    <p class="search-card__tagline">{{ Str::limit($place->tagline, 70) }}</p>
                                                @endif
                                                <div class="search-card__footer">
                                                    <span class="search-card__price">{{ ucfirst($place->price_level) }}</span>
                                                    <a href="{{ route('places.show', $place) }}" class="search-card__link">View</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($places->hasPages())
                                <div class="search-pagination">{{ $places->appends(request()->query())->links() }}</div>
                            @endif
                        @elseif($showPlaces && $placeCount === 0 && request('type') !== 'services')
                            <div class="mk-card mk-card--center mk-stack-sm">
                                <p class="mk-text mk-text--muted">No places found for these filters.</p>
                            </div>
                        @endif

                        @if ($showServices && $services && $services->count())
                            <h4 class="search-section-title">
                                <i class="fa fa-briefcase" aria-hidden="true"></i> Services
                                <span>({{ $serviceCount }})</span>
                            </h4>
                            <div class="row">
                                @foreach ($services as $service)
                                    <div class="col-sm-6 col-md-4 search-result-col">
                                        <x-service-card :service="$service" />
                                    </div>
                                @endforeach
                            </div>
                            @if ($services->hasPages())
                                <div class="mk-pagination">{{ $services->appends(request()->query())->links() }}</div>
                            @endif
                        @elseif($showServices && $serviceCount === 0 && request('type') !== 'places')
                            <div class="mk-card mk-card--center">
                                <p class="mk-text mk-text--muted">No services found for these filters.</p>
                            </div>
                        @endif
                    @else
                        <div class="mk-card mk-card--empty">
                            <div class="mk-empty-icon"><i class="fa fa-search" aria-hidden="true"></i></div>
                            <h3 class="mk-heading mk-heading--md">No results found</h3>
                            <p class="mk-text mk-text--muted mk-stack-sm">
                                Try different keywords, a different location, or remove some filters.
                            </p>
                            <a href="{{ route('search.index') }}" class="mk-button mk-button--primary mk-button--md">
                                Reset Search
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
