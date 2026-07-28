@extends('layouts.admin')

@section('title', 'Place Details')
@section('page-title', 'Place Details')

@section('content')
    <div class="card">
        <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <h6 style="margin: 0; font-weight: 600;">{{ $place->name }}</h6>
                @if ($place->is_verified)
                    <span class="badge badge-success">Verified</span>
                @endif
                <span class="badge {{ $place->is_active ? 'badge-success' : 'badge-secondary' }}">
                    {{ $place->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div style="display: flex; gap: 8px;">
                <a href="{{ route('admin.places.edit', $place) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.places.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="card" style="border: 1px solid var(--color-gray-200);">
                    <div class="card-body">
                        <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin: 0 0 4px; text-transform: uppercase; letter-spacing: 0.05em;">Category</p>
                        <p style="margin: 0; font-weight: var(--font-weight-medium);">{{ $place->category->name }}</p>
                    </div>
                </div>

                <div class="card" style="border: 1px solid var(--color-gray-200);">
                    <div class="card-body">
                        <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin: 0 0 4px; text-transform: uppercase; letter-spacing: 0.05em;">Rating</p>
                        <p style="margin: 0; font-weight: var(--font-weight-medium);">
                            <span style="color: var(--color-warning);"><i class="fa fa-star"></i></span>
                            {{ number_format($place->average_rating, 1) }} ({{ $place->reviews_count }} reviews)
                        </p>
                    </div>
                </div>

                <div style="grid-column: 1 / -1;">
                    <div class="card" style="border: 1px solid var(--color-gray-200);">
                        <div class="card-body">
                            <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin: 0 0 4px; text-transform: uppercase; letter-spacing: 0.05em;">Description</p>
                            <p style="margin: 0;">{{ $place->description }}</p>
                        </div>
                    </div>
                </div>

                <div class="card" style="border: 1px solid var(--color-gray-200);">
                    <div class="card-body">
                        <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin: 0 0 4px; text-transform: uppercase; letter-spacing: 0.05em;">Address</p>
                        <p style="margin: 0;">{{ $place->address }}</p>
                    </div>
                </div>

                <div class="card" style="border: 1px solid var(--color-gray-200);">
                    <div class="card-body">
                        <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin: 0 0 4px; text-transform: uppercase; letter-spacing: 0.05em;">Phone</p>
                        <p style="margin: 0;">{{ $place->phone_1 ?: 'N/A' }}</p>
                    </div>
                </div>

                <div class="card" style="border: 1px solid var(--color-gray-200);">
                    <div class="card-body">
                        <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin: 0 0 4px; text-transform: uppercase; letter-spacing: 0.05em;">Coordinates</p>
                        <p style="margin: 0; font-family: monospace; font-size: var(--font-size-xs);">{{ $place->latitude }}, {{ $place->longitude }}</p>
                    </div>
                </div>

                <div class="card" style="border: 1px solid var(--color-gray-200);">
                    <div class="card-body">
                        <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin: 0 0 4px; text-transform: uppercase; letter-spacing: 0.05em;">Website</p>
                        @if ($place->website)
                            <a href="{{ $place->website }}" target="_blank" rel="noopener noreferrer" style="font-size: var(--font-size-sm);">{{ $place->website }}</a>
                        @else
                            <p style="margin: 0; color: var(--color-gray-400);">N/A</p>
                        @endif
                    </div>
                </div>

                @if ($place->images && count($place->images) > 0)
                    <div style="grid-column: 1 / -1;">
                        <div class="card" style="border: 1px solid var(--color-gray-200);">
                            <div class="card-body">
                                <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin: 0 0 12px; text-transform: uppercase; letter-spacing: 0.05em;">Images</p>
                                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
                                    @foreach ($place->images as $image)
                                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $place->name }}"
                                            style="height: 130px; width: 100%; object-fit: cover; border-radius: var(--radius-md); border: 1px solid var(--color-gray-200);">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card" style="border: 1px solid var(--color-gray-200);">
                    <div class="card-body">
                        <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin: 0 0 4px; text-transform: uppercase; letter-spacing: 0.05em;">Created</p>
                        <p style="margin: 0;">{{ $place->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                <div class="card" style="border: 1px solid var(--color-gray-200);">
                    <div class="card-body">
                        <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin: 0 0 4px; text-transform: uppercase; letter-spacing: 0.05em;">Last Updated</p>
                        <p style="margin: 0;">{{ $place->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 767px) {
            div[style*="grid-template-columns: 1fr 1fr"] {
                grid-template-columns: 1fr !important;
            }
            div[style*="grid-template-columns: repeat(4"] {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }
    </style>
@endsection
