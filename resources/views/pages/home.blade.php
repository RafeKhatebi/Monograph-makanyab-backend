@extends('layouts.app')

@section('title', 'Home')

@section('content')

    {{-- Hero --}}
    <div
        style="background:linear-gradient(135deg,#064e3b 0%,#10B981 100%); padding:80px 0 70px; position:relative; overflow:hidden;">
        <div
            style="position:absolute;inset:0;background:url('{{ asset('assets/img/slide1/slider-image-1.jpg') }}') center/cover no-repeat;opacity:.15;">
        </div>
        <div class="container" style="position:relative;">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                    <h1 style="font-size:42px;font-weight:800;color:#fff;margin-bottom:16px;line-height:1.2;">Discover the
                        Best Places Near You</h1>
                    <p style="font-size:18px;color:rgba(255,255,255,.85);margin-bottom:36px;">Find restaurants, cafes, shops,
                        hotels and more — all in one place.</p>
                    <form action="{{ route('search.index') }}" method="GET" style="max-width:600px;margin:0 auto;">
                        <div
                            style="display:flex;gap:8px;background:#fff;border-radius:14px;padding:8px;box-shadow:0 8px 30px rgba(0,0,0,.15);">
                            <div style="flex:1;position:relative;">
                                <i class="fa fa-search"
                                    style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#9CA3AF;"></i>
                                <input type="text" name="search" placeholder="Search places, services..."
                                    style="width:100%;height:46px;padding:0 14px 0 38px;border:none;outline:none;font-size:15px;border-radius:10px;">
                            </div>
                            <button type="submit"
                                style="height:46px;padding:0 24px;background:#10B981;color:#fff;border:none;border-radius:10px;font-weight:700;font-size:15px;cursor:pointer;white-space:nowrap;">
                                Search
                            </button>
                        </div>
                    </form>
                    <div style="margin-top:20px;display:flex;justify-content:center;gap:10px;flex-wrap:wrap;">
                        @foreach ($categories->take(4) as $cat)
                            <a href="{{ route('search.index', ['place_category' => $cat->slug, 'type' => 'places']) }}"
                                style="background:rgba(255,255,255,.2);color:#fff;padding:6px 16px;border-radius:20px;font-size:13px;font-weight:600;text-decoration:none;border:1px solid rgba(255,255,255,.3);">
                                @if ($cat->icon_name)
                                    <i class="fa {{ $cat->icon_name }}" style="margin-right:4px;"></i>
                                @endif{{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Featured Places --}}
    <div style="background:#F8FAFC; padding:60px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center" style="margin-bottom:36px;">
                    <h2 style="font-size:30px;font-weight:800;color:#111827;margin-bottom:8px;">Featured Places</h2>
                    <p style="color:#6B7280;font-size:16px;">Handpicked top-rated places loved by our community.</p>
                </div>
            </div>
            <div class="row">
                @forelse($featuredPlaces ?? [] as $place)
                    @include('components.place-card', ['place' => $place])
                @empty
                    <div class="col-md-12 text-center" style="padding:40px 0;">
                        <p style="color:#6B7280;">No featured places yet.</p>
                    </div>
                @endforelse
            </div>
            <div class="row" style="margin-top:30px;">
                <div class="col-md-12 text-center" style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;">
                    <a href="{{ route('places.index') }}"
                        style="display:inline-block;background:#10B981;color:#fff;padding:14px 36px;border-radius:12px;font-weight:700;font-size:15px;text-decoration:none;">
                        Browse All Places
                    </a>
                    <a href="{{ route('place-suggestions.create') }}"
                        style="display:inline-block;background:#fff;color:#10B981;padding:14px 36px;border-radius:12px;font-weight:700;font-size:15px;text-decoration:none;border:2px solid #10B981;">
                        Suggest a Place
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Categories --}}
    <div style="background:#fff; padding:60px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center" style="margin-bottom:36px;">
                    <h2 style="font-size:30px;font-weight:800;color:#111827;margin-bottom:8px;">Browse by Category</h2>
                    <p style="color:#6B7280;font-size:16px;">Explore popular categories and find what you need.</p>
                </div>
            </div>
            <div class="row">
                @forelse($categories ?? [] as $category)
                    <div class="col-sm-6 col-md-4" style="margin-bottom:20px;">
                        <a href="{{ route('search.index', ['place_category' => $category->slug, 'type' => 'places']) }}"
                            style="text-decoration:none;">
                            <div style="background:#F8FAFC;border:1px solid #E5E7EB;border-radius:14px;padding:28px 20px;text-align:center;transition:box-shadow .2s;"
                                class="cat-card">
                                <div
                                    style="width:60px;height:60px;background:#ECFDF5;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:26px;color:{{ $category->color_code ?? '#10B981' }};">
                                    <i class="fa {{ $category->icon_name ?? 'fa-folder' }}"></i>
                                </div>
                                <h5 style="font-size:16px;font-weight:700;color:#111827;margin-bottom:6px;">
                                    {{ $category->name }}</h5>
                                <p style="font-size:13px;color:#6B7280;margin:0;">{{ $category->places_count }} places</p>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-sm-12 text-center">
                        <p style="color:#6B7280;">No categories available yet.</p>
                    </div>
                @endforelse
            </div>
            <div class="row" style="margin-top:20px;">
                <div class="col-md-12 text-center">
                    <a href="{{ route('categories.index') }}"
                        style="display:inline-block;border:2px solid #10B981;color:#10B981;padding:12px 32px;border-radius:12px;font-weight:700;font-size:15px;text-decoration:none;">
                        View All Categories
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div style="background:linear-gradient(135deg,#064e3b,#10B981);padding:60px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-6" style="margin-bottom:20px;">
                    <div
                        style="background:rgba(255,255,255,.1);border-radius:16px;padding:32px;display:flex;align-items:center;gap:20px;">
                        <div style="flex:1;">
                            <h3 style="font-size:20px;font-weight:700;color:#fff;margin-bottom:6px;">Looking for a Place?
                            </h3>
                            <p style="color:rgba(255,255,255,.8);margin-bottom:14px;font-size:14px;">Search thousands of
                                places by category, location, and rating.</p>
                            <a href="{{ route('search.index') }}"
                                style="background:#fff;color:#10B981;padding:10px 22px;border-radius:10px;font-weight:700;font-size:14px;text-decoration:none;">Search
                                Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" style="margin-bottom:20px;">
                    <div
                        style="background:rgba(255,255,255,.1);border-radius:16px;padding:32px;display:flex;align-items:center;gap:20px;">
                        <div style="flex:1;">
                            <h3 style="font-size:20px;font-weight:700;color:#fff;margin-bottom:6px;">Own a Business?</h3>
                            <p style="color:rgba(255,255,255,.8);margin-bottom:14px;font-size:14px;">List your place and
                                reach thousands of potential customers.</p>
                            <a href="{{ route('register') }}"
                                style="background:#fff;color:#10B981;padding:10px 22px;border-radius:10px;font-weight:700;font-size:14px;text-decoration:none;">Get
                                Listed</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .cat-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, .08);
            border-color: #10B981 !important;
        }
    </style>
@endpush
