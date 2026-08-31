@extends('layouts.app')
@section('title', __('navigation.categories'))
@section('content')

    {{-- Header --}}
    <div class="mk-hero mk-hero--search">
        <div class="container text-center">
            <h1 class="mk-hero__title">{{ __('categories.browse') }}</h1>
            <p class="mk-hero__text">{{ __('categories.browse_intro') }}</p>
            <form action="{{ route('categories.index') }}" method="GET" class="mk-search-strip">
                <div class="mk-search-strip__inner">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('categories.search_placeholder') }}"
                        class="mk-search-strip__input">
                    <button type="submit" class="mk-search-strip__button" aria-label="{{ __('categories.search_placeholder') }}">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mk-page-section">
        <div class="container">
            <div class="row">
                @forelse($categories as $category)
                    <div class="col-sm-6 col-md-3 category-grid-col">
                        <a href="{{ route('categories.show', $category->slug) }}" class="category-link">
                            <div class="category-card">
                                <div class="category-card__icon"
                                    style="color:{{ $category->color_code ?? '#10B981' }};">
                                    <i class="fa {{ $category->icon_name ?: 'fa-map-marker' }}" aria-hidden="true"></i>
                                </div>
                                <h5 class="category-card__title">
                                    {{ $category->name }}</h5>
                                @if ($category->parent)
                                    <p class="category-card__parent">{{ __('categories.in') }}
                                        {{ $category->parent->name }}</p>
                                @endif
                                <span class="category-card__count">
                                    {{ __('categories.places', ['count' => $category->places_count ?? 0]) }}
                                </span>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-xs-12 mk-ui-empty">
                        <div class="mk-empty-icon"><i class="fa fa-folder-open" aria-hidden="true"></i></div>
                        @if (request('search'))
                            <h3 class="mk-heading mk-heading--md">{{ __('categories.no_matching') }}</h3>
                            <p class="mk-text mk-text--muted">{{ __('categories.try_keyword') }}</p>
                            <a href="{{ route('categories.index') }}" class="mk-button mk-button--primary mk-button--md mk-ui-empty__action">{{ __('categories.clear_search') }}</a>
                        @else
                            <h3 class="mk-heading mk-heading--md">{{ __('categories.empty') }}</h3>
                            <p class="mk-text mk-text--muted">{{ __('categories.added_later') }}</p>
                        @endif
                    </div>
                @endforelse
            </div>
            @if ($categories->hasPages())
                <div class="mk-pagination-wrap">{{ $categories->links() }}</div>
            @endif
        </div>
    </div>

@endsection
