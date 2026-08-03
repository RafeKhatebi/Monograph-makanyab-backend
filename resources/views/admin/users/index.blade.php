@extends('layouts.admin')

@section('title', 'Manage Users')
@section('page-title', 'Users')

@section('content')
    <section class="card" aria-label="Users Management">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">All Users ({{ $users->total() }})</h2>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus" aria-hidden="true"></i> Add New User
            </a>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" role="search" aria-label="Filter users" class="admin-filter-form">
                <div class="admin-filter-field">
                    <label for="search" class="sr-only">Search users</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search users..."
                        class="form-control">
                </div>
                <div>
                    <label for="role" class="sr-only">Filter by role</label>
                    <select id="role" name="role" class="form-select admin-filter-select">
                        <option value="">All Roles</option>
                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                        <option value="owner" {{ request('role') === 'owner' ? 'selected' : '' }}>Owner</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div>
                    <label for="is_active" class="sr-only">Filter by status</label>
                    <select id="is_active" name="is_active" class="form-select admin-filter-select admin-filter-select--sm">
                        <option value="">All Status</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter" aria-hidden="true"></i> Filter
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Clear</a>
            </form>

            <div class="admin-table-wrap">
                <table class="table" aria-label="Users list">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Reviews</th>
                            <th scope="col">Favorites</th>
                            <th scope="col">Status</th>
                            <th scope="col">Joined</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge badge-primary">{{ ucfirst($user->role) }}</span></td>
                                <td>{{ $user->reviews_count }}</td>
                                <td>{{ $user->favorites_count }}</td>
                                <td>
                                    <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="admin-actions">
                                        <a href="{{ route('admin.users.show', $user) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            aria-label="View {{ $user->name }}">View</a>
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="btn btn-sm btn-outline-success"
                                            aria-label="Edit {{ $user->name }}">Edit</a>
                                        @if ($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this user?');"
                                                class="admin-action-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    aria-label="Delete {{ $user->name }}">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="admin-empty">
                                    No users found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <nav class="admin-pagination" aria-label="Users pagination">
                    {{ $users->links() }}
                </nav>
            @endif
        </div>
    </section>
@endsection
