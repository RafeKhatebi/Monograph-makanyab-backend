@extends('layouts.app')
@section('title', 'Saved Places')
@section('content')

<div class="page-head">
    <div class="container">
        <div class="row">
            <div class="page-head-content">
                <h1 class="page-title">My Favorites</h1>
            </div>
        </div>
    </div>
</div>

<div class="content-area recent-property" style="background-color: #FCFCFC; padding: 55px 0;">
    <div class="container">
        @if($favorites->isEmpty())
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center" style="padding: 80px 20px;">
                    <i class="fa fa-heart-o" style="font-size: 80px; color: #E5E7EB; margin-bottom: 20px;"></i>
                    <h3 style="color: #111827; margin-bottom: 15px;">No Saved Places Yet</h3>
                    <p style="color: #6B7280; margin-bottom: 30px; font-size: 16px;">
                        Start exploring and save your favorite places to find them easily later.
                    </p>
                    <a href="{{ route('places.index') }}" class="btn btn-primary" style="padding: 12px 32px;">
                        <i class="fa fa-search"></i> Explore Places
                    </a>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-12">
                    <div class="page-subheader sorting">
                        <p class="text-muted">
                            <i class="fa fa-heart" style="color: #10B981;"></i> 
                            {{ $favorites->total() }} saved {{ Str::plural('place', $favorites->total()) }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="proerty-th">
                    @foreach($favorites as $favorite)
                        @include('components.place-card', ['place' => $favorite])
                    @endforeach
                </div>
            </div>

            @if($favorites->hasPages())
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            {{ $favorites->links() }}
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

@endsection
