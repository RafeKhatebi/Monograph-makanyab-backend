@extends('layouts.app')

@section('title', 'Browse Places')

@section('content')

    <div class="page-head">
        <div class="container">
            <div class="row">
                <div class="page-head-content">
                    <h1 class="page-title">Browse Places</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="properties-area recent-property" style="background-color: #FCFCFC; padding-bottom: 60px;">
        <div class="container">
            <div class="row">

                {{-- Filter Sidebar (col-md-4) --}}
                <div class="col-md-4 p0 padding-top-40">
                    <div class="blog-asside-right pr0">
                        <div class="panel panel-default sidebar-menu wow fadeInRight animated">
                            <div class="panel-heading">
                                <h3 class="panel-title" style="padding: 15px;">Filter Places</h3>
                            </div>
                            <div class="panel-body search-widget">
                                <form action="{{ route('places.index') }}" method="GET">

                                    <div class="form-group mb-3">
                                        <label>Keywords</label>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Name or description..." value="{{ request('search') }}">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Location</label>
                                        <input type="text" name="city" class="form-control" placeholder="City"
                                            value="{{ request('city') }}">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Category</label>
                                        <select name="category" class="selectpicker show-tick form-control">
                                            <option value="">All Categories</option>
                                            @foreach ($categories ?? [] as $cat)
                                                <option value="{{ $cat->slug }}"
                                                    {{ request('category') === $cat->slug ? 'selected' : '' }}>
                                                    {{ $cat->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Status</label>
                                        <select name="status" class="selectpicker show-tick form-control">
                                            <option value="">Any Status</option>
                                            <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open
                                            </option>
                                            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>
                                                Closed</option>
                                            <option value="temporarily_closed"
                                                {{ request('status') === 'temporarily_closed' ? 'selected' : '' }}>
                                                Temporarily Closed</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Market Level</label>
                                        <select name="market_level" class="selectpicker show-tick form-control">
                                            <option value="">Any Level</option>
                                            <option value="low"
                                                {{ request('market_level') === 'low' ? 'selected' : '' }}>Small</option>
                                            <option value="medium"
                                                {{ request('market_level') === 'medium' ? 'selected' : '' }}>Medium
                                            </option>
                                            <option value="high"
                                                {{ request('market_level') === 'high' ? 'selected' : '' }}>Large</option>
                                            <option value="luxury"
                                                {{ request('market_level') === 'luxury' ? 'selected' : '' }}>Mall</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="verified" value="1"
                                                    {{ request('verified') ? 'checked' : '' }}>
                                                <strong>Verified Only</strong>
                                            </label>
                                        </div>
                                    </div>

                                    <button class="btn btn-primary btn-block btn-lg" type="submit">
                                        <i class="fa fa-search"></i> Apply Filters
                                    </button>

                                    @if (request()->anyFilled(['search', 'city', 'category', 'status', 'market_level', 'verified']))
                                        <a href="{{ route('places.index') }}"
                                            class="btn btn-link btn-block text-muted mt-2">Clear All</a>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Places Grid (col-md-8) --}}
                <div class="col-md-8 pr0 padding-top-40 properties-page">
                    <div class="row">
                        @forelse($places as $place)
                            <div class="col-sm-6 mb-4">
                                <div class="box-two proerty-item border rounded bg-white shadow-sm h-100">
                                    <div class="item-thumb position-relative"
                                        style="overflow: hidden; border-radius: 4px 4px 0 0;">
                                        <a href="{{ route('places.show', $place) }}">
                                            @if ($place->media->first())
                                                <img src="{{ asset('storage/' . $place->media->first()->file_path) }}"
                                                    class="img-responsive" alt="{{ $place->name }}"
                                                    style="height: 180px; width: 100%; object-fit: cover;">
                                            @else
                                                <img src="{{ asset('assets/img/demo/property-1.jpg') }}"
                                                    class="img-responsive" alt="{{ $place->name }}"
                                                    style="height: 180px; width: 100%; object-fit: cover;">
                                            @endif
                                        </a>

                                        @if ($place->is_verified)
                                            <span class="position-absolute"
                                                style="top: 10px; left: 10px; background: rgba(0, 184, 148, 0.9); color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px;">
                                                <i class="fa fa-check-circle"></i> Verified
                                            </span>
                                        @endif

                                        @auth
                                            <form method="POST" action="{{ route('favorites.toggle') }}"
                                                class="position-absolute" style="top: 10px; right: 10px;">
                                                @csrf
                                                <input type="hidden" name="place_id" value="{{ $place->id }}">
                                                <button type="submit" class="btn btn-light btn-xs shadow-sm"
                                                    style="border-radius: 50%; width: 30px; height: 30px; padding: 0;">
                                                    <i
                                                        class="fa {{ $place->is_favorited ? 'fa-heart text-danger' : 'fa-heart-o' }}"></i>
                                                </button>
                                            </form>
                                        @endauth
                                    </div>

                                    <div class="item-entry p-3"
                                        style="min-height: 160px; display: flex; flex-direction: column;">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="m-0 font-weight-bold" style="font-size: 16px;">
                                                <a href="{{ route('places.show', $place) }}"
                                                    class="text-dark">{{ $place->name }}</a>
                                            </h5>
                                        </div>

                                        <div class="text-muted small mt-1">
                                            @if ($place->category)
                                                <span
                                                    class="text-primary font-weight-bold">{{ $place->category->name }}</span>
                                            @endif
                                            <span class="mx-1">•</span>
                                            <i class="fa fa-map-marker"></i> {{ $place->city }}
                                        </div>

                                        <div class="my-2">
                                            @include('components.rating-stars', [
                                                'rating' => $place->avg_rating ?? 0,
                                            ])
                                        </div>

                                        <div class="mt-auto d-flex justify-content-between align-items-center pt-2"
                                            style="border-top: 1px solid #eee;">
                                            <div class="small">
                                                @include('components.status-badge', [
                                                    'status' => $place->status,
                                                ])
                                                <span class="text-muted ml-1"
                                                    style="font-size: 11px;">{{ ucfirst($place->price_level) }}</span>
                                            </div>
                                            <a href="{{ route('places.show', $place) }}"
                                                class="btn btn-default btn-xs">View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-md-12 text-center" style="padding: 100px 0;">
                                <i class="fa fa-map-o fa-4x text-muted mb-3"></i>
                                <h3 class="text-muted">No places found matching your criteria.</h3>
                                <a href="{{ route('places.index') }}" class="btn btn-primary mt-3">Reset All Filters</a>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    <div class="row">
                        <div class="col-md-12 text-center">
                            {{ $places->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
