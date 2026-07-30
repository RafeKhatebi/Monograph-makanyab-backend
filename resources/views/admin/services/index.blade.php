@extends('layouts.admin')

@section('title', 'Manage Services')
@section('page-title', 'Services')

@section('content')
    <section class="card" aria-label="Services Management">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">All Services ({{ $services->total() }})</h2>
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus" aria-hidden="true"></i> Add New Service
            </a>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.services.index') }}" role="search" aria-label="Filter services" class="admin-filter-form">
                <div class="admin-filter-field">
                    <label for="search" class="sr-only">Search services</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search services..."
                        class="form-control">
                </div>
                <div>
                    <label for="service_category" class="sr-only">Filter by category</label>
                    <select id="service_category" name="service_category" class="form-select admin-filter-select">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('service_category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="is_verified" class="sr-only">Filter by verification status</label>
                    <select id="is_verified" name="is_verified" class="form-select admin-filter-select">
                        <option value="">All Verification</option>
                        <option value="1" {{ request('is_verified') === '1' ? 'selected' : '' }}>Verified</option>
                        <option value="0" {{ request('is_verified') === '0' ? 'selected' : '' }}>Not Verified</option>
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
                <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">Clear</a>
            </form>

            <div class="admin-table-wrap">
                <table class="table" aria-label="Services list">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Address</th>
                            <th scope="col">Owner</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                            <tr>
                                <td>
                                    {{ $service->name }}
                                    @if ($service->is_verified)
                                        <span class="badge badge-success admin-ml-1">Verified</span>
                                    @endif
                                </td>
                                <td>{{ $service->category->name ?? '-' }}</td>
                                <td>{{ Str::limit($service->address, 35) }}</td>
                                <td>{{ $service->user->name ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $service->is_active ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="admin-actions">
                                        <a href="{{ route('admin.services.show', $service) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            aria-label="View {{ $service->name }}">View</a>
                                        <a href="{{ route('admin.services.edit', $service) }}"
                                            class="btn btn-sm btn-outline-success"
                                            aria-label="Edit {{ $service->name }}">Edit</a>
                                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this service?');"
                                            class="admin-action-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                aria-label="Delete {{ $service->name }}">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="admin-empty">
                                    No services found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($services->hasPages())
                <nav class="admin-pagination" aria-label="Services pagination">
                    {{ $services->links() }}
                </nav>
            @endif
        </div>
    </section>
@endsection
