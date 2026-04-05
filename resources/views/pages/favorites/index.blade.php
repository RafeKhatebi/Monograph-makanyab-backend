@extends('layouts.app')
@section('title', 'Saved Places')
@section('content')

<div class="mkb-page-bar">
    <div class="mkb-container">
        <h1 class="mkb-page-bar__title">Saved places</h1>
        <p class="mkb-page-bar__count">{{ $favorites->total() }} places saved</p>
    </div>
</div>

<div class="mkb-container mkb-section">
    @if($favorites->isEmpty())
        <div class="mkb-empty">
            <i class="fa fa-heart-o fa-3x"></i>
            <h3>No saved places yet</h3>
            <p><a href="{{ route('places.index') }}">Explore places</a> and save the ones you love.</p>
        </div>
    @else
        <div class="mkb-grid mkb-grid--4">
            @foreach($favorites as $place)
                @include('components.place-card', ['place' => $place])
            @endforeach
        </div>
        <div class="mkb-pagination">
            {{ $favorites->links() }}
        </div>
    @endif
</div>

@endsection
