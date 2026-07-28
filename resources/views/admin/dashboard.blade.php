@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <section aria-label="Statistics">
        <h2 class="sr-only">Dashboard Statistics</h2>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 32px;">
            <div class="stat-card" role="region" aria-label="Total Places">
                <div class="stat-card-icon green" aria-hidden="true">
                    <i class="fa fa-map-marker-alt"></i>
                </div>
                <div class="stat-card-info">
                    <p>Total Places</p>
                    <h6>{{ $stats['total_places'] }}</h6>
                    <small>{{ $stats['active_places'] }} Active / {{ $stats['pending_places'] }} Pending</small>
                </div>
            </div>
            <div class="stat-card" role="region" aria-label="Total Users">
                <div class="stat-card-icon blue" aria-hidden="true">
                    <i class="fa fa-users"></i>
                </div>
                <div class="stat-card-info">
                    <p>Total Users</p>
                    <h6>{{ $stats['total_users'] }}</h6>
                    <small>{{ $stats['admin_users'] }} Admin / {{ $stats['owner_users'] }} Owner</small>
                </div>
            </div>
            <div class="stat-card" role="region" aria-label="Categories">
                <div class="stat-card-icon purple" aria-hidden="true">
                    <i class="fa fa-tags"></i>
                </div>
                <div class="stat-card-info">
                    <p>Categories</p>
                    <h6>{{ $stats['total_categories'] }}</h6>
                    <small>{{ $stats['active_categories'] }} Active</small>
                </div>
            </div>
            <div class="stat-card" role="region" aria-label="Reviews">
                <div class="stat-card-icon amber" aria-hidden="true">
                    <i class="fa fa-star"></i>
                </div>
                <div class="stat-card-info">
                    <p>Reviews</p>
                    <h6>{{ $stats['total_reviews'] }}</h6>
                    <small>Avg: {{ number_format($stats['avg_rating'] ?? 0, 1) }}</small>
                </div>
            </div>
        </div>
    </section>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        <section class="card" aria-label="Recent Places">
            <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
                <h6 style="margin: 0; font-weight: 600;">Recent Places</h6>
                <a href="{{ route('admin.places.index') }}" style="font-size: var(--font-size-xs);">Show all</a>
            </div>
            <div class="card-body" style="padding: 0;">
                <table class="table" aria-label="Recent Places List">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats['recent_places'] as $place)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.places.show', $place) }}">{{ Str::limit($place->name, 30) }}</a>
                                </td>
                                <td>{{ $place->category->name ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $place->is_active ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $place->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 32px; color: var(--color-gray-400);">
                                    No places yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card" aria-label="Recent Users">
            <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
                <h6 style="margin: 0; font-weight: 600;">Recent Users</h6>
                <a href="{{ route('admin.users.index') }}" style="font-size: var(--font-size-xs);">Show all</a>
            </div>
            <div class="card-body" style="padding: 0;">
                <table class="table" aria-label="Recent Users List">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats['recent_users'] as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge badge-primary">{{ ucfirst($user->role) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 32px; color: var(--color-gray-400);">
                                    No users yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <style>
        @media (max-width: 991px) {
            div[style*="grid-template-columns: repeat(4"] {
                grid-template-columns: repeat(2, 1fr) !important;
            }
            div[style*="grid-template-columns: 1fr 1fr"] {
                grid-template-columns: 1fr !important;
            }
        }
        @media (max-width: 575px) {
            div[style*="grid-template-columns: repeat(4"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection
