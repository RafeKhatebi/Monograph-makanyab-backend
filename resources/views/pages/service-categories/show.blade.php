@extends('layouts.app')
@section('title', $category->name . ' Services')
@php use Illuminate\Support\Str; @endphp
@section('content')

    <div style="background:linear-gradient(135deg,#1e3a5f,#3B82F6);padding:40px 0;">
        <div class="container">
            <div style="display:flex;align-items:center;gap:14px;">
                <div
                    style="width:56px;height:56px;background:rgba(255,255,255,.2);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:26px;color:#fff;flex-shrink:0;">
                    <i class="fa {{ $category->icon_name ?? 'fa-briefcase' }}"></i>
                </div>
                <div>
                    <div style="font-size:13px;color:rgba(255,255,255,.7);margin-bottom:4px;">
                        <a href="{{ route('service-categories.index') }}"
                            style="color:rgba(255,255,255,.7);text-decoration:none;">Service Categories</a>
                        @if ($category->parent)
                            › <a href="{{ route('service-categories.show', $category->parent->slug) }}"
                                style="color:rgba(255,255,255,.7);text-decoration:none;">{{ $category->parent->name }}</a>
                        @endif
                    </div>
                    <h1 style="font-size:28px;font-weight:800;color:#fff;margin:0 0 4px;">{{ $category->name }}</h1>
                    <p style="color:rgba(255,255,255,.8);margin:0;font-size:14px;">{{ $category->services_count }}
                        service{{ $category->services_count !== 1 ? 's' : '' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div style="background:#F8FAFC;padding:30px 0 70px;">
        <div class="container">

            {{-- Subcategories toggle --}}
            @if ($subcategories->count())
                <details open
                    style="background:#fff;border-radius:14px;border:1px solid #E5E7EB;margin-bottom:24px;overflow:hidden;">
                    <summary
                        style="display:flex;justify-content:space-between;align-items:center;padding:16px 20px;cursor:pointer;font-size:15px;font-weight:700;color:#111827;">
                        <span><i class="fa fa-th-large" style="color:#3B82F6;margin-right:8px;"></i> Subcategories
                            ({{ $subcategories->count() }})</span>
                        <i class="fa fa-chevron-down" style="color:#9CA3AF;font-size:12px;"></i>
                    </summary>
                    <div style="padding:0 20px 16px;display:flex;flex-wrap:wrap;gap:10px;">
                        @foreach ($subcategories as $sub)
                            <a href="{{ route('service-categories.show', $sub->slug) }}"
                                style="display:flex;align-items:center;gap:8px;padding:8px 16px;background:#EFF6FF;border-radius:10px;text-decoration:none;color:#1D4ED8;font-size:13px;font-weight:600;">
                                <i class="fa {{ $sub->icon_name ?? 'fa-briefcase' }}"></i>
                                {{ $sub->name }}
                                <span
                                    style="background:#DBEAFE;padding:1px 7px;border-radius:20px;font-size:11px;">{{ $sub->services_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </details>
            @endif

            {{-- Services grid --}}
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                <h3 style="font-size:18px;font-weight:700;color:#111827;margin:0;">{{ $services->total() }}
                    Service{{ $services->total() !== 1 ? 's' : '' }}</h3>
                <a href="{{ route('services.index', ['category' => $category->slug]) }}"
                    style="font-size:13px;color:#3B82F6;text-decoration:none;">View with filters →</a>
            </div>

            <div class="row">
                @forelse($services as $service)
                    <div class="col-sm-6 col-md-4" style="margin-bottom:20px;">
                        <x-service-card :service="$service" />
                    </div>
                @empty
                    <div class="col-md-12 text-center" style="padding:60px 0;">
                        <h3 style="font-size:20px;font-weight:700;color:#111827;margin-bottom:10px;">No services in this category yet</h3>
                        <p style="color:#6B7280;">Active services will appear here once they are added.</p>
                    </div>
                @endforelse
            </div>

            @if ($services->hasPages())
                <div style="text-align:center;margin-top:20px;">{{ $services->links() }}</div>
            @endif
        </div>
    </div>

@endsection
