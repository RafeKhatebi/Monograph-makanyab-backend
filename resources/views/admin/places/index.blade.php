@extends('layouts.admin')

@section('title', 'Manage Places')
@section('page-title', 'Places')

@section('content')
    <div class="bg-light rounded h-100 p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">All Places ({{ $places->total() }})</h6>
            <a href="{{ route('admin.places.create') }}" class="btn btn-primary btn-sm">+ Add New Place</a>
        </div>

        <form method="GET" action="{{ route('admin.places.index') }}" class="row g-2 mb-4">
            <div class="col-md-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search places..."
                    class="form-control">
            </div>
            <div class="col-md-2">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="is_verified" class="form-select">
                    <option value="">All Verification</option>
                    <option value="1" {{ request('is_verified') === '1' ? 'selected' : '' }}>Verified</option>
                    <option value="0" {{ request('is_verified') === '0' ? 'selected' : '' }}>Not Verified</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="is_active" class="form-select">
                    <option value="">All Status</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                <a href="{{ route('admin.places.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-dark">
                        <th>Name</th>
                        <th>Category</th>
                        <th>Address</th>
                        <th>Reviews</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($places as $place)
                        <tr>
                            <td>
                                {{ $place->name }}
                                @if ($place->is_verified)
                                    <span class="badge bg-success ms-1">Verified</span>
                                @endif
                            </td>
                            <td>{{ $place->category->name ?? '-' }}</td>
                            <td>{{ Str::limit($place->address, 30) }}</td>
                            <td>{{ $place->reviews_count }}</td>
                            <td>{{ number_format($place->reviews_avg_rating ?? 0, 1) }}</td>
                            <td>
                                <span class="badge {{ $place->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $place->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.places.show', $place) }}"
                                        class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="{{ route('admin.places.edit', $place) }}"
                                        class="btn btn-sm btn-outline-success">Edit</a>
                                    <form action="{{ route('admin.places.destroy', $place) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this place?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No places found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($places->hasPages())
            <div class="pt-4">
                {{ $places->links() }}
            </div>
        @endif
    </div>
@endsection
