@extends('layouts.app')
@section('title', $category->name . ' Places')
@section('content')

    <div style="background:linear-gradient(135deg,#064e3b,#10B981);padding:40px 0;">
        <div class="container">
            <div style="display:flex;align-items:center;gap:14px;">
                <div
                    style="width:56px;height:56px;background:rgba(255,255,255,.2);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:26px;color:#fff;flex-shrink:0;">
                    <i class="fa {{ $category->icon_name ?? 'fa-folder' }}"></i>
                </div>
                <div>
                    <div style="font-size:13px;color:rgba(255,255,255,.7);margin-bottom:4px;">
                        <a href="{{ route('categories.index') }}"
                            style="color:rgba(255,255,255,.7);text-decoration:none;">Categories</a>
                        @if ($category->parent)
                            › <a href="{{ route('categories.show', $category->parent->slug) }}"
                                style="color:rgba(255,255,255,.7);text-decoration:none;">{{ $category->parent->name }}</a>
                        @endif
                    </div>
                    <h1 style="font-size:28px;font-weight:800;color:#fff;margin:0 0 4px;">{{ $category->name }}</h1>
                    <p style="color:rgba(255,255,255,.8);margin:0;font-size:14px;">{{ $category->places_count }}
                        place{{ $category->places_count !== 1 ? 's' : '' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div style="background:#F8FAFC;padding:30px 0 70px;">
        <div class="container">

            {{-- Subcategories toggle --}}
            @if ($subcategories->count())
                <div
                    style="background:#fff;border-radius:14px;border:1px solid #E5E7EB;margin-bottom:24px;overflow:hidden;">
                    <button onclick="document.getElementById('subcats').classList.toggle('hidden')"
                        style="width:100%;display:flex;justify-content:space-between;align-items:center;padding:16px 20px;background:none;border:none;cursor:pointer;font-size:15px;font-weight:700;color:#111827;">
                        <span><i class="fa fa-th-large" style="color:#10B981;margin-right:8px;"></i> Sub-categories
                            ({{ $subcategories->count() }})</span>
                        <i class="fa fa-chevron-down" style="color:#9CA3AF;font-size:12px;"></i>
                    </button>
                    <div id="subcats" style="padding:0 20px 16px;display:flex;flex-wrap:wrap;gap:10px;">
                        @foreach ($subcategories as $sub)
                            <a href="{{ route('categories.show', $sub->slug) }}"
                                style="display:flex;align-items:center;gap:8px;padding:8px 16px;background:#F0FDF4;border-radius:10px;text-decoration:none;color:#065F46;font-size:13px;font-weight:600;">
                                <i class="fa {{ $sub->icon_name ?? 'fa-folder' }}"></i>
                                {{ $sub->name }}
                                <span
                                    style="background:#D1FAE5;padding:1px 7px;border-radius:20px;font-size:11px;">{{ $sub->places_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Places grid --}}
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                <h3 style="font-size:18px;font-weight:700;color:#111827;margin:0;">{{ $places->total() }}
                    Place{{ $places->total() !== 1 ? 's' : '' }}</h3>
                <a href="{{ route('places.index', ['category' => $category->slug]) }}"
                    style="font-size:13px;color:#10B981;text-decoration:none;">View with filters →</a>
            </div>

            <div class="row">
                @forelse($places as $place)
                    @include('components.place-card', ['place' => $place])
                @empty
                    <div class="col-md-12 text-center" style="padding:60px 0;">
                        <p style="color:#6B7280;">No places in this category yet.</p>
                    </div>
                @endforelse
            </div>

            @if ($places->hasPages())
                <div style="text-align:center;margin-top:20px;">{{ $places->links() }}</div>
            @endif
        </div>
    </div>

@endsection
