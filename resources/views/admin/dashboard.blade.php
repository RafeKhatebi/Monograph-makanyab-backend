@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <section aria-label="Statistics">
        <h2 class="sr-only">Dashboard Statistics</h2>
        <div class="admin-stats-grid admin-dashboard-stats">
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
            <div class="stat-card" role="region" aria-label="Total Services">
                <div class="stat-card-icon blue" aria-hidden="true">
                    <i class="fa fa-briefcase"></i>
                </div>
                <div class="stat-card-info">
                    <p>Total Services</p>
                    <h6>{{ $stats['total_services'] }}</h6>
                    <small>{{ $stats['active_services'] }} Active / {{ $stats['inactive_services'] }} Inactive</small>
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
            <div class="stat-card" role="region" aria-label="Service Categories">
                <div class="stat-card-icon purple" aria-hidden="true">
                    <i class="fa fa-layer-group"></i>
                </div>
                <div class="stat-card-info">
                    <p>Service Categories</p>
                    <h6>{{ $stats['total_service_categories'] }}</h6>
                    <small>{{ $stats['active_service_categories'] }} Active</small>
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
            <div class="stat-card" role="region" aria-label="Posts">
                <div class="stat-card-icon green" aria-hidden="true">
                    <i class="fa fa-newspaper"></i>
                </div>
                <div class="stat-card-info">
                    <p>Posts</p>
                    <h6>{{ $stats['total_posts'] }}</h6>
                    <small>{{ $stats['published_posts'] }} Published / {{ $stats['draft_posts'] }} Draft</small>
                </div>
            </div>
            <div class="stat-card" role="region" aria-label="Contact Messages">
                <div class="stat-card-icon amber" aria-hidden="true">
                    <i class="fa fa-envelope"></i>
                </div>
                <div class="stat-card-info">
                    <p>Contact Messages</p>
                    <h6>{{ $stats['unread_contact_messages'] }}</h6>
                    <small>{{ $stats['archived_contact_messages'] }} Archived</small>
                </div>
            </div>
            <div class="stat-card" role="region" aria-label="Pending Suggestions">
                <div class="stat-card-icon blue" aria-hidden="true">
                    <i class="fa fa-lightbulb"></i>
                </div>
                <div class="stat-card-info">
                    <p>Pending Suggestions</p>
                    <h6>{{ $stats['pending_place_suggestions'] + $stats['pending_service_suggestions'] }}</h6>
                    <small>{{ $stats['pending_place_suggestions'] }} Places / {{ $stats['pending_service_suggestions'] }} Services</small>
                </div>
            </div>
        </div>
    </section>

    <div class="admin-two-column-grid admin-dashboard-grid">
        <section class="card" aria-label="Recent Places">
            <div class="card-header admin-card-header">
                <h6 class="admin-card-title">Recent Places</h6>
                <a href="{{ route('admin.places.index') }}" class="admin-card-link">Show all</a>
            </div>
            <div class="card-body admin-card-body-flush">
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
                                <td colspan="3" class="admin-empty admin-empty--compact">
                                    No places yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card" aria-label="Recent Contact Messages">
            <div class="card-header admin-card-header">
                <h6 class="admin-card-title">Recent Contact Messages</h6>
                <a href="{{ route('admin.contact-messages.index') }}" class="admin-card-link">Show all</a>
            </div>
            <div class="card-body admin-card-body-flush">
                <table class="table" aria-label="Recent Contact Messages List">
                    <thead>
                        <tr>
                            <th scope="col">From</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats['recent_contact_messages'] as $message)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.contact-messages.show', $message) }}">{{ Str::limit($message->name, 30) }}</a>
                                </td>
                                <td>{{ Str::limit($message->subject, 35) }}</td>
                                <td>
                                    <span class="badge {{ $message->read_at ? 'badge-success' : 'badge-warning' }}">
                                        {{ $message->read_at ? 'Read' : 'Unread' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="admin-empty admin-empty--compact">
                                    No messages yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card" aria-label="Recent Users">
            <div class="card-header admin-card-header">
                <h6 class="admin-card-title">Recent Users</h6>
                <a href="{{ route('admin.users.index') }}" class="admin-card-link">Show all</a>
            </div>
            <div class="card-body admin-card-body-flush">
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
                                <td colspan="3" class="admin-empty admin-empty--compact">
                                    No users yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

@endsection
