@extends('layouts.app')
@section('title', 'Saved Places')
@section('content')

    {{-- Header --}}
    <div class="mk-hero">
        <div class="container">
            <h1 class="mk-hero__title">My Favorites</h1>
            <p class="mk-hero__text">Places you've saved for later.</p>
        </div>
    </div>

    <div class="mk-page-section mk-page-section--compact">
        <div class="container">
            @if ($favorites->isEmpty() && $favoriteServices->isEmpty())
                <div class="mk-card mk-card--empty">
                    <div class="mk-empty-icon"><i class="fa fa-heart" aria-hidden="true"></i></div>
                    <h3 class="mk-heading mk-heading--md">No Saved Places Yet</h3>
                    <p class="mk-text mk-text--muted mk-stack-sm">
                        Start exploring and save your favorite places to find them easily later.
                    </p>
                    <a href="{{ route('places.index') }}" class="mk-button mk-button--primary mk-button--md">
                        <i class="fa fa-search"></i> Explore Places
                    </a>
                </div>
            @else
                <div>
                    <p class="mk-count">
                        <i class="fa fa-heart" aria-hidden="true"></i>
                        {{ $favorites->total() }} saved {{ $favorites->total() === 1 ? 'place' : 'places' }}
                    </p>
                </div>
                <div class="row">
                    @foreach ($favorites as $favorite)
                        @include('components.place-card', ['place' => $favorite])
                    @endforeach
                </div>
                @if ($favorites->hasPages())
                    <div class="mk-pagination">{{ $favorites->links() }}</div>
                @endif
                @if ($favoriteServices->isNotEmpty())
                    <h2 class="mk-heading mk-heading--md">Saved services</h2>
                    <div class="row">
                        @foreach ($favoriteServices as $service)
                            <div class="col-sm-6 col-md-4 mk-stack-sm">
                                <x-service-card :service="$service" />
                            </div>
                        @endforeach
                    </div>
                    @if ($favoriteServices->hasPages())
                        <div class="mk-pagination">{{ $favoriteServices->links() }}</div>
                    @endif
                @endif
            @endif
        </div>
    </div>

@endsection
