@extends('layouts.app')
@section('title', $category->name . ' - ' . __('navigation.services'))
@php use Illuminate\Support\Str; @endphp
@section('content')

    <div class="mk-hero mk-hero--compact">
        <div class="container">
            <div class="category-detail-hero">
                <div class="category-detail-hero__icon">
                    <i class="fa {{ $category->icon_name ?? 'fa-briefcase' }}" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="category-detail-hero__breadcrumb">
                        <a href="{{ route('service-categories.index') }}">{{ __('categories.service_browse') }}</a>
                        @if ($category->parent)
                            › <a href="{{ route('service-categories.show', $category->parent->slug) }}"
                                >{{ $category->parent->name }}</a>
                        @endif
                    </div>
                    <h1 class="category-detail-hero__title">{{ $category->name }}</h1>
                    <p class="category-detail-hero__meta">{{ __('categories.services', ['count' => $category->services_count]) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mk-page-section mk-page-section--compact">
        <div class="container">

            {{-- Subcategories toggle --}}
            @if ($subcategories->count())
                <details open
                    class="category-subcategory-panel">
                    <summary class="category-subcategory-panel__summary">
                        <span><i class="fa fa-th-large" aria-hidden="true"></i> {{ __('categories.subcategories') }}
                            ({{ $subcategories->count() }})</span>
                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                    </summary>
                    <div class="category-subcategory-panel__list">
                        @foreach ($subcategories as $sub)
                            <a href="{{ route('service-categories.show', $sub->slug) }}"
                                class="category-subcategory-chip">
                                <i class="fa {{ $sub->icon_name ?? 'fa-briefcase' }}" aria-hidden="true"></i>
                                {{ $sub->name }}
                                <span>{{ $sub->services_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </details>
            @endif

            {{-- Services grid --}}
            <div class="category-results-header">
                <h3>{{ __('categories.services', ['count' => $services->total()]) }}</h3>
                <a href="{{ route('search.index', ['type' => 'service', 'category' => $category->slug]) }}">{{ __('categories.view_filters') }} <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
            </div>

            <div class="row">
                @forelse($services as $service)
                    <x-service-card :service="$service" />
                @empty
                    <div class="col-md-12 mk-ui-empty">
                        <h3 class="mk-heading mk-heading--md">{{ __('categories.empty_services_in_category') }}</h3>
                        <p class="mk-text mk-text--muted">{{ __('categories.active_services_later') }}</p>
                    </div>
                @endforelse
            </div>

            @if ($services->hasPages())
                <div class="mk-pagination-wrap">{{ $services->links() }}</div>
            @endif
        </div>
    </div>

@endsection
