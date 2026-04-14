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

    <div class="properties-area recent-property" style="background-color: #FFF;">
        <div class="container">
            <div class="row">
                {{-- Filter Sidebar --}}
                <div class="col-md-6 p0 padding-top-40">
                    <div class="blog-asside-right pr0">
                        <div class="panel panel-default sidebar-menu wow fadeInRight animated">
                            <div class="panel-heading">
                                <h3 class="panel-title m-3">Filter Places</h3>
                            </div>
                            <div class="panel-body search-widget">
                                <form action="{{ route('places.index') }}" method="GET" class="form-inline">

                                    <fieldset>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <input type="text" name="search" class="form-control"
                                                    placeholder="Search places..." value="{{ request('search') }}">
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <input type="text" name="city" class="form-control" placeholder="City"
                                                    value="{{ request('city') }}">
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="padding-5">
                                        <div class="row">
                                            <div class="col-xs-12">
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
                                        </div>
                                    </fieldset>

                                    <fieldset class="padding-5">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Status</label>
                                                <select name="status" class="selectpicker show-tick form-control">
                                                    <option value="">Any Status</option>
                                                    <option value="open"
                                                        {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                                                    <option value="closed"
                                                        {{ request('status') === 'closed' ? 'selected' : '' }}>Closed
                                                    </option>
                                                    <option value="temporarily_closed"
                                                        {{ request('status') === 'temporarily_closed' ? 'selected' : '' }}>
                                                        Temporarily Closed</option>
                                                </select>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="padding-5">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <label>Marekt Level</label>
                                                <select name="market_level" class="selectpicker show-tick form-control">
                                                    <option value="">Any Level</option>
                                                    <option value="low"
                                                        {{ request('market_level') === 'low' ? 'selected' : '' }}> Small
                                                    </option>
                                                    <option value="medium"
                                                        {{ request('market_level') === 'medium' ? 'selected' : '' }}>
                                                        Medium</option>
                                                    <option value="high"
                                                        {{ request('market_level') === 'high' ? 'selected' : '' }}>Large
                                                    </option>
                                                    <option value="luxury"
                                                        {{ request('market_level') === 'luxury' ? 'selected' : '' }}>Mall
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="padding-5">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="verified" value="1"
                                                            {{ request('verified') ? 'checked' : '' }}>
                                                        Verified Only
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <input class="button btn largesearch-btn" value="Search" type="submit">
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Places Grid --}}
                <div class="col-md-6 pr0 padding-top-40 properties-page">
                    <div class="col-md-12 clear">
                        <div class="col-xs-10 page-subheader sorting pl0">
                            <p class="text-muted">
                                Showing {{ $places->firstItem() ?? 0 }}–{{ $places->lastItem() ?? 0 }}
                                of {{ $places->total() ?? 0 }} places
                            </p>
                        </div>
                    </div>

                    <div class="col-md-12 clear">
                        <div id="list-type" class="proerty-th">
                            @forelse($places as $place)
                                @include('components.place-card', ['place' => $place])
                            @empty
                                <div class="col-xs-12 text-center" style="padding: 60px 0;">
                                    <i class="fa fa-map-marker fa-4x text-muted"></i>
                                    <h3 class="text-muted">No places found</h3>
                                    <p>Try adjusting your filters.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    @if (isset($places) && $places->hasPages())
                        <div class="col-md-12">
                            <div class="pull-right">
                                {{ $places->withQueryString()->links() }}
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

@endsection
