@extends('layouts.app')
@section('title', 'Search')
@php use Illuminate\Support\Str; @endphp
@section('content')

    {{-- Top Search Bar --}}
    <div style="background:#fff; border-bottom:1px solid #E5E7EB; padding:20px 0;">
        <div class="container">
            <form action="{{ route('search.index') }}" method="GET" id="search-form">
                <div class="row" style="align-items:center;">
                    <div class="col-md-5 col-sm-12" style="padding-right:6px; margin-bottom:10px;">
                        <div style="position:relative;">
                            <i class="fa fa-search"
                                style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#9CA3AF;"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search businesses, places or services..."
                                style="width:100%;height:48px;padding:0 14px 0 40px;border:1px solid #D1D5DB;border-radius:10px;font-size:15px;outline:none;">
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6" style="padding:0 6px;margin-bottom:10px;">
                        <div style="position:relative;">
                            <i class="fa fa-map-marker"
                                style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#9CA3AF;"></i>
                            <input type="text" name="city" value="{{ request('city') }}" placeholder="City"
                                style="width:100%;height:48px;padding:0 14px 0 36px;border:1px solid #D1D5DB;border-radius:10px;font-size:15px;outline:none;">
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6" style="padding:0 6px;margin-bottom:10px;">
                        <select name="type"
                            style="width:100%;height:48px;padding:0 14px;border:1px solid #D1D5DB;border-radius:10px;font-size:15px;background:#fff;outline:none;">
                            <option value="all" {{ request('type', 'all') == 'all' ? 'selected' : '' }}>All Types
                            </option>
                            <option value="places" {{ request('type') == 'places' ? 'selected' : '' }}>Places</option>
                            <option value="services" {{ request('type') == 'services' ? 'selected' : '' }}>Services
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-12" style="padding-left:6px;margin-bottom:10px;display:flex;gap:8px;">
                        <button type="submit"
                            style="flex:1;height:48px;background:#10B981;color:#fff;border:none;border-radius:10px;font-weight:700;font-size:15px;cursor:pointer;">
                            <i class="fa fa-search"></i> Search
                        </button>
                        @if (request()->hasAny([
                                'search',
                                'city',
                                'type',
                                'place_category',
                                'service_category',
                                'status',
                                'price_level',
                                'rating',
                                'open_now',
                                'verified',
                                'province',
                                'district',
                            ]))
                            <a href="{{ route('search.index') }}"
                                style="height:48px;padding:0 16px;display:flex;align-items:center;border:1px solid #D1D5DB;border-radius:10px;color:#6B7280;text-decoration:none;">
                                <i class="fa fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
                {{-- Category Chips --}}
                <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:10px;">
                    <a href="{{ route('search.index', array_merge(request()->except('place_category'), ['type' => 'places'])) }}"
                        style="padding:6px 16px;border-radius:20px;font-size:13px;font-weight:600;text-decoration:none;
                    background:{{ !request('place_category') && request('type', 'all') != 'services' ? '#10B981' : '#F3F4F6' }};
                    color:{{ !request('place_category') && request('type', 'all') != 'services' ? '#fff' : '#374151' }};">
                        All Places
                    </a>
                    @foreach ($placeCategories as $cat)
                        <a href="{{ route('search.index', array_merge(request()->except('place_category', 'service_category'), ['place_category' => $cat->slug, 'type' => 'places'])) }}"
                            style="padding:6px 16px;border-radius:20px;font-size:13px;font-weight:600;text-decoration:none;
                        background:{{ request('place_category') == $cat->slug ? '#10B981' : '#F3F4F6' }};
                        color:{{ request('place_category') == $cat->slug ? '#fff' : '#374151' }};">
                            @if ($cat->icon_name)
                                <i class="fa {{ $cat->icon_name }}" style="margin-right:4px;"></i>
                            @endif
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </form>
        </div>
    </div>

    {{-- Main Content --}}
    <div style="background:#F8FAFC;padding:30px 0 70px;">
        <div class="container">
            <div class="row">
                {{-- Sidebar Filters --}}
                <div class="col-md-3" style="margin-bottom:24px;">
                    <div style="background:#fff;border-radius:14px;padding:24px;border:1px solid #E5E7EB;">
                        <h4 style="font-size:17px;font-weight:700;color:#111827;margin-bottom:20px;">
                            <i class="fa fa-sliders" style="color:#10B981;margin-right:6px;"></i> Filters
                        </h4>
                        <form action="{{ route('search.index') }}" method="GET">
                            @if (request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if (request('type'))
                                <input type="hidden" name="type" value="{{ request('type') }}">
                            @endif
                            @if (request('city'))
                                <input type="hidden" name="city" value="{{ request('city') }}">
                            @endif

                            <div style="margin-bottom:16px;">
                                <label
                                    style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Province</label>
                                <input type="text" name="province" value="{{ request('province') }}"
                                    placeholder="e.g. Herat"
                                    style="width:100%;height:42px;padding:0 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:14px;outline:none;">
                            </div>
                            <div style="margin-bottom:16px;">
                                <label
                                    style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">District</label>
                                <input type="text" name="district" value="{{ request('district') }}"
                                    placeholder="District"
                                    style="width:100%;height:42px;padding:0 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:14px;outline:none;">
                            </div>
                            <div style="margin-bottom:16px;">
                                <label
                                    style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Place
                                    Category</label>
                                <select name="place_category"
                                    style="width:100%;height:42px;padding:0 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:14px;background:#fff;outline:none;">
                                    <option value="">All Categories</option>
                                    @foreach ($placeCategories as $cat)
                                        <option value="{{ $cat->slug }}"
                                            {{ request('place_category') == $cat->slug ? 'selected' : '' }}>
                                            {{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="margin-bottom:16px;">
                                <label
                                    style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Service
                                    Category</label>
                                <select name="service_category"
                                    style="width:100%;height:42px;padding:0 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:14px;background:#fff;outline:none;">
                                    <option value="">All Categories</option>
                                    @foreach ($serviceCategories as $cat)
                                        <option value="{{ $cat->slug }}"
                                            {{ request('service_category') == $cat->slug ? 'selected' : '' }}>
                                            {{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="margin-bottom:16px;">
                                <label
                                    style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Status</label>
                                <select name="status"
                                    style="width:100%;height:42px;padding:0 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:14px;background:#fff;outline:none;">
                                    <option value="">Any Status</option>
                                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>
                                        Open</option>
                                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>
                                        Closed</option>
                                    <option value="temporarily_closed"
                                        {{ request('status') == 'temporarily_closed' ? 'selected' : '' }}>Temporarily Closed
                                    </option>
                                </select>
                            </div>
                            <div style="margin-bottom:16px;">
                                <label
                                    style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Price
                                    Level</label>
                                <select name="price_level"
                                    style="width:100%;height:42px;padding:0 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:14px;background:#fff;outline:none;">
                                    <option value="">Any Price</option>
                                    <option value="low" {{ request('price_level') == 'low' ? 'selected' : '' }}>Low
                                    </option>
                                    <option value="medium" {{ request('price_level') == 'medium' ? 'selected' : '' }}>Medium
                                    </option>
                                    <option value="high" {{ request('price_level') == 'high' ? 'selected' : '' }}>High
                                    </option>
                                    <option value="luxury" {{ request('price_level') == 'luxury' ? 'selected' : '' }}>Luxury
                                    </option>
                                </select>
                            </div>
                            <div style="margin-bottom:16px;">
                                <label
                                    style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Rating</label>
                                <select name="rating"
                                    style="width:100%;height:42px;padding:0 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:14px;background:#fff;outline:none;">
                                    <option value="">Any Rating</option>
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} star{{ $i > 1 ? 's' : '' }}+</option>
                                    @endfor
                                </select>
                            </div>
                            <div style="margin-bottom:16px;">
                                <label
                                    style="font-size:13px;font-weight:600;color:#374151;display:flex;align-items:center;gap:8px;cursor:pointer;">
                                    <input type="checkbox" name="open_now" value="1"
                                        {{ request('open_now') ? 'checked' : '' }}
                                        style="width:16px;height:16px;accent-color:#10B981;">
                                    Open Now
                                </label>
                            </div>
                            <div style="margin-bottom:20px;">
                                <label
                                    style="font-size:13px;font-weight:600;color:#374151;display:flex;align-items:center;gap:8px;cursor:pointer;">
                                    <input type="checkbox" name="verified" value="1"
                                        {{ request('verified') ? 'checked' : '' }}
                                        style="width:16px;height:16px;accent-color:#10B981;">
                                    Verified Only
                                </label>
                            </div>
                            <button type="submit"
                                style="width:100%;height:44px;background:#10B981;color:#fff;border:none;border-radius:8px;font-weight:700;font-size:14px;cursor:pointer;">
                                Apply Filters
                            </button>
                            <a href="{{ route('search.index') }}"
                                style="display:block;text-align:center;margin-top:10px;color:#6B7280;font-size:13px;text-decoration:none;">
                                Reset All
                            </a>
                        </form>
                    </div>
                </div>

                {{-- Results Area --}}
                <div class="col-md-9">
                    @php
                        $placeCount = $places ? $places->total() : 0;
                        $serviceCount = $services ? $services->total() : 0;
                        $totalResults = $placeCount + $serviceCount;
                    @endphp

                    <div
                        style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:10px;">
                        <div>
                            <h3 style="font-size:20px;font-weight:700;color:#111827;margin:0;">
                                {{ $totalResults }} Result{{ $totalResults !== 1 ? 's' : '' }}
                                @if (request('search'))
                                    for "<span style="color:#10B981;">{{ request('search') }}</span>"
                                @endif
                                @if (request('city'))
                                    in <span style="color:#10B981;">{{ request('city') }}</span>
                                @endif
                            </h3>
                        </div>
                    </div>

                    @if ($totalResults > 0)
                        {{-- Places --}}
                        @if ($showPlaces && $places && $places->count())
                            <h4
                                style="font-size:16px;font-weight:700;color:#374151;margin-bottom:14px;padding-bottom:8px;border-bottom:2px solid #E5E7EB;">
                                <i class="fa fa-map-marker" style="color:#10B981;"></i> Places
                                <span style="font-weight:400;color:#9CA3AF;font-size:14px;">({{ $placeCount }})</span>
                            </h4>
                            <div class="row" style="margin-bottom:30px;">
                                @foreach ($places as $place)
                                    <div class="col-sm-6 col-md-4" style="margin-bottom:20px;">
                                        <div class="search-card"
                                            style="background:#fff;border-radius:14px;overflow:hidden;border:1px solid #E5E7EB;height:100%;display:flex;flex-direction:column;">
                                            <div style="position:relative;overflow:hidden;height:180px;">
                                                <a href="{{ route('places.show', $place) }}">
                                                    @if ($place->media->first())
                                                        <img src="{{ asset('storage/' . $place->media->first()->file_path) }}"
                                                            alt="{{ $place->name }}"
                                                            style="width:100%;height:180px;object-fit:cover;transition:transform .3s;">
                                                    @else
                                                        <img src="{{ asset('assets/img/demo/property-1.jpg') }}"
                                                            alt="{{ $place->name }}"
                                                            style="width:100%;height:180px;object-fit:cover;">
                                                    @endif
                                                </a>
                                                @if ($place->is_verified)
                                                    <span
                                                        style="position:absolute;top:10px;left:10px;background:rgba(16,185,129,.9);color:#fff;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">
                                                        <i class="fa fa-check-circle"></i> Verified
                                                    </span>
                                                @endif
                                                @if ($place->status === 'open')
                                                    <span
                                                        style="position:absolute;top:10px;right:10px;background:rgba(16,185,129,.9);color:#fff;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">Open</span>
                                                @elseif($place->status === 'closed')
                                                    <span
                                                        style="position:absolute;top:10px;right:10px;background:rgba(239,68,68,.85);color:#fff;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">Closed</span>
                                                @else
                                                    <span
                                                        style="position:absolute;top:10px;right:10px;background:rgba(245,158,11,.85);color:#fff;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">Temp.
                                                        Closed</span>
                                                @endif
                                            </div>
                                            <div style="padding:14px 16px;flex:1;display:flex;flex-direction:column;">
                                                <div
                                                    style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;">
                                                    <h5
                                                        style="margin:0;font-size:15px;font-weight:700;color:#111827;line-height:1.3;">
                                                        <a href="{{ route('places.show', $place) }}"
                                                            style="color:inherit;text-decoration:none;">{{ $place->name }}</a>
                                                    </h5>
                                                    @if ($place->category)
                                                        <span
                                                            style="background:#F0FDF4;color:#10B981;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:600;white-space:nowrap;margin-left:6px;">{{ $place->category->name }}</span>
                                                    @endif
                                                </div>
                                                <p style="margin:0 0 8px;font-size:13px;color:#6B7280;">
                                                    <i class="fa fa-map-marker"
                                                        style="color:#EF4444;margin-right:4px;"></i>
                                                    {{ $place->city }}@if ($place->district)
                                                        , {{ $place->district }}
                                                    @endif
                                                </p>
                                                @if ($place->tagline)
                                                    <p
                                                        style="margin:0 0 10px;font-size:13px;color:#6B7280;line-height:1.5;flex:1;">
                                                        {{ Str::limit($place->tagline, 70) }}</p>
                                                @endif
                                                <div
                                                    style="display:flex;justify-content:space-between;align-items:center;margin-top:auto;padding-top:10px;border-top:1px solid #F3F4F6;">
                                                    <span
                                                        style="font-size:12px;color:#9CA3AF;">{{ ucfirst($place->price_level) }}</span>
                                                    <a href="{{ route('places.show', $place) }}"
                                                        style="background:#10B981;color:#fff;padding:5px 14px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">View</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($places->hasPages())
                                <div style="margin-bottom:30px;">{{ $places->appends(request()->query())->links() }}</div>
                            @endif
                        @elseif($showPlaces && $placeCount === 0 && request('type') !== 'services')
                            <div
                                style="background:#fff;border-radius:14px;padding:30px;text-align:center;margin-bottom:24px;border:1px solid #E5E7EB;">
                                <p style="color:#6B7280;margin:0;">No places found for these filters.</p>
                            </div>
                        @endif

                        {{-- Services --}}
                        @if ($showServices && $services && $services->count())
                            <h4
                                style="font-size:16px;font-weight:700;color:#374151;margin-bottom:14px;padding-bottom:8px;border-bottom:2px solid #E5E7EB;">
                                <i class="fa fa-briefcase" style="color:#10B981;"></i> Services
                                <span style="font-weight:400;color:#9CA3AF;font-size:14px;">({{ $serviceCount }})</span>
                            </h4>
                            <div class="row">
                                @foreach ($services as $service)
                                    <div class="col-sm-6 col-md-4" style="margin-bottom:20px;">
                                        <div class="search-card"
                                            style="background:#fff;border-radius:14px;overflow:hidden;border:1px solid #E5E7EB;height:100%;display:flex;flex-direction:column;">
                                            <div style="position:relative;overflow:hidden;height:180px;">
                                                <a href="{{ route('services.show', $service) }}">
                                                    @if ($service->media->first())
                                                        <img src="{{ asset('storage/' . $service->media->first()->file_path) }}"
                                                            alt="{{ $service->name }}"
                                                            style="width:100%;height:180px;object-fit:cover;">
                                                    @else
                                                        <img src="{{ asset('assets/img/demo/property-1.jpg') }}"
                                                            alt="{{ $service->name }}"
                                                            style="width:100%;height:180px;object-fit:cover;">
                                                    @endif
                                                </a>
                                                @if ($service->is_verified)
                                                    <span
                                                        style="position:absolute;top:10px;left:10px;background:rgba(16,185,129,.9);color:#fff;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">
                                                        <i class="fa fa-check-circle"></i> Verified
                                                    </span>
                                                @endif
                                            </div>
                                            <div style="padding:14px 16px;flex:1;display:flex;flex-direction:column;">
                                                <div
                                                    style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;">
                                                    <h5
                                                        style="margin:0;font-size:15px;font-weight:700;color:#111827;line-height:1.3;">
                                                        <a href="{{ route('services.show', $service) }}"
                                                            style="color:inherit;text-decoration:none;">{{ $service->name }}</a>
                                                    </h5>
                                                    @if ($service->category)
                                                        <span
                                                            style="background:#EFF6FF;color:#3B82F6;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:600;white-space:nowrap;margin-left:6px;">{{ $service->category->name }}</span>
                                                    @endif
                                                </div>
                                                <p style="margin:0 0 8px;font-size:13px;color:#6B7280;">
                                                    <i class="fa fa-map-marker"
                                                        style="color:#EF4444;margin-right:4px;"></i>{{ $service->city }}
                                                </p>
                                                @if ($service->description)
                                                    <p
                                                        style="margin:0 0 10px;font-size:13px;color:#6B7280;line-height:1.5;flex:1;">
                                                        {{ Str::limit($service->description, 70) }}</p>
                                                @endif
                                                <div
                                                    style="display:flex;justify-content:space-between;align-items:center;margin-top:auto;padding-top:10px;border-top:1px solid #F3F4F6;">
                                                    <span
                                                        style="font-size:12px;color:#9CA3AF;">{{ ucfirst($service->price_level ?? '') }}</span>
                                                    <a href="{{ route('services.show', $service) }}"
                                                        style="background:#3B82F6;color:#fff;padding:5px 14px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">View</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($services->hasPages())
                                <div style="margin-top:10px;">{{ $services->appends(request()->query())->links() }}</div>
                            @endif
                        @elseif($showServices && $serviceCount === 0 && request('type') !== 'places')
                            <div
                                style="background:#fff;border-radius:14px;padding:30px;text-align:center;border:1px solid #E5E7EB;">
                                <p style="color:#6B7280;margin:0;">No services found for these filters.</p>
                            </div>
                        @endif
                    @else
                        <div
                            style="background:#fff;border-radius:14px;padding:70px 30px;text-align:center;border:1px solid #E5E7EB;">
                            <div style="font-size:56px;margin-bottom:16px;">🔍</div>
                            <h3 style="font-size:22px;font-weight:700;color:#111827;margin-bottom:12px;">No results found
                            </h3>
                            <p
                                style="color:#6B7280;margin-bottom:24px;max-width:400px;margin-left:auto;margin-right:auto;">
                                Try different keywords, a different city, or remove some filters.
                            </p>
                            <a href="{{ route('search.index') }}"
                                style="background:#10B981;color:#fff;padding:12px 28px;border-radius:10px;font-weight:700;text-decoration:none;font-size:15px;">
                                Reset Search
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .search-card {
                transition: box-shadow .2s;
            }

            .search-card:hover {
                box-shadow: 0 4px 20px rgba(0, 0, 0, .08);
            }

            .search-card:hover img {
                transform: scale(1.03);
            }
        </style>
    @endpush

@endsection
