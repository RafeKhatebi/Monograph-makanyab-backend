@extends('layouts.app')
@section('title', 'Saved Places')
@section('content')

{{-- Header --}}
<div style="background:linear-gradient(135deg,#064e3b,#10B981);padding:40px 0;">
    <div class="container">
        <h1 style="font-size:30px;font-weight:800;color:#fff;margin:0 0 6px;">My Favorites</h1>
        <p style="color:rgba(255,255,255,.8);margin:0;font-size:15px;">Places you've saved for later.</p>
    </div>
</div>

<div style="background:#F8FAFC;padding:40px 0 70px;">
    <div class="container">
        @if($favorites->isEmpty())
            <div style="background:#fff;border-radius:14px;padding:80px 30px;text-align:center;border:1px solid #E5E7EB;">
                <div style="font-size:56px;margin-bottom:16px;">❤️</div>
                <h3 style="font-size:22px;font-weight:700;color:#111827;margin-bottom:12px;">No Saved Places Yet</h3>
                <p style="color:#6B7280;margin-bottom:24px;max-width:400px;margin-left:auto;margin-right:auto;">
                    Start exploring and save your favorite places to find them easily later.
                </p>
                <a href="{{ route('places.index') }}" style="background:#10B981;color:#fff;padding:12px 28px;border-radius:10px;font-weight:700;text-decoration:none;font-size:15px;">
                    <i class="fa fa-search"></i> Explore Places
                </a>
            </div>
        @else
            <div style="margin-bottom:20px;">
                <p style="color:#6B7280;font-size:14px;margin:0;">
                    <i class="fa fa-heart" style="color:#10B981;"></i>
                    {{ $favorites->total() }} saved {{ $favorites->total() === 1 ? 'place' : 'places' }}
                </p>
            </div>
            <div class="row">
                @foreach($favorites as $favorite)
                    @include('components.place-card', ['place' => $favorite])
                @endforeach
            </div>
            @if($favorites->hasPages())
                <div style="text-align:center;margin-top:20px;">{{ $favorites->links() }}</div>
            @endif
        @endif
    </div>
</div>

@endsection
