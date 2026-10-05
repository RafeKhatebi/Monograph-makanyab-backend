@extends('layouts.admin')

@section('title', __('admin.users.details'))
@section('page-title', __('admin.users.details'))

@section('content')
    <section class="card" aria-label="{{ __('admin.users.details') }}">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">{{ $user->name }}</h2>
            <div class="admin-header-actions">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-edit" aria-hidden="true"></i> {{ __('admin.users.edit_action') }}
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> {{ __('admin.users.back_short') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="admin-status-row">
                <span class="badge {{ $user->role === 'admin' ? 'badge-primary' : ($user->role === 'owner' ? 'badge-info' : 'badge-secondary') }}">
                    {{ __('admin.dashboard.'.$user->role) }}
                </span>
                <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">
                    {{ $user->is_active ? __('admin.users.active') : __('admin.users.inactive') }}
                </span>
            </div>

            <div class="admin-detail-grid">
                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">{{ __('admin.users.email') }}</p>
                        <p class="admin-detail-value">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">{{ __('admin.users.email_verified') }}</p>
                        <p class="admin-detail-value">{{ $user->email_verified_at ? __('common.yes') : __('common.no') }}</p>
                    </div>
                </div>

                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">{{ __('admin.users.total_reviews') }}</p>
                        <p class="admin-detail-value admin-detail-value--metric">{{ $user->reviews_count }}</p>
                    </div>
                </div>

                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">{{ __('admin.users.total_favorites') }}</p>
                        <p class="admin-detail-value admin-detail-value--metric">{{ $user->favorites_count }}</p>
                    </div>
                </div>

                @if ($user->role === 'owner')
                    <div class="card admin-detail-card">
                        <div class="card-body">
                            <p class="admin-detail-label">{{ __('admin.users.owned_places') }}</p>
                            <p class="admin-detail-value admin-detail-value--metric">{{ $user->places_count }}</p>
                        </div>
                    </div>
                @endif

                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">{{ __('admin.users.joined') }}</p>
                        <p class="admin-detail-value">{{ \App\Support\LocalizedDate::dateTime($user->created_at) }}</p>
                    </div>
                </div>

                <div class="card admin-detail-card">
                    <div class="card-body">
                        <p class="admin-detail-label">{{ __('admin.users.last_updated') }}</p>
                        <p class="admin-detail-value">{{ \App\Support\LocalizedDate::dateTime($user->updated_at) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
