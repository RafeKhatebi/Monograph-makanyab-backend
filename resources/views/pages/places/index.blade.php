@extends('layouts.app')
@section('title', 'Browse Places')
@section('content')

{{-- Page Header --}}
<div style="background:linear-gradient(135deg,#064e3b,#10B981);padding:40px 0;">
    <div class="container">
        <h1 style="font-size:30px;font-weight:800;color:#fff;margin:0 0 6px;">Browse Places</h1>
        <p style="color:rgba(255,255,255,.8);margin:0;font-size:15px;">Discover verified businesses and places near you.</p>
    </div>
</div>

<div style="background:#F8FAFC;padding:30px 0 70px;">
    <div class="container">
        <div class="row">

            {{-- Sidebar Filters --}}
            <div class="col-md-3" style="margin-bottom:24px;">
                <div style="background:#fff;border-radius:14px;padding:24px;border:1px solid #E5E7EB;">
                    <h4 style="font-size:17px;font-weight:700;color:#111827;margin-bottom:20px;">
                        <i class="fa fa-sliders" style="color:#10B981;margin-right:6px;"></i> Filters
                    </h4>
                    <form action="{{ route('places.index') }}" method="GET">
                        <div style="margin-bottom:14px;">
                            <label style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Keywords</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, city..."
                                style="width:100%;height:42px;padding:0 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:14px;outline:none;">
                        </div>
                        <div style="margin-bottom:14px;">
                            <label style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">City</label>
                            <input type="text" name="city" value="{{ request('city') }}" placeholder="City"
                                style="width:100%;height:42px;padding:0 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:14px;outline:none;">
                        </div>
                        <div style="margin-bottom:14px;">
                            <label style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Category</label>
                            <select name="category" style="width:100%;height:42px;padding:0 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:14px;background:#fff;outline:none;">
                                <option value="">All Categories</option>
                                @foreach($categories ?? [] as $cat)
                                    <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="margin-bottom:14px;">
                            <label style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Status</label>
                            <select name="status" style="width:100%;height:42px;padding:0 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:14px;background:#fff;outline:none;">
                                <option value="">Any Status</option>
                                <option value="open"               {{ request('status') === 'open'               ? 'selected' : '' }}>Open</option>
                                <option value="closed"             {{ request('status') === 'closed'             ? 'selected' : '' }}>Closed</option>
                                <option value="temporarily_closed" {{ request('status') === 'temporarily_closed' ? 'selected' : '' }}>Temporarily Closed</option>
                            </select>
                        </div>
                        <div style="margin-bottom:14px;">
                            <label style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Price Level</label>
                            <select name="price_level" style="width:100%;height:42px;padding:0 12px;border:1px solid #D1D5DB;border-radius:8px;font-size:14px;background:#fff;outline:none;">
                                <option value="">Any Price</option>
                                <option value="low"    {{ request('price_level') === 'low'    ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('price_level') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high"   {{ request('price_level') === 'high'   ? 'selected' : '' }}>High</option>
                                <option value="luxury" {{ request('price_level') === 'luxury' ? 'selected' : '' }}>Luxury</option>
                            </select>
                        </div>
                        <div style="margin-bottom:20px;">
                            <label style="font-size:13px;font-weight:600;color:#374151;display:flex;align-items:center;gap:8px;cursor:pointer;">
                                <input type="checkbox" name="verified" value="1" {{ request('verified') ? 'checked' : '' }} style="width:16px;height:16px;accent-color:#10B981;">
                                Verified Only
                            </label>
                        </div>
                        <button type="submit" style="width:100%;height:44px;background:#10B981;color:#fff;border:none;border-radius:8px;font-weight:700;font-size:14px;cursor:pointer;">
                            Apply Filters
                        </button>
                        @if(request()->anyFilled(['search','city','category','status','price_level','verified']))
                            <a href="{{ route('places.index') }}" style="display:block;text-align:center;margin-top:10px;color:#6B7280;font-size:13px;text-decoration:none;">Reset All</a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Results --}}
            <div class="col-md-9">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:10px;">
                    <h3 style="font-size:18px;font-weight:700;color:#111827;margin:0;">
                        {{ $places->total() }} Place{{ $places->total() !== 1 ? 's' : '' }}
                        @if(request('search')) for "<span style="color:#10B981;">{{ request('search') }}</span>"@endif
                    </h3>
                </div>

                <div class="row">
                    @forelse($places as $place)
                        @include('components.place-card', ['place' => $place])
                    @empty
                        <div class="col-md-12 text-center" style="padding:80px 0;">
                            <div style="font-size:56px;margin-bottom:16px;">📍</div>
                            <h3 style="font-size:22px;font-weight:700;color:#111827;margin-bottom:12px;">No places found</h3>
                            <p style="color:#6B7280;margin-bottom:24px;">Try adjusting your filters.</p>
                            <a href="{{ route('places.index') }}" style="background:#10B981;color:#fff;padding:12px 28px;border-radius:10px;font-weight:700;text-decoration:none;">Reset Filters</a>
                        </div>
                    @endforelse
                </div>

                @if($places->hasPages())
                    <div style="margin-top:20px;text-align:center;">
                        {{ $places->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
