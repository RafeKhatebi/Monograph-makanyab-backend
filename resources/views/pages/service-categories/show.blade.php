@extends('layouts.app')
@section('title', $category->name . ' Services')
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
                        <a href="{{ route('service-categories.index') }}">Service Categories</a>
                        @if ($category->parent)
                            › <a href="{{ route('service-categories.show', $category->parent->slug) }}"
                                >{{ $category->parent->name }}</a>
                        @endif
                    </div>
                    <h1 class="category-detail-hero__title">{{ $category->name }}</h1>
                    <p class="category-detail-hero__meta">{{ $category->services_count }}
                        service{{ $category->services_count !== 1 ? 's' : '' }}</p>
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
                        <span><i class="fa fa-th-large" aria-hidden="true"></i> Subcategories
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
                <h3>{{ $services->total() }}
                    Service{{ $services->total() !== 1 ? 's' : '' }}</h3>
                <a href="{{ route('services.index', ['category' => $category->slug]) }}">View with filters →</a>
            </div>

            <div class="row">
                @forelse($services as $service)
                    <div class="col-sm-6 col-md-4 mk-stack-sm">
                        <x-service-card :service="$service" />
                    </div>
                @empty
                    <div class="col-md-12">
                        <div class="mk-ui-empty">
                            <h3 class="mk-ui-empty__title">No services in this category yet</h3>
                            <p class="mk-ui-empty__text">Active services will appear here once they are added.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($services->hasPages())
                <div class="mk-pagination-wrap">{{ $services->links() }}</div>
            @endif
        </div>
    </div>

@endsection
