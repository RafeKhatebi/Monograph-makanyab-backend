@extends('layouts.app')
@section('title', $category->name . ' Services')
@php use Illuminate\Support\Str; @endphp
@section('content')

<div style="background:linear-gradient(135deg,#1e3a5f,#3B82F6);padding:40px 0;">
    <div class="container">
        <div style="display:flex;align-items:center;gap:14px;">
            <div style="width:56px;height:56px;background:rgba(255,255,255,.2);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:26px;color:#fff;flex-shrink:0;">
                <i class="fa {{ $category->icon_name ?? 'fa-briefcase' }}"></i>
            </div>
            <div>
                <div style="font-size:13px;color:rgba(255,255,255,.7);margin-bottom:4px;">
                    <a href="{{ route('service-categories.index') }}" style="color:rgba(255,255,255,.7);text-decoration:none;">Service Categories</a>
                    @if($category->parent) › <a href="{{ route('service-categories.show', $category->parent->slug) }}" style="color:rgba(255,255,255,.7);text-decoration:none;">{{ $category->parent->name }}</a>@endif
                </div>
                <h1 style="font-size:28px;font-weight:800;color:#fff;margin:0 0 4px;">{{ $category->name }}</h1>
                <p style="color:rgba(255,255,255,.8);margin:0;font-size:14px;">{{ $category->services_count }} service{{ $category->services_count !== 1 ? 's' : '' }}</p>
            </div>
        </div>
    </div>
</div>

<div style="background:#F8FAFC;padding:30px 0 70px;">
    <div class="container">

        {{-- Subcategories toggle --}}
        @if($subcategories->count())
            <div style="background:#fff;border-radius:14px;border:1px solid #E5E7EB;margin-bottom:24px;overflow:hidden;">
                <button onclick="document.getElementById('subcats').classList.toggle('hidden')"
                    style="width:100%;display:flex;justify-content:space-between;align-items:center;padding:16px 20px;background:none;border:none;cursor:pointer;font-size:15px;font-weight:700;color:#111827;">
                    <span><i class="fa fa-th-large" style="color:#3B82F6;margin-right:8px;"></i> Sub-categories ({{ $subcategories->count() }})</span>
                    <i class="fa fa-chevron-down" style="color:#9CA3AF;font-size:12px;"></i>
                </button>
                <div id="subcats" style="padding:0 20px 16px;display:flex;flex-wrap:wrap;gap:10px;">
                    @foreach($subcategories as $sub)
                        <a href="{{ route('service-categories.show', $sub->slug) }}"
                            style="display:flex;align-items:center;gap:8px;padding:8px 16px;background:#EFF6FF;border-radius:10px;text-decoration:none;color:#1D4ED8;font-size:13px;font-weight:600;">
                            <i class="fa {{ $sub->icon_name ?? 'fa-briefcase' }}"></i>
                            {{ $sub->name }}
                            <span style="background:#DBEAFE;padding:1px 7px;border-radius:20px;font-size:11px;">{{ $sub->services_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Services grid --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 style="font-size:18px;font-weight:700;color:#111827;margin:0;">{{ $services->total() }} Service{{ $services->total() !== 1 ? 's' : '' }}</h3>
            <a href="{{ route('services.index', ['category' => $category->slug]) }}" style="font-size:13px;color:#3B82F6;text-decoration:none;">View with filters →</a>
        </div>

        <div class="row">
            @forelse($services as $service)
                <div class="col-sm-6 col-md-4" style="margin-bottom:20px;">
                    <div class="svc-card" style="background:#fff;border-radius:14px;overflow:hidden;border:1px solid #E5E7EB;height:100%;display:flex;flex-direction:column;transition:box-shadow .2s;">
                        <div style="position:relative;overflow:hidden;height:180px;">
                            <a href="{{ route('services.show', $service) }}">
                                @if($service->media->first())
                                    <img src="{{ asset('storage/'.$service->media->first()->file_path) }}" alt="{{ $service->name }}" style="width:100%;height:180px;object-fit:cover;transition:transform .3s;">
                                @else
                                    <img src="{{ asset('assets/img/demo/property-1.jpg') }}" alt="{{ $service->name }}" style="width:100%;height:180px;object-fit:cover;">
                                @endif
                            </a>
                            @if($service->is_verified)
                                <span style="position:absolute;top:10px;left:10px;background:rgba(16,185,129,.9);color:#fff;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;"><i class="fa fa-check-circle"></i> Verified</span>
                            @endif
                        </div>
                        <div style="padding:14px 16px;flex:1;display:flex;flex-direction:column;">
                            <h5 style="margin:0 0 6px;font-size:15px;font-weight:700;color:#111827;">
                                <a href="{{ route('services.show', $service) }}" style="color:inherit;text-decoration:none;">{{ $service->name }}</a>
                            </h5>
                            <p style="margin:0 0 8px;font-size:13px;color:#6B7280;"><i class="fa fa-map-marker" style="color:#EF4444;margin-right:4px;"></i>{{ $service->city }}</p>
                            @if($service->tagline)
                                <p style="margin:0 0 10px;font-size:13px;color:#6B7280;line-height:1.5;flex:1;">{{ Str::limit($service->tagline, 70) }}</p>
                            @endif
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:auto;padding-top:10px;border-top:1px solid #F3F4F6;">
                                <span style="font-size:12px;color:#9CA3AF;">{{ ucfirst($service->price_level) }}</span>
                                <a href="{{ route('services.show', $service) }}" style="background:#3B82F6;color:#fff;padding:5px 14px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-md-12 text-center" style="padding:60px 0;">
                    <p style="color:#6B7280;">No services in this category yet.</p>
                </div>
            @endforelse
        </div>

        @if($services->hasPages())
            <div style="text-align:center;margin-top:20px;">{{ $services->links() }}</div>
        @endif
    </div>
</div>

@push('styles')
<style>.svc-card:hover { box-shadow:0 4px 20px rgba(0,0,0,.08); } .svc-card:hover img { transform:scale(1.03); }</style>
@endpush

@endsection
