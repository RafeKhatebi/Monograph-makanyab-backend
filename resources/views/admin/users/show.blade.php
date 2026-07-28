@extends('layouts.admin')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('content')
    <section class="card" aria-label="User Details">
        <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
            <h2 style="margin: 0; font-weight: 600; font-size: var(--font-size-base);">{{ $user->name }}</h2>
            <div style="display: flex; gap: 8px;">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-edit" aria-hidden="true"></i> Edit
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">
            <div style="display: flex; gap: 12px; margin-bottom: 24px;">
                <span class="badge {{ $user->role === 'admin' ? 'badge-primary' : ($user->role === 'owner' ? 'badge-info' : 'badge-secondary') }}">
                    {{ ucfirst($user->role) }}
                </span>
                <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="card" style="border: 1px solid var(--color-gray-200);">
                    <div class="card-body">
                        <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin: 0 0 4px; text-transform: uppercase; letter-spacing: 0.05em;">Email</p>
                        <p style="margin: 0;">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="card" style="border: 1px solid var(--color-gray-200);">
                    <div class="card-body">
                        <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin: 0 0 4px; text-transform: uppercase; letter-spacing: 0.05em;">Email Verified</p>
                        <p style="margin: 0;">{{ $user->email_verified_at ? 'Yes' : 'No' }}</p>
                    </div>
                </div>

                <div class="card" style="border: 1px solid var(--color-gray-200);">
                    <div class="card-body">
                        <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin: 0 0 4px; text-transform: uppercase; letter-spacing: 0.05em;">Total Reviews</p>
                        <p style="margin: 0; font-size: var(--font-size-2xl); font-weight: var(--font-weight-bold);">{{ $user->reviews_count }}</p>
                    </div>
                </div>

                <div class="card" style="border: 1px solid var(--color-gray-200);">
                    <div class="card-body">
                        <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin: 0 0 4px; text-transform: uppercase; letter-spacing: 0.05em;">Total Favorites</p>
                        <p style="margin: 0; font-size: var(--font-size-2xl); font-weight: var(--font-weight-bold);">{{ $user->favorites_count }}</p>
                    </div>
                </div>

                @if ($user->role === 'owner')
                    <div class="card" style="border: 1px solid var(--color-gray-200);">
                        <div class="card-body">
                            <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin: 0 0 4px; text-transform: uppercase; letter-spacing: 0.05em;">Owned Places</p>
                            <p style="margin: 0; font-size: var(--font-size-2xl); font-weight: var(--font-weight-bold);">{{ $user->places_count }}</p>
                        </div>
                    </div>
                @endif

                <div class="card" style="border: 1px solid var(--color-gray-200);">
                    <div class="card-body">
                        <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin: 0 0 4px; text-transform: uppercase; letter-spacing: 0.05em;">Joined</p>
                        <p style="margin: 0;">{{ $user->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                <div class="card" style="border: 1px solid var(--color-gray-200);">
                    <div class="card-body">
                        <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin: 0 0 4px; text-transform: uppercase; letter-spacing: 0.05em;">Last Updated</p>
                        <p style="margin: 0;">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        @media (max-width: 767px) {
            div[style*="grid-template-columns: 1fr 1fr"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection
