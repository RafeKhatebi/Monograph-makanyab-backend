@extends('layouts.app')

@section('title', 'Search')

@section('content')

    <div class="content-area recent-property" style="background:#F8FAFC; padding:70px 0;">
        <div class="container" style="margin-bottom:40px;">
            <div class="box-two" style="padding:40px; border-radius:18px;">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 style="font-size:40px; font-weight:700; color:#111827; margin-bottom:15px;">Search Local Places & Services</h1>
                        <p style="font-size:17px; color:#6B7280; line-height:1.8; margin:0; max-width:720px;">
                            Use smart filters to find verified businesses, services, and local places in your city. Search by
                            keyword, category, location and status to get the best match.
                        </p>
                    </div>
                    <div class="col-md-4">
                        <div style="padding:25px; background:#ECFDF5; border-radius:16px;">
                            <div style="font-size:20px; font-weight:700; color:#065F46; margin-bottom:12px;">Filter Summary</div>
                            <div style="font-size:15px; color:#4B5563; line-height:1.8;">
                                {{ $placeCategories->count() }} place categories available<br>
                                {{ $serviceCategories->count() }} service categories available<br>
                                Results update when you apply filters.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="box-two" style="padding:30px; border-radius:16px; margin-bottom:30px;">
                        <h3 style="font-size:28px; font-weight:700; color:#111827; margin-bottom:25px;">
                            Filters
                        </h3>

                        <form action="{{ route('search.index') }}" method="GET">
                            <div class="form-group" style="margin-bottom:18px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Type</label>
                                <select name="type" class="form-control" style="height:48px; border-radius:10px;">
                                    <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All</option>
                                    <option value="places" {{ request('type') == 'places' ? 'selected' : '' }}>Places</option>
                                    <option value="services" {{ request('type') == 'services' ? 'selected' : '' }}>Services</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom:18px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Keyword</label>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or description..."
                                    class="form-control" style="height:48px; border-radius:10px;">
                            </div>

                            <div class="form-group" style="margin-bottom:18px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Place Category</label>
                                <select name="place_category" class="form-control" style="height:48px; border-radius:10px;">
                                    <option value="">All Place Categories</option>
                                    @foreach ($placeCategories as $cat)
                                        <option value="{{ $cat->slug }}" {{ request('place_category') == $cat->slug ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom:18px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Service Category</label>
                                <select name="service_category" class="form-control" style="height:48px; border-radius:10px;">
                                    <option value="">All Service Categories</option>
                                    @foreach ($serviceCategories as $cat)
                                        <option value="{{ $cat->slug }}"
                                            {{ request('service_category') == $cat->slug ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom:18px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">City</label>
                                <input type="text" name="city" value="{{ request('city') }}" placeholder="City..."
                                    class="form-control" style="height:48px; border-radius:10px;">
                            </div>

                            <div class="form-group" style="margin-bottom:18px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Province</label>
                                <input type="text" name="province" value="{{ request('province') }}" placeholder="Province..."
                                    class="form-control" style="height:48px; border-radius:10px;">
                            </div>

                            <div class="form-group" style="margin-bottom:18px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">District</label>
                                <input type="text" name="district" value="{{ request('district') }}" placeholder="District..."
                                    class="form-control" style="height:48px; border-radius:10px;">
                            </div>

                            <div class="form-group" style="margin-bottom:18px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Status</label>
                                <select name="status" class="form-control" style="height:48px; border-radius:10px;">
                                    <option value="">Any Status</option>
                                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                    <option value="temporarily_closed" {{ request('status') == 'temporarily_closed' ? 'selected' : '' }}>
                                        Temporarily Closed</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom:18px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Price Level</label>
                                <select name="price_level" class="form-control" style="height:48px; border-radius:10px;">
                                    <option value="">Any Price</option>
                                    <option value="low" {{ request('price_level') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ request('price_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ request('price_level') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="luxury" {{ request('price_level') == 'luxury' ? 'selected' : '' }}>Luxury</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom:25px;">
                                <label style="font-weight:600;">
                                    <input type="checkbox" name="verified" value="1" {{ request('verified') ? 'checked' : '' }}>
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

                <div class="col-md-8">
                    <div class="box-two" style="padding:25px 30px; border-radius:16px; margin-bottom:30px;">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h3 style="font-size:28px; font-weight:700; color:#111827; margin:0;">Search Results</h3>
                            </div>
                            <div class="col-md-6 text-right">
                                <span style="color:#6B7280;">
                                    @if($showPlaces && $showServices)
                                        {{ ($places ? $places->total() : 0) + ($services ? $services->total() : 0) }} results found
                                    @elseif($showPlaces)
                                        {{ $places->total() }} places found
                                    @else
                                        {{ $services->total() }} services found
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div style="margin-top:18px; display:flex; flex-wrap:wrap; gap:10px;">
                            @foreach(['type' => request('type'), 'place_category' => request('place_category'), 'service_category' => request('service_category'), 'city' => request('city'), 'province' => request('province'), 'district' => request('district'), 'status' => request('status'), 'price_level' => request('price_level'), 'verified' => request('verified') ? 'Verified' : null] as $label => $value)
                                @if($value)
                                    @php
                                        $labelText = match($label) {
                                            'type' => 'Type',
                                            'place_category' => 'Place Category',
                                            'service_category' => 'Service Category',
                                            'city' => 'City',
                                            'province' => 'Province',
                                            'district' => 'District',
                                            'status' => 'Status',
                                            'price_level' => 'Price',
                                            'verified' => 'Verified',
                                            default => ucfirst($label),
                                        };
                                    @endphp
                                    <span style="background:#F3F4F6; color:#374151; padding:8px 14px; border-radius:999px; font-size:13px;">{{ $labelText }}: {{ $value }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    @if($showPlaces && $places && $places->count())
                        <div class="box-two" style="padding:25px 30px; border-radius:16px; margin-bottom:30px;">
                            <h4 style="font-size:22px; font-weight:700; margin-bottom:20px;">Places</h4>
                        </div>
                        @foreach ($places as $place)
                            <div class="box-two" style="padding:0; border-radius:16px; overflow:hidden; margin-bottom:30px;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <a href="{{ route('places.show', $place) }}">
                                            @if ($place->media->first())
                                                <img src="{{ asset('storage/' . $place->media->first()->file_path) }}"
                                                    style="width:100%; height:240px; object-fit:cover;">
                                            @else
                                                <img src="{{ asset('assets/img/demo/property-1.jpg') }}"
                                                    style="width:100%; height:240px; object-fit:cover;">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="col-md-8">
                                        <div style="padding:28px;">
                                            <div style="margin-bottom:10px;">
                                                @if ($place->category)
                                                    <span style="background:#ECFDF5; color:#10B981; padding:6px 12px; border-radius:50px; font-size:13px; font-weight:600;">
                                                        {{ $place->category->name }}
                                                    </span>
                                                @endif
                                                @if ($place->is_verified)
                                                    <span style="margin-left:8px; color:#10B981; font-size:13px; font-weight:700;">✓ Verified</span>
                                                @endif
                                            </div>
                                            <h2 style="font-size:28px; font-weight:700; margin-bottom:12px;">
                                                <a href="{{ route('places.show', $place) }}"
                                                    style="color:#111827; text-decoration:none;">{{ $place->name }}</a>
                                            </h2>
                                            <div style="font-size:15px; color:#6B7280; margin-bottom:10px;">
                                                📍 {{ $place->city }}@if($place->district), {{ $place->district }}@endif
                                            </div>
                                            <div style="margin-bottom:15px;">
                                                @include('components.rating-stars', ['rating' => $place->avg_rating ?? 0])
                                            </div>
                                            <p style="font-size:15px; color:#6B7280; line-height:1.8; margin-bottom:18px;">
                                                {{ Str::limit(strip_tags($place->description), 140) }}
                                            </p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    @include('components.status-badge', ['status' => $place->status])
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <a href="{{ route('places.show', $place) }}"
                                                        style="display:inline-block; background:#10B981; color:#fff; padding:10px 24px; border-radius:10px; text-decoration:none; font-weight:600;">View Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="text-center">
                            {{ $places->appends(request()->query())->links() }}
                        </div>
                    @elseif($showPlaces)
                        <div class="box-two text-center" style="padding:70px 30px; border-radius:16px; margin-bottom:30px;">
                            <div style="font-size:70px; margin-bottom:20px;">🔍</div>
                            <h3 style="font-size:30px; font-weight:700; color:#111827; margin-bottom:15px;">No Places Found</h3>
                            <p style="font-size:16px; color:#6B7280;">Try adjusting filters or searching another keyword.</p>
                        </div>
                    @endif

                    @if($showServices && $services && $services->count())
                        <div class="box-two" style="padding:25px 30px; border-radius:16px; margin-bottom:30px;">
                            <h4 style="font-size:22px; font-weight:700; margin-bottom:20px;">Services</h4>
                        </div>
                        @foreach ($services as $service)
                            <div class="box-two" style="padding:0; border-radius:16px; overflow:hidden; margin-bottom:30px;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <a href="{{ route('services.show', $service) }}">
                                            @if ($service->media->first())
                                                <img src="{{ asset('storage/' . $service->media->first()->file_path) }}"
                                                    style="width:100%; height:240px; object-fit:cover;">
                                            @else
                                                <img src="{{ asset('assets/img/demo/property-1.jpg') }}"
                                                    style="width:100%; height:240px; object-fit:cover;">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="col-md-8">
                                        <div style="padding:28px;">
                                            <div style="margin-bottom:10px;">
                                                @if ($service->category)
                                                    <span style="background:#ECFDF5; color:#10B981; padding:6px 12px; border-radius:50px; font-size:13px; font-weight:600;">
                                                        {{ $service->category->name }}
                                                    </span>
                                                @endif
                                                @if ($service->is_verified)
                                                    <span style="margin-left:8px; color:#10B981; font-size:13px; font-weight:700;">✓ Verified</span>
                                                @endif
                                            </div>
                                            <h2 style="font-size:28px; font-weight:700; margin-bottom:12px;">
                                                <a href="{{ route('services.show', $service) }}"
                                                    style="color:#111827; text-decoration:none;">{{ $service->name }}</a>
                                            </h2>
                                            <div style="font-size:15px; color:#6B7280; margin-bottom:10px;">
                                                📍 {{ $service->city }}@if($service->district), {{ $service->district }}@endif
                                            </div>
                                            <p style="font-size:15px; color:#6B7280; line-height:1.8; margin-bottom:18px;">
                                                {{ Str::limit(strip_tags($service->description), 140) }}
                                            </p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span style="background:#F3F4F6; color:#374151; padding:8px 14px; border-radius:50px; font-size:13px; font-weight:600;">{{ ucfirst($service->status) }}</span>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <a href="{{ route('services.show', $service) }}"
                                                        style="display:inline-block; background:#10B981; color:#fff; padding:10px 24px; border-radius:10px; text-decoration:none; font-weight:600;">View Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="text-center">
                            {{ $services->appends(request()->query())->links() }}
                        </div>
                    @elseif($showServices)
                        <div class="box-two text-center" style="padding:70px 30px; border-radius:16px; margin-bottom:30px;">
                            <div style="font-size:70px; margin-bottom:20px;">🔍</div>
                            <h3 style="font-size:30px; font-weight:700; color:#111827; margin-bottom:15px;">No Services Found</h3>
                            <p style="font-size:16px; color:#6B7280;">Try adjusting filters or searching another keyword.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
