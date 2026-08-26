@extends('layouts.app')
@section('title', __('categories.service_browse'))
@section('content')

    <div class="mk-hero mk-hero--search">
        <div class="container text-center">
            <h1 class="mk-hero__title">{{ __('categories.service_browse') }}</h1>
            <p class="mk-hero__text mk-hero__text--center">Find the right service provider by category.</p>
            <form action="{{ route('service-categories.index') }}" method="GET" class="mk-search-strip">
                <div class="mk-search-strip__inner">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('categories.service_search_placeholder') }}"
                        class="mk-search-strip__input">
                    <button type="submit" class="mk-search-strip__button" aria-label="{{ __('common.actions.search') }}">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mk-page-section">
        <div class="container">
            <div class="row">
                @forelse($categories as $category)
                    <div class="col-sm-6 col-md-3 mk-stack-sm">
                        <a href="{{ route('service-categories.show', $category->slug) }}" class="service-category-link">
                            <div class="service-category-card">
                                <div class="service-category-card__icon" style="color:{{ $category->color_code ?? '#10B981' }};">
                                    <i class="fa {{ $category->icon_name ?? 'fa-briefcase' }}" aria-hidden="true"></i>
                                </div>
                                <h5 class="service-category-card__title">
                                    {{ $category->name }}</h5>
                                @if ($category->parent)
                                    <p class="service-category-card__parent">{{ __('categories.in') }}
                                        {{ $category->parent->name }}</p>
                                @endif
                                <span class="service-category-card__count">
                                    {{ __('categories.services', ['count' => $category->services_count ?? 0]) }}
                                </span>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-xs-12">
                        <div class="mk-ui-empty">
                        <div class="mk-empty-icon"><i class="fa fa-briefcase" aria-hidden="true"></i></div>
                        @if (request('search'))
                            <h3 class="mk-ui-empty__title">{{ __('categories.no_matching_services') }}</h3>
                            <p class="mk-ui-empty__text">{{ __('categories.try_service_keyword') }}</p>
                            <a href="{{ route('service-categories.index') }}"
                                class="mk-button mk-button--primary mk-button--md mk-ui-empty__action">{{ __('categories.clear_search') }}</a>
                        @else
                            <h3 class="mk-ui-empty__title">{{ __('categories.empty_services') }}</h3>
                            <p class="mk-ui-empty__text">{{ __('categories.services_added_later') }}</p>
                        @endif
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

@endsection
