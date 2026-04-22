@extends('layouts.app')
@section('title', 'Search')
@php
    use Illuminate\Support\Str;
@endphp
@section('content')

    <div class="properties-area recent-property" style="background:#F8FAFC; padding: 45px 0 80px;">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="box-two" style="padding:30px; border-radius:16px; margin-bottom:20px; background:#ffffff;">
                        <h3 style="font-size:24px; font-weight:700; color:#111827; margin-bottom:25px;">
                            Search Filters
                        </h3>

                        <form action="{{ route('search.index') }}" method="GET">
                            <div class="form-group" style="margin-bottom:14px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Search</label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search businesses, categories or services..." class="form-control"
                                    style="height:48px; border-radius:10px;">
                            </div>

                            <div class="form-group" style="margin-bottom:14px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Type</label>
                                <select name="type" class="form-control" style="height:48px; border-radius:10px;">
                                    <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All Businesses</option>
                                    <option value="places" {{ request('type') == 'places' ? 'selected' : '' }}>Places</option>
                                    <option value="services" {{ request('type') == 'services' ? 'selected' : '' }}>Services</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom:14px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Location</label>
                                <input type="text" name="city" value="{{ request('city') }}" placeholder="City"
                                    class="form-control" style="height:48px; border-radius:10px;">
                            </div>

                            <div class="form-group" style="margin-bottom:14px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Province</label>
                                <input type="text" name="province" value="{{ request('province') }}"
                                    placeholder="Province" class="form-control" style="height:48px; border-radius:10px;">
                            </div>

                            <div class="form-group" style="margin-bottom:14px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">District</label>
                                <input type="text" name="district" value="{{ request('district') }}"
                                    placeholder="District" class="form-control" style="height:48px; border-radius:10px;">
                            </div>

                            <div class="form-group" style="margin-bottom:14px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Place Category</label>
                                <select name="place_category" class="form-control" style="height:48px; border-radius:10px;">
                                    <option value="">All Place Categories</option>
                                    @foreach ($placeCategories as $cat)
                                        <option value="{{ $cat->slug }}"
                                            {{ request('place_category') == $cat->slug ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom:14px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Service Category</label>
                                <select name="service_category" class="form-control"
                                    style="height:48px; border-radius:10px;">
                                    <option value="">All Service Categories</option>
                                    @foreach ($serviceCategories as $cat)
                                        <option value="{{ $cat->slug }}"
                                            {{ request('service_category') == $cat->slug ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom:14px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Status</label>
                                <select name="status" class="form-control" style="height:48px; border-radius:10px;">
                                    <option value="">Any Status</option>
                                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                    <option value="temporarily_closed"
                                        {{ request('status') == 'temporarily_closed' ? 'selected' : '' }}>
                                        Temporarily Closed</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom:14px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Price Level</label>
                                <select name="price_level" class="form-control" style="height:48px; border-radius:10px;">
                                    <option value="">Any Price</option>
                                    <option value="low" {{ request('price_level') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ request('price_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ request('price_level') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="luxury" {{ request('price_level') == 'luxury' ? 'selected' : '' }}>Luxury</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom:22px;">
                                <label style="font-weight:600; display:flex; align-items:center; gap:8px;">
                                    <input type="checkbox" name="verified" value="1"
                                        {{ request('verified') ? 'checked' : '' }}>
                                    Verified Only
                                </label>
                            </div>

                            <button type="submit"
                                style="width:100%; height:50px; border:none; border-radius:10px; background:#10B981; color:#fff; font-weight:700;">
                                Search Now
                            </button>

                            <a href="{{ route('search.index') }}"
                                style="display:block; text-align:center; margin-top:12px; color:#6B7280; text-decoration:none;">
                                Reset Filters
                            </a>
                        </form>
                    </div>
                </div>

                <div class="col-md-8 pr0 padding-top-40 properties-page">
                    @php
                        $placeCount = $places ? $places->total() : 0;
                        $serviceCount = $services ? $services->total() : 0;
                        $totalResults = $placeCount + $serviceCount;
                    @endphp

                    <div class="box-two" style="padding:24px 30px 18px; border-radius:16px; margin-bottom:20px; background:#ffffff;">
                        <div class="d-flex justify-content-between flex-wrap align-items-center">
                            <div>
                                <h3 style="font-size:24px; font-weight:700; margin-bottom:6px; color:#111827;">Search Results</h3>
                                <p style="margin:0; color:#6B7280;">
                                    {{ $totalResults }} result{{ $totalResults === 1 ? '' : 's' }}
                                    @if(request('search')) for "{{ request('search') }}" @endif
                                    @if(request('city')) in {{ request('city') }} @endif
                                </p>
                            </div>
                            <a href="{{ route('search.index') }}" class="btn btn-light"
                                style="border:1px solid #D1D5DB; color:#111827;">Clear Filters</a>
                        </div>
                    </div>

                    @if($totalResults > 0)
                        @if($showPlaces)
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h4 style="font-size:20px; font-weight:700; margin:0; color:#111827;">Places</h4>
                                        <p style="margin:4px 0 0; color:#6B7280;">{{ $placeCount }} place{{ $placeCount === 1 ? '' : 's' }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    @if($places->count())
                                        @foreach($places as $place)
                                            @include('components.place-card', ['place' => $place])
                                        @endforeach
                                    @else
                                        <div class="col-md-12 text-center py-5">
                                            <p class="text-muted mb-0">No places found for these filters.</p>
                                        </div>
                                    @endif
                                </div>

                                @if($places->hasPages())
                                    <div class="row mt-3">
                                        <div class="col-md-12 text-center">
                                            {{ $places->appends(request()->query())->links() }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if($showServices)
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h4 style="font-size:20px; font-weight:700; margin:0; color:#111827;">Services</h4>
                                        <p style="margin:4px 0 0; color:#6B7280;">{{ $serviceCount }} service{{ $serviceCount === 1 ? '' : 's' }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    @if($services && $services->count())
                                        @foreach($services as $service)
                                            <div class="col-sm-6 col-md-4 p-2">
                                                <div class="box-two proerty-item shadow-sm border rounded bg-white h-100">
                                                    <div class="item-thumb position-relative" style="overflow:hidden; border-radius: 4px 4px 0 0;">
                                                        <a href="{{ route('services.show', $service) }}">
                                                            @if($service->media->first())
                                                                <img src="{{ asset('storage/' . $service->media->first()->file_path) }}"
                                                                    class="img-responsive w-100" alt="{{ $service->name }}"
                                                                    style="height: 200px; object-fit: cover;">
                                                            @else
                                                                <img src="{{ asset('assets/img/demo/property-1.jpg') }}"
                                                                    class="img-responsive w-100" alt="{{ $service->name }}"
                                                                    style="height: 200px; object-fit: cover;">
                                                            @endif
                                                        </a>
                                                        @if($service->is_verified)
                                                            <span class="position-absolute"
                                                                style="top: 10px; left: 10px; background: rgba(16, 185, 129, 0.9); color: white; padding: 4px 10px; border-radius: 4px; font-size: 11px;">
                                                                <i class="fa fa-check-circle"></i> Verified
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="item-entry p-3" style="min-height: 220px; display: flex; flex-direction: column;">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h5 class="m-0 font-weight-bold" style="font-size: 16px;">
                                                                <a href="{{ route('services.show', $service) }}" class="text-dark">{{ $service->name }}</a>
                                                            </h5>
                                                            @if($service->category)
                                                                <span class="badge badge-light border text-dark" style="font-size: 11px;">{{ $service->category->name }}</span>
                                                            @endif
                                                        </div>

                                                        <div class="text-muted small mb-3">
                                                            <i class="fa fa-map-marker"></i> {{ $service->city ?? 'Unknown location' }}
                                                        </div>

                                                        <p class="text-muted" style="font-size: 14px; line-height: 1.6; flex-grow: 1;">
                                                            {{ Str::limit($service->description, 90) }}
                                                        </p>

                                                        <div class="mt-auto d-flex justify-content-between align-items-center pt-3" style="border-top: 1px solid #eee;">
                                                            <span class="text-muted small">{{ ucfirst($service->price_level ?? 'N/A') }}</span>
                                                            <a href="{{ route('services.show', $service) }}" class="btn btn-primary btn-xs px-3">View</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-md-12 text-center py-5">
                                            <p class="text-muted mb-0">No services found for these filters.</p>
                                        </div>
                                    @endif
                                </div>

                                @if($services && $services->hasPages())
                                    <div class="row mt-3">
                                        <div class="col-md-12 text-center">
                                            {{ $services->appends(request()->query())->links() }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="box-two text-center" style="padding: 60px 30px; border-radius:16px; background:#ffffff;">
                            <h3 style="font-size:24px; font-weight:700; color:#111827; margin-bottom:18px;">No results found</h3>
                            <p style="color:#6B7280; margin-bottom:22px;">Try updating your search keywords or filters to discover businesses.</p>
                            <a href="{{ route('search.index') }}" class="btn btn-primary">Reset search</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
