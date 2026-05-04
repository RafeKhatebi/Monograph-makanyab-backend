@extends('layouts.admin')

@section('title', 'Place Details')
@section('page-title', 'Place Details')

@section('content')
    <div class="bg-light rounded h-100 p-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
            <h6 class="mb-0">{{ $place->name }}</h6>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.places.edit', $place) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                <a href="{{ route('admin.places.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-auto">
                @if ($place->is_verified)
                    <span class="badge bg-success">Verified</span>
                @endif
            </div>
            <div class="col-auto">
                <span class="badge {{ $place->is_active ? 'bg-success' : 'bg-secondary' }}">
                    {{ $place->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h6 class="mb-2">Category</h6>
                    <p class="mb-0">{{ $place->category->name }}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h6 class="mb-2">Rating</h6>
                    <p class="mb-0">{{ number_format($place->average_rating, 1) }} ({{ $place->reviews_count }} reviews)</p>
                </div>
            </div>

            <div class="col-12">
                <div class="border rounded p-3">
                    <h6 class="mb-2">Description</h6>
                    <p class="mb-0">{{ $place->description }}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h6 class="mb-2">Address</h6>
                    <p class="mb-0">{{ $place->address }}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h6 class="mb-2">Phone</h6>
                    <p class="mb-0">{{ $place->phone_1 ?: 'N/A' }}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h6 class="mb-2">Coordinates</h6>
                    <p class="mb-0">{{ $place->latitude }}, {{ $place->longitude }}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h6 class="mb-2">Website</h6>
                    @if ($place->website)
                        <a href="{{ $place->website }}" target="_blank" rel="noopener noreferrer">{{ $place->website }}</a>
                    @else
                        <p class="mb-0">N/A</p>
                    @endif
                </div>
            </div>

            @if ($place->images && count($place->images) > 0)
                <div class="col-12">
                    <div class="border rounded p-3">
                        <h6 class="mb-3">Images</h6>
                        <div class="row g-3">
                            @foreach ($place->images as $image)
                                <div class="col-6 col-md-3">
                                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $place->name }}"
                                        class="img-fluid rounded border" style="height: 130px; width: 100%; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h6 class="mb-2">Created</h6>
                    <p class="mb-0">{{ $place->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h6 class="mb-2">Last Updated</h6>
                    <p class="mb-0">{{ $place->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
