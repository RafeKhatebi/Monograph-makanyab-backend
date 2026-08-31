@extends('layouts.app')
@section('title', __('services.title'))
@php use Illuminate\Support\Str; @endphp
@section('content')

    {{-- Header --}}
    <div class="listing-hero">
        <div class="container">
            <h1 class="listing-hero__title">{{ __('services.title') }}</h1>
            <p class="listing-hero__text">{{ __('services.subtitle') }}</p>
        </div>
    </div>

    <div class="listing-layout listing-layout--simple">
        <div class="container">
            <div class="listing-discover-cta">
                <div>
                    <h2>{{ __('search.discover_services_title') }}</h2>
                    <p>{{ __('search.discover_services_text') }}</p>
                </div>
                <a href="{{ route('search.index', ['type' => 'service']) }}" class="mk-button mk-button--primary mk-button--md">
                    <i class="fa fa-search" aria-hidden="true"></i>
                    {{ __('navigation.discover') }}
                </a>
            </div>

            <div class="search-summary listing-summary">
                <h3>
                    {{ $services->total() }} {{ __('services.title') }}
                    @if (request('search'))
                        {{ __('search.for_keyword') }} "<mark>{{ request('search') }}</mark>"
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
                            <div class="mk-empty-icon"><i class="fa fa-briefcase" aria-hidden="true"></i></div>
                            <h3 class="mk-heading mk-heading--md">{{ __('services.no_results') }}</h3>
                            <p class="mk-text--muted mk-stack-sm">{{ __('places.adjust') }}</p>
                            <a href="{{ route('services.index') }}" class="mk-button mk-button--primary mk-button--md">{{ __('places.reset_filters') }}</a>
                        </div>
                    @endforelse
                </div>

                @if ($services->hasMorePages())
                    <div class="listing-load-more">
                        <a href="{{ $services->appends(request()->query())->nextPageUrl() }}" class="mk-button mk-button--primary mk-button--lg">
                            {{ __('services.load_more') }}
                        </a>
                    </div>
                @endif
        </div>
    </div>

    @endsection
