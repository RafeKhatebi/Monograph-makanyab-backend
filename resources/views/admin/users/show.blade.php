@extends('layouts.admin')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('content')
    <section class="card" aria-label="User Details">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">{{ $user->name }}</h2>
            <div class="admin-header-actions">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-edit" aria-hidden="true"></i> Edit
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="admin-status-row">
                <span class="badge {{ $user->role === 'admin' ? 'badge-primary' : ($user->role === 'owner' ? 'badge-info' : 'badge-secondary') }}">
                    {{ ucfirst($user->role) }}
                </span>
                <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <div class="admin-detail-grid">
                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">Email</p>
                        <p class="admin-detail-value">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">Email Verified</p>
                        <p class="admin-detail-value">{{ $user->email_verified_at ? 'Yes' : 'No' }}</p>
                    </div>
                </div>

                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">Total Reviews</p>
                        <p class="admin-detail-value admin-detail-value--metric">{{ $user->reviews_count }}</p>
                    </div>
                </div>

                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">Total Favorites</p>
                        <p class="admin-detail-value admin-detail-value--metric">{{ $user->favorites_count }}</p>
                    </div>
                </div>

                @if ($user->role === 'owner')
                    <div class="card admin-detail-card">
                        <div class="card-body">
                            <p class="admin-detail-label">Owned Places</p>
                            <p class="admin-detail-value admin-detail-value--metric">{{ $user->places_count }}</p>
                        </div>
                    </div>
                @endif

                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">Joined</p>
                        <p class="admin-detail-value">{{ $user->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">Last Updated</p>
                        <p class="admin-detail-value">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
