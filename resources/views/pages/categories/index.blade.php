@extends('layouts.app')

@section('title', 'Categories')

@section('content')

    <div class="page-head">
        <div class="container">
            <div class="row">
                <div class="page-head-content">
                    <h1 class="page-title" style="text-align: center;">Browse Categories</h1>
                    <p class="text-muted" style="text-align: center;">Discover places by category and find your next favorite
                        spot.</p>
                </div>
                <div class="search-box-container" style="max-width: 600px; margin:0px auto 50px;">
                    <form action="{{ route('categories.index') }}" method="GET">
                        <div class="input-group shadow-sm">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search categories (e.g. Restaurants, Parks...)" value="{{ request('search') }}"
                                style="height: 50px; border-radius: 25px 0 0 25px; border-right: none; padding-left: 25px;">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit"
                                    style="height: 50px; border-radius: 0 25px 25px 0; padding: 0 30px;">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="content-area home-area-1 recent-property" style="background-color: #FCFCFC; padding-bottom: 55px;">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1 col-sm-12 text-center page-title">
                    <h2>All Categories</h2>
                    <p>Explore places by category and find exactly what you need.</p>
                </div>
            </div>

            <div class="row">
                @forelse($categories as $category)
                    <div class="col-sm-6 col-md-3" style="margin-bottom: 30px;">
                        <a href="{{ route('places.index', ['category' => $category->slug]) }}"
                            style="text-decoration:none;">
                            <div class="box-two proerty-item" style="text-align:center; padding: 30px 15px;">
                                <div style="font-size: 48px; margin-bottom: 15px;">
                                    <i class="{{ $category->icon_name ?? 'pe-7s-map-marker' }}"></i>
                                </div>
                                <h4>{{ $category->name }}</h4>
                                @if ($category->parent)
                                    <small class="text-muted">in {{ $category->parent->name }}</small>
                                @endif
                                <div class="dot-hr"></div>
                                <span class="proerty-price">
                                    {{ $category->places_count ?? 0 }} places
                                </span>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-xs-12 text-center" style="padding: 60px 0;">
                        <i class="fa fa-th-large fa-4x text-muted"></i>
                        <h3 class="text-muted">No categories yet</h3>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

@endsection
