@extends('layouts.app')
@section('title', 'Browse Services')
@php use Illuminate\Support\Str; @endphp
@section('content')

    {{-- Header --}}
    <div style="background:linear-gradient(135deg,#1e3a5f 0%,#3B82F6 100%);padding:40px 0;">
        <div class="container">
            <h1 style="font-size:30px;font-weight:800;color:#fff;margin:0 0 6px;">Browse Services</h1>
            <p style="color:rgba(255,255,255,.8);margin:0;font-size:15px;">Find trusted service providers near you.</p>
        </div>
    </div>

    <div style="background:#F8FAFC;padding:30px 0 70px;">
        <div class="container">
            <div class="row">

                {{-- Sidebar --}}
                <div class="col-md-3" style="margin-bottom:24px;">
                    <div style="background:#fff;border-radius:14px;padding:24px;border:1px solid #E5E7EB;">
                        <h4 style="font-size:17px;font-weight:700;color:#111827;margin-bottom:20px;">
                            <i class="fa fa-sliders" style="color:#3B82F6;margin-right:6px;"></i> Filters
                        </h4>
                        <form action="{{ route('services.index') }}" method="GET">
                            <div style="margin-bottom:14px;">
                                <label
                                    style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Keywords</label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Name, city..."
                                    style="width:100%;height:42px;padding:0 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:14px;outline:none;">
                            </div>
                            <div style="margin-bottom:14px;">
                                <label
                                    style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">City</label>
                                <input type="text" name="city" value="{{ request('city') }}" placeholder="City"
                                    style="width:100%;height:42px;padding:0 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:14px;outline:none;">
                            </div>
                            <div style="margin-bottom:14px;">
                                <label
                                    style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Category</label>
                                <select name="category"
                                    style="width:100%;height:42px;padding:0 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:14px;background:#fff;outline:none;">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->slug }}"
                                            {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="margin-bottom:14px;">
                                <label
                                    style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Status</label>
                                <select name="status"
                                    style="width:100%;height:42px;padding:0 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:14px;background:#fff;outline:none;">
                                    <option value="">Any Status</option>
                                    <option value="open"
                                        {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="closed"
                                        {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                                    <option value="temporarily_closed"
                                        {{ request('status') === 'temporarily_closed' ? 'selected' : '' }}>Temporarily
                                        Closed</option>
                                </select>
                            </div>
                            <div style="margin-bottom:14px;">
                                <label
                                    style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Price
                                    Level</label>
                                <select name="price_level"
                                    style="width:100%;height:42px;padding:0 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:14px;background:#fff;outline:none;">
                                    <option value="">Any Price</option>
                                    <option value="low" {{ request('price_level') === 'low' ? 'selected' : '' }}>Low
                                    </option>
                                    <option value="medium" {{ request('price_level') === 'medium' ? 'selected' : '' }}>
                                        Medium</option>
                                    <option value="high" {{ request('price_level') === 'high' ? 'selected' : '' }}>High
                                    </option>
                                    <option value="luxury" {{ request('price_level') === 'luxury' ? 'selected' : '' }}>
                                        Luxury</option>
                                </select>
                            </div>
                            <div style="margin-bottom:20px;">
                                <label
                                    style="font-size:13px;font-weight:600;color:#374151;display:flex;align-items:center;gap:8px;cursor:pointer;">
                                    <input type="checkbox" name="verified" value="1"
                                        {{ request('verified') ? 'checked' : '' }}
                                        style="width:16px;height:16px;accent-color:#3B82F6;">
                                    Verified Only
                                </label>
                            </div>
                            <button type="submit"
                                style="width:100%;height:44px;background:#3B82F6;color:#fff;border:none;border-radius:8px;font-weight:700;font-size:14px;cursor:pointer;">
                                Apply Filters
                            </button>
                            @if (request()->anyFilled(['search', 'city', 'category', 'status', 'price_level', 'verified']))
                                <a href="{{ route('services.index') }}"
                                    style="display:block;text-align:center;margin-top:10px;color:#6B7280;font-size:13px;text-decoration:none;">Reset
                                    All</a>
                            @endif
                        </form>
                    </div>

                    {{-- Service Categories quick links --}}
                    @if ($categories->count())
                        <div
                            style="background:#fff;border-radius:14px;padding:20px;border:1px solid #E5E7EB;margin-top:16px;">
                            <h5 style="font-size:14px;font-weight:700;color:#111827;margin-bottom:14px;">Service Categories
                            </h5>
                            @foreach ($categories as $cat)
                                <a href="{{ route('service-categories.show', $cat->slug) }}"
                                    style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #F3F4F6;text-decoration:none;color:#374151;font-size:13px;font-weight:500;">
                                    <span>{{ $cat->name }}</span>
                                    <span
                                        style="background:#EFF6FF;color:#3B82F6;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:600;">{{ $cat->services_count ?? 0 }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Results --}}
                <div class="col-md-9">
                    <div
                        style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:10px;">
                        <h3 style="font-size:18px;font-weight:700;color:#111827;margin:0;">
                            {{ $services->total() }} Service{{ $services->total() !== 1 ? 's' : '' }}
                            @if (request('search'))
                                for "<span style="color:#3B82F6;">{{ request('search') }}</span>"
                            @endif
                        </h3>
                    </div>

                    <div class="row">
                        @forelse($services as $service)
                            <div class="col-sm-6 col-md-4" style="margin-bottom:20px;">
                                <div class="svc-card"
                                    style="background:#fff;border-radius:14px;overflow:hidden;border:1px solid #E5E7EB;height:100%;display:flex;flex-direction:column;transition:box-shadow .2s;">
                                    <div style="position:relative;overflow:hidden;height:180px;">
                                        <a href="{{ route('services.show', $service) }}">
                                            @if ($service->media->first())
                                                <img src="{{ asset('storage/' . $service->media->first()->file_path) }}"
                                                    alt="{{ $service->name }}"
                                                    style="width:100%;height:180px;object-fit:cover;transition:transform .3s;">
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
                                        @if ($service->status === 'open')
                                            <span
                                                style="position:absolute;top:10px;right:10px;background:rgba(16,185,129,.9);color:#fff;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">Open</span>
                                        @elseif($service->status === 'closed')
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
                                                <a href="{{ route('services.show', $service) }}"
                                                    style="color:inherit;text-decoration:none;">{{ $service->name }}</a>
                                            </h5>
                                            @if ($service->category)
                                                <span
                                                    style="background:#EFF6FF;color:#3B82F6;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:600;white-space:nowrap;margin-left:6px;">{{ $service->category->name }}</span>
                                            @endif
                                        </div>
                                        <p style="margin:0 0 8px;font-size:13px;color:#6B7280;">
                                            <i class="fa fa-map-marker" style="color:#EF4444;margin-right:4px;"></i>
                                            {{ $service->city }}@if ($service->district)
                                                , {{ $service->district }}
                                            @endif
                                        </p>
                                        @if ($service->tagline)
                                            <p style="margin:0 0 10px;font-size:13px;color:#6B7280;line-height:1.5;flex:1;">
                                                {{ Str::limit($service->tagline, 70) }}</p>
                                        @endif
                                        <div
                                            style="display:flex;justify-content:space-between;align-items:center;margin-top:auto;padding-top:10px;border-top:1px solid #F3F4F6;">
                                            <span
                                                style="font-size:12px;color:#9CA3AF;">{{ ucfirst($service->price_level) }}</span>
                                            <a href="{{ route('services.show', $service) }}"
                                                style="background:#3B82F6;color:#fff;padding:5px 14px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                                <div class="col-md-12 text-center" style="padding:80px 0;">
                                    <div style="font-size:56px;margin-bottom:16px;">🔧</div>
                                    <h3 style="font-size:22px;font-weight:700;color:#111827;margin-bottom:12px;">No services
                                        found</h3>
                                    <p style="color:#6B7280;margin-bottom:24px;">Try adjusting your filters.</p>
                                    <a href="{{ route('services.index') }}"
                                        style="background:#3B82F6;color:#fff;padding:12px 28px;border-radius:10px;font-weight:700;text-decoration:none;">Reset
                                        Filters</a>
                                </div>
                            @endforelse
                        </div>

                        @if ($services->hasPages())
                            <div style="margin-top:20px;text-align:center;">
                                {{ $services->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @push('styles')
            <style>
                .svc-card:hover {
                    box-shadow: 0 4px 20px rgba(0, 0, 0, .08);
                }

                .svc-card:hover img {
                    transform: scale(1.03);
                }
            </style>
        @endpush

    @endsection
