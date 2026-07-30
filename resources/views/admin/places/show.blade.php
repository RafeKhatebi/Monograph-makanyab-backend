@extends('layouts.admin')

@section('title', 'Place Details')
@section('page-title', 'Place Details')

@section('content')
    <div class="card">
        <div class="card-header admin-card-header">
            <div class="admin-title-group">
                <h6 class="admin-card-title">{{ $place->name }}</h6>
                @if ($place->is_verified)
                    <span class="badge badge-success">Verified</span>
                @endif
                <span class="badge {{ $place->is_active ? 'badge-success' : 'badge-secondary' }}">
                    {{ $place->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="admin-header-actions">
                <a href="{{ route('admin.places.edit', $place) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.places.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="admin-detail-grid">
                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">Category</p>
                        <p class="admin-detail-value admin-detail-value--strong">{{ $place->category->name }}</p>
                    </div>
                </div>

                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">Rating</p>
                        <p class="admin-detail-value admin-detail-value--strong">
                            <span class="admin-rating"><i class="fa fa-star"></i></span>
                            {{ number_format($place->average_rating, 1) }} ({{ $place->reviews_count }} reviews)
                        </p>
                    </div>
                </div>

                <div class="admin-full-span">
                    <div class="card admin-detail-card">
                        <div class="card-body">
                            <p class="admin-detail-label">Description</p>
                            <p class="admin-detail-value">{{ $place->description }}</p>
                        </div>
                    </div>
                </div>

                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">Address</p>
                        <p class="admin-detail-value">{{ $place->address }}</p>
                    </div>
                </div>

                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">Phone</p>
                        <p class="admin-detail-value">{{ $place->phone_1 ?: 'N/A' }}</p>
                    </div>
                </div>

                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">Coordinates</p>
                        <p class="admin-detail-value admin-code">{{ $place->latitude }}, {{ $place->longitude }}</p>
                    </div>
                </div>

                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">Website</p>
                        @if ($place->website)
                            <a href="{{ $place->website }}" target="_blank" rel="noopener noreferrer" class="admin-detail-link">{{ $place->website }}</a>
                        @else
                            <p class="admin-detail-value admin-muted">N/A</p>
                        @endif
                    </div>
                </div>

                @if ($place->images && count($place->images) > 0)
                    <div class="admin-full-span">
                        <div class="card admin-detail-card">
                            <div class="card-body">
                                <p class="admin-detail-label">Images</p>
                                <div class="admin-image-grid">
                                    @foreach ($place->images as $image)
                                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $place->name }}"
                                            class="admin-gallery-image">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">Created</p>
                        <p class="admin-detail-value">{{ $place->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">Last Updated</p>
                        <p class="admin-detail-value">{{ $place->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
