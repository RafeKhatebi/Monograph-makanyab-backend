@extends('layouts.app')
@section('title', 'Browse Services')
@php use Illuminate\Support\Str; @endphp
@section('content')

    {{-- Header --}}
    <div class="listing-hero">
        <div class="container">
            <h1 class="listing-hero__title">Browse Services</h1>
            <p class="listing-hero__text">Find trusted service providers near you.</p>
        </div>
    </div>

    <div class="listing-layout">
        <div class="container">
            <div class="row">

                {{-- Sidebar --}}
                <div class="col-md-3 listing-sidebar">
                    <div class="listing-filter-panel">
                        <h4 class="listing-filter-title listing-filter-title--service">
                            <i class="fa fa-sliders" aria-hidden="true"></i> Filters
                        </h4>
                        <form action="{{ route('services.index') }}" method="GET">
                            <div class="mk-form-group">
                                <label class="mk-label">Keywords</label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Name, city..." class="search-filter-input">
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">City</label>
                                <input type="text" name="city" value="{{ request('city') }}" placeholder="City"
                                    class="search-filter-input">
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">Category</label>
                                <select name="category" class="search-filter-select">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->slug }}"
                                            {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">Status</label>
                                <select name="status" class="search-filter-select">
                                    <option value="">Any Status</option>
                                    <option value="open"
                                        {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="closed"
                                        {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                                    <option value="temporarily_closed"
                                        {{ request('status') === 'temporarily_closed' ? 'selected' : '' }}>Temporarily
                                        Closed</option>
                                </select>
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">Price Level</label>
                                <select name="price_level" class="search-filter-select">
                                    <option value="">Any Price</option>
                                    <option value="low" {{ request('price_level') === 'low' ? 'selected' : '' }}>Low
                                    </option>
                                    <option value="medium" {{ request('price_level') === 'medium' ? 'selected' : '' }}>
                                        Medium</option>
                                    <option value="high" {{ request('price_level') === 'high' ? 'selected' : '' }}>High
                                    </option>
                                    <option value="luxury" {{ request('price_level') === 'luxury' ? 'selected' : '' }}>
                                        Luxury</option>
                                </select>
                            </div>
                            <div class="mk-form-group">
                                <label class="mk-label">Rating</label>
                                <select name="rating" class="search-filter-select">
                                    <option value="">Any Rating</option>
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                            {{ $i }} star{{ $i > 1 ? 's' : '' }}+
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mk-form-group">
                                <label class="search-check">
                                    <input type="checkbox" name="open_now" value="1"
                                        {{ request('open_now') ? 'checked' : '' }}>
                                    Open Now
                                </label>
                            </div>
                            <div class="mk-form-group">
                                <label class="search-check">
                                    <input type="checkbox" name="verified" value="1"
                                        {{ request('verified') ? 'checked' : '' }}>
                                    Verified Only
                                </label>
                            </div>
                            <button type="submit" class="mk-button mk-button--primary search-filter-submit">
                                Apply Filters
                            </button>
                            @if (request()->anyFilled(['search', 'city', 'category', 'status', 'price_level', 'rating', 'open_now', 'verified']))
                                <a href="{{ route('services.index') }}" class="search-filter-reset">Reset
                                    All</a>
                            @endif
                        </form>
                    </div>

                    {{-- Service Categories quick links --}}
                    @if ($categories->count())
                        <div class="listing-filter-panel listing-quick-links">
                            <h5 class="listing-quick-links__title">Service Categories
                            </h5>
                            @foreach ($categories as $cat)
                                <a href="{{ route('service-categories.show', $cat->slug) }}" class="listing-quick-link">
                                    <span>{{ $cat->name }}</span>
                                    <span class="listing-quick-link__count">{{ $cat->services_count ?? 0 }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Results --}}
                <div class="col-md-9">
                    <div class="search-summary">
                        <h3>
                            {{ $services->total() }} Service{{ $services->total() !== 1 ? 's' : '' }}
                            @if (request('search'))
                                for "<mark>{{ request('search') }}</mark>"
                            @endif
                        </h3>
                    </div>

                    <div class="row">
                        @forelse($services as $service)
                            <div class="col-sm-6 col-md-4 listing-result-col">
                                <x-service-card :service="$service" />
                            </div>
                            @empty
                                <div class="col-md-12 listing-empty">
                                    <div class="mk-empty-icon">🔧</div>
                                    <h3 class="mk-heading mk-heading--md">No services
                                        found</h3>
                                    <p class="mk-text--muted mk-stack-sm">Try adjusting your filters.</p>
                                    <a href="{{ route('services.index') }}" class="mk-button mk-button--primary mk-button--md">Reset
                                        Filters</a>
                                </div>
                            @endforelse
                        </div>

                        @if ($services->hasPages())
                            <div class="mk-pagination">
                                {{ $services->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @endsection
