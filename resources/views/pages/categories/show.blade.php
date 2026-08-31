@extends('layouts.app')
@section('title', $category->name . ' - ' . __('navigation.places'))
@section('content')

    <div class="mk-hero mk-hero--compact">
        <div class="container">
            <div class="category-detail-hero">
                <div class="category-detail-hero__icon">
                    <i class="fa {{ $category->icon_name ?? 'fa-folder' }}"></i>
                </div>
                <div>
                    <div class="category-detail-hero__breadcrumb">
                        <a href="{{ route('categories.index') }}">{{ __('navigation.categories') }}</a>
                        @if ($category->parent)
                            › <a href="{{ route('categories.show', $category->parent->slug) }}"
                                >{{ $category->parent->name }}</a>
                        @endif
                    </div>
                    <h1 class="category-detail-hero__title">{{ $category->name }}</h1>
                    <p class="category-detail-hero__meta">{{ __('categories.places', ['count' => $category->places_count]) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mk-page-section mk-page-section--compact">
        <div class="container">

            {{-- Subcategories toggle --}}
            @if ($subcategories->count())
                <details open class="category-subcategory-panel">
                    <summary class="category-subcategory-panel__summary">
                        <span><i class="fa fa-th-large"></i> {{ __('categories.subcategories') }}
                            ({{ $subcategories->count() }})</span>
                        <i class="fa fa-chevron-down"></i>
                    </summary>
                    <div class="category-subcategory-panel__list">
                        @foreach ($subcategories as $sub)
                            <a href="{{ route('categories.show', $sub->slug) }}" class="category-subcategory-chip">
                                <i class="fa {{ $sub->icon_name ?? 'fa-folder' }}"></i>
                                {{ $sub->name }}
                                <span>{{ $sub->places_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </details>
            @endif

            {{-- Places grid --}}
            <div class="category-results-header">
                <h3>{{ __('categories.places', ['count' => $places->total()]) }}</h3>
                <a href="{{ route('search.index', ['type' => 'place', 'category' => $category->slug]) }}"
                    >{{ __('categories.view_filters') }} <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
            </div>

            <div class="row">
                @forelse($places as $place)
                    @include('components.place-card', ['place' => $place])
                @empty
                    <div class="col-md-12 mk-ui-empty">
                        <h3 class="mk-heading mk-heading--md">{{ __('categories.empty_places_in_category') }}</h3>
                        <p class="mk-text mk-text--muted">{{ __('categories.active_places_later') }}</p>
                    </div>
                @endforelse
            </div>

            @if ($places->hasPages())
                <div class="mk-pagination-wrap">{{ $places->links() }}</div>
            @endif
        </div>
    </div>

@endsection
