@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 h-100">
                <i class="fa fa-map-marker-alt fa-3x text-primary"></i>
                <div class="ms-3 text-end">
                    <p class="mb-2">Total Places</p>
                    <h6 class="mb-0">{{ $stats['total_places'] }}</h6>
                    <small>{{ $stats['active_places'] }} Active / {{ $stats['pending_places'] }} Pending</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 h-100">
                <i class="fa fa-users fa-3x text-primary"></i>
                <div class="ms-3 text-end">
                    <p class="mb-2">Total Users</p>
                    <h6 class="mb-0">{{ $stats['total_users'] }}</h6>
                    <small>{{ $stats['admin_users'] }} Admin / {{ $stats['owner_users'] }} Owner</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 h-100">
                <i class="fa fa-tags fa-3x text-primary"></i>
                <div class="ms-3 text-end">
                    <p class="mb-2">Categories</p>
                    <h6 class="mb-0">{{ $stats['total_categories'] }}</h6>
                    <small>{{ $stats['active_categories'] }} Active</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 h-100">
                <i class="fa fa-star fa-3x text-primary"></i>
                <div class="ms-3 text-end">
                    <p class="mb-2">Reviews</p>
                    <h6 class="mb-0">{{ $stats['total_reviews'] }}</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="bg-light rounded p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="mb-0">Recent Places</h6>
                    <a href="{{ route('admin.places.index') }}">Show all</a>
                </div>
                <div class="table-responsive">
                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                        <thead>
                            <tr class="text-dark">
                                <th>Name</th>
                                <th>Category</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['recent_places'] as $place)
                                <tr>
                                    <td><a
                                            href="{{ route('admin.places.show', $place) }}">{{ Str::limit($place->name, 30) }}</a>
                                    </td>
                                    <td>{{ $place->category->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $place->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $place->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No places yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="bg-light rounded p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="mb-0">Recent Users</h6>
                    <a href="{{ route('admin.users.index') }}">Show all</a>
                </div>
                <div class="table-responsive">
                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                        <thead>
                            <tr class="text-dark">
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['recent_users'] as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><span class="badge bg-primary">{{ ucfirst($user->role) }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No users yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
