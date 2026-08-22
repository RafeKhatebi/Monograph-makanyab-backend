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

    <div class="listing-layout">
        <div class="container">
            <div class="row places-layout">

                {{-- Sidebar Filters --}}
                <div class="col-md-3 listing-sidebar">
                    <div class="listing-filter-panel listing-filter-panel--sticky">
                        <h4 class="listing-filter-title">
                            <i class="fa fa-sliders" aria-hidden="true"></i> {{ __('places.filters') }}
                        </h4>
                        <form action="{{ route('places.index') }}" method="GET">
                            <div class="mk-form-group">
                                <label class="mk-label">{{ __('places.keywords') }}</label>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('places.keyword_placeholder') }}"
                                    class="search-filter-input">
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">{{ __('places.city') }}</label>
                                <input type="text" name="city" value="{{ request('city') }}" placeholder="{{ __('places.city') }}"
                                    class="search-filter-input">
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">{{ __('search.place_category') }}</label>
                                <select name="category" class="search-filter-select">
                                    <option value="">{{ __('search.category_all') }}</option>
                                    @foreach ($categories ?? [] as $cat)
                                        <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">{{ __('search.status') }}</label>
                                <select name="status" class="search-filter-select">
                                    <option value="">{{ __('search.any_status') }}</option>
                                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>{{ __('common.status.open') }}</option>
                                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>{{ __('common.status.closed') }}</option>
                                    <option value="temporarily_closed" {{ request('status') === 'temporarily_closed' ? 'selected' : '' }}>{{ __('common.status.temporarily_closed') }}</option>
                                </select>
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">{{ __('search.price_level') }}</label>
                                <select name="price_level" class="search-filter-select">
                                    <option value="">{{ __('search.any_price') }}</option>
                                    <option value="low" {{ request('price_level') === 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ request('price_level') === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ request('price_level') === 'high' ? 'selected' : '' }}>High</option>
                                    <option value="luxury" {{ request('price_level') === 'luxury' ? 'selected' : '' }}>Luxury</option>
                                </select>
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">{{ __('search.rating') }}</label>
                                <select name="rating" class="search-filter-select">
                                    <option value="">{{ __('places.any_rating') }}</option>
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ __('places.stars', ['count' => $i]) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mk-form-group">
                                <label class="search-check">
                                    <input type="checkbox" name="open_now" value="1" {{ request('open_now') ? 'checked' : '' }}>
                                    {{ __('places.open_now') }}
                                </label>
                            </div>
                            <div class="mk-form-group">
                                <label class="search-check">
                                    <input type="checkbox" name="verified" value="1" {{ request('verified') ? 'checked' : '' }}>
                                    {{ __('places.verified_only') }}
                                </label>
                            </div>
                            <button type="submit" class="mk-button mk-button--primary search-filter-submit">
                                {{ __('places.apply') }}
                            </button>
                            @if (request()->anyFilled(['search', 'city', 'category', 'status', 'price_level', 'rating', 'open_now', 'verified']))
                                <a href="{{ route('places.index') }}" class="search-filter-reset">{{ __('places.reset') }}</a>
                            @endif
                        </form>
                    </div>
                </div>

                {{-- Results --}}
                <div class="col-md-9">
                    <div class="search-summary">
                        <h3>
                            {{ __('places.count', ['count' => $places->total()]) }}
                            @if (request('search'))
                                for "<mark>{{ request('search') }}</mark>"
                            @endif
                        </h3>
                    </div>

                    <div class="row">
                        @forelse($places as $place)
                            @include('components.place-card', ['place' => $place])
                        @empty
                            <div class="col-md-12 listing-empty">
                                <div class="mk-empty-icon">📍</div>
                                <h3 class="mk-heading mk-heading--md">{{ __('places.no_results') }}</h3>
                                <p class="mk-text--muted mk-stack-sm">{{ __('places.adjust') }}</p>
                                <a href="{{ route('places.index') }}" class="mk-button mk-button--primary mk-button--md">{{ __('places.reset_filters') }}</a>
                            </div>
                        @endforelse
                    </div>

                    @if ($places->hasPages())
                        <div class="mk-pagination">
                            {{ $places->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
