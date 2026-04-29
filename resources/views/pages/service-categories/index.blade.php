@extends('layouts.app')
@section('title', 'Service Categories')
@section('content')

    <div style="background:linear-gradient(135deg,#1e3a5f,#3B82F6);padding:50px 0;">
        <div class="container text-center">
            <h1 style="font-size:32px;font-weight:800;color:#fff;margin-bottom:10px;">Service Categories</h1>
            <p style="color:rgba(255,255,255,.85);font-size:16px;margin:0;">Find the right service provider by category.</p>
        </div>
    </div>

    <div style="background:#F8FAFC;padding:50px 0 70px;">
        <div class="container">
            <div class="row">
                @forelse($categories as $category)
                    <div class="col-sm-6 col-md-3" style="margin-bottom:20px;">
                        <a href="{{ route('service-categories.show', $category->slug) }}" style="text-decoration:none;">
                            <div class="scat-card"
                                style="background:#fff;border:1px solid #E5E7EB;border-radius:14px;padding:28px 20px;text-align:center;transition:all .2s;">
                                <div
                                    style="width:64px;height:64px;background:#EFF6FF;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:28px;color:{{ $category->color_code ?? '#3B82F6' }};">
                                    <i class="fa {{ $category->icon_name ?? 'fa-briefcase' }}"></i>
                                </div>
                                <h5 style="font-size:16px;font-weight:700;color:#111827;margin-bottom:6px;">
                                    {{ $category->name }}</h5>
                                @if ($category->parent)
                                    <p style="font-size:12px;color:#9CA3AF;margin-bottom:6px;">in
                                        {{ $category->parent->name }}</p>
                                @endif
                                <span
                                    style="display:inline-block;background:#EFF6FF;color:#3B82F6;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">
                                    {{ $category->services_count ?? 0 }} services
                                </span>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-xs-12 text-center" style="padding:80px 0;">
                        <div style="font-size:56px;margin-bottom:16px;">📂</div>
                        <h3 style="font-size:22px;font-weight:700;color:#111827;margin-bottom:12px;">No service categories
                            yet</h3>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .scat-card:hover {
                box-shadow: 0 4px 20px rgba(0, 0, 0, .08);
                border-color: #3B82F6 !important;
                transform: translateY(-2px);
            }
        </style>
    @endpush

@endsection
