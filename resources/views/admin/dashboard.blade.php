@extends('layouts.admin')

@section('title', __('admin.navigation.dashboard'))
@section('page-title', __('admin.navigation.dashboard'))

@section('content')
    <section aria-label="{{ __('admin.dashboard.statistics') }}">
        <h2 class="sr-only">{{ __('admin.dashboard.statistics') }}</h2>
        <div class="admin-stats-grid admin-dashboard-stats">
            <div class="stat-card" role="region" aria-label="{{ __('admin.dashboard.total_places') }}">
                <div class="stat-card-icon green" aria-hidden="true">
                    <i class="fa fa-map-marker-alt"></i>
                </div>
                <div class="stat-card-info">
                    <p>{{ __('admin.dashboard.total_places') }}</p>
                    <h6>{{ $stats['total_places'] }}</h6>
                    <small>{{ $stats['active_places'] }} {{ __('admin.dashboard.active') }} / {{ $stats['pending_places'] }} {{ __('admin.dashboard.pending') }}</small>
                </div>
            </div>
            <div class="stat-card" role="region" aria-label="{{ __('admin.dashboard.total_users') }}">
                <div class="stat-card-icon blue" aria-hidden="true">
                    <i class="fa fa-users"></i>
                </div>
                <div class="stat-card-info">
                    <p>{{ __('admin.dashboard.total_users') }}</p>
                    <h6>{{ $stats['total_users'] }}</h6>
                    <small>{{ $stats['admin_users'] }} {{ __('admin.dashboard.admin') }} / {{ $stats['owner_users'] }} {{ __('admin.dashboard.owner') }}</small>
                </div>
            </div>
            <div class="stat-card" role="region" aria-label="{{ __('admin.dashboard.total_services') }}">
                <div class="stat-card-icon blue" aria-hidden="true">
                    <i class="fa fa-briefcase"></i>
                </div>
                <div class="stat-card-info">
                    <p>{{ __('admin.dashboard.total_services') }}</p>
                    <h6>{{ $stats['total_services'] }}</h6>
                    <small>{{ $stats['active_services'] }} {{ __('admin.dashboard.active') }} / {{ $stats['inactive_services'] }} {{ __('admin.dashboard.inactive') }}</small>
                </div>
            </div>
            <div class="stat-card" role="region" aria-label="Categories">
                <div class="stat-card-icon purple" aria-hidden="true">
                    <i class="fa fa-tags"></i>
                </div>
                <div class="stat-card-info">
                    <p>{{ __('admin.dashboard.categories') }}</p>
                    <h6>{{ $stats['total_categories'] }}</h6>
                    <small>{{ $stats['active_categories'] }} {{ __('admin.dashboard.active') }}</small>
                </div>
            </div>
            <div class="stat-card" role="region" aria-label="Service Categories">
                <div class="stat-card-icon purple" aria-hidden="true">
                    <i class="fa fa-layer-group"></i>
                </div>
                <div class="stat-card-info">
                    <p>{{ __('admin.dashboard.service_categories') }}</p>
                    <h6>{{ $stats['total_service_categories'] }}</h6>
                    <small>{{ $stats['active_service_categories'] }} {{ __('admin.dashboard.active') }}</small>
                </div>
            </div>
            <div class="stat-card" role="region" aria-label="Reviews">
                <div class="stat-card-icon amber" aria-hidden="true">
                    <i class="fa fa-star"></i>
                </div>
                <div class="stat-card-info">
                    <p>{{ __('admin.dashboard.reviews') }}</p>
                    <h6>{{ $stats['total_reviews'] }}</h6>
                    <small>Avg: {{ number_format($stats['avg_rating'] ?? 0, 1) }}</small>
                </div>
            </div>
            <div class="stat-card" role="region" aria-label="Posts">
                <div class="stat-card-icon green" aria-hidden="true">
                    <i class="fa fa-newspaper"></i>
                </div>
                <div class="stat-card-info">
                    <p>{{ __('admin.dashboard.posts') }}</p>
                    <h6>{{ $stats['total_posts'] }}</h6>
                    <small>{{ $stats['published_posts'] }} {{ __('admin.dashboard.published') }} / {{ $stats['draft_posts'] }} {{ __('admin.dashboard.draft') }}</small>
                </div>
            </div>
            <div class="stat-card" role="region" aria-label="Contact Messages">
                <div class="stat-card-icon amber" aria-hidden="true">
                    <i class="fa fa-envelope"></i>
                </div>
                <div class="stat-card-info">
                    <p>{{ __('admin.dashboard.contact_messages') }}</p>
                    <h6>{{ $stats['unread_contact_messages'] }}</h6>
                    <small>{{ $stats['archived_contact_messages'] }} {{ __('admin.dashboard.archived') }}</small>
                </div>
            </div>
            <div class="stat-card" role="region" aria-label="Pending Suggestions">
                <div class="stat-card-icon blue" aria-hidden="true">
                    <i class="fa fa-lightbulb"></i>
                </div>
                <div class="stat-card-info">
                    <p>{{ __('admin.dashboard.pending_suggestions') }}</p>
                    <h6>{{ $stats['pending_place_suggestions'] + $stats['pending_service_suggestions'] }}</h6>
                    <small>{{ $stats['pending_place_suggestions'] }} {{ __('admin.dashboard.places') }} / {{ $stats['pending_service_suggestions'] }} {{ __('admin.dashboard.services') }}</small>
                </div>
            </div>
        </div>
    </section>

    <div class="admin-two-column-grid admin-dashboard-grid">
        <section class="card" aria-label="{{ __('admin.dashboard.recent_places') }}">
            <div class="card-header admin-card-header">
                <h6 class="admin-card-title">{{ __('admin.dashboard.recent_places') }}</h6>
                <a href="{{ route('admin.places.index') }}" class="admin-card-link">{{ __('admin.dashboard.show_all') }}</a>
            </div>
            <div class="card-body admin-card-body-flush">
                <table class="table" aria-label="Recent Places List">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('admin.dashboard.name') }}</th>
                            <th scope="col">{{ __('admin.dashboard.category') }}</th>
                            <th scope="col">{{ __('admin.dashboard.status') }}</th>
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
                                        {{ $place->is_active ? __('admin.dashboard.active') : __('admin.dashboard.inactive') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="admin-empty admin-empty--compact">
                                    {{ __('admin.dashboard.empty_places') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card" aria-label="{{ __('admin.dashboard.recent_messages') }}">
            <div class="card-header admin-card-header">
                <h6 class="admin-card-title">{{ __('admin.dashboard.recent_messages') }}</h6>
                <a href="{{ route('admin.contact-messages.index') }}" class="admin-card-link">{{ __('admin.dashboard.show_all') }}</a>
            </div>
            <div class="card-body admin-card-body-flush">
                <table class="table" aria-label="Recent Contact Messages List">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('admin.dashboard.from') }}</th>
                            <th scope="col">{{ __('admin.dashboard.subject') }}</th>
                            <th scope="col">{{ __('admin.dashboard.status') }}</th>
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
                                        {{ $message->read_at ? __('admin.dashboard.read') : __('admin.dashboard.unread') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="admin-empty admin-empty--compact">
                                    {{ __('admin.dashboard.empty_messages') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card" aria-label="{{ __('admin.dashboard.recent_users') }}">
            <div class="card-header admin-card-header">
                <h6 class="admin-card-title">{{ __('admin.dashboard.recent_users') }}</h6>
                <a href="{{ route('admin.users.index') }}" class="admin-card-link">{{ __('admin.dashboard.show_all') }}</a>
            </div>
            <div class="card-body admin-card-body-flush">
                <table class="table" aria-label="Recent Users List">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('admin.dashboard.name') }}</th>
                            <th scope="col">{{ __('admin.dashboard.email') }}</th>
                            <th scope="col">{{ __('admin.dashboard.role') }}</th>
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
                                    {{ __('admin.dashboard.empty_users') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

@endsection
