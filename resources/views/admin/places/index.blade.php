@extends('layouts.admin')

@section('title', 'Manage Places')
@section('page-title', 'Places')

@section('content')
    <section class="card" aria-label="Places Management">
        <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
            <h2 style="margin: 0; font-weight: 600; font-size: var(--font-size-base);">All Places ({{ $places->total() }})</h2>
            <a href="{{ route('admin.places.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus" aria-hidden="true"></i> Add New Place
            </a>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.places.index') }}" role="search" aria-label="Filter places" style="display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 24px;">
                <div style="flex: 1; min-width: 200px;">
                    <label for="search" class="sr-only">Search places</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search places..."
                        class="form-control">
                </div>
                <div>
                    <label for="category" class="sr-only">Filter by category</label>
                    <select id="category" name="category" class="form-select" style="width: auto; min-width: 150px;">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="is_verified" class="sr-only">Filter by verification status</label>
                    <select id="is_verified" name="is_verified" class="form-select" style="width: auto; min-width: 140px;">
                        <option value="">All Verification</option>
                        <option value="1" {{ request('is_verified') === '1' ? 'selected' : '' }}>Verified</option>
                        <option value="0" {{ request('is_verified') === '0' ? 'selected' : '' }}>Not Verified</option>
                    </select>
                </div>
                <div>
                    <label for="is_active" class="sr-only">Filter by status</label>
                    <select id="is_active" name="is_active" class="form-select" style="width: auto; min-width: 120px;">
                        <option value="">All Status</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter" aria-hidden="true"></i> Filter
                </button>
                <a href="{{ route('admin.places.index') }}" class="btn btn-outline-secondary">
                    Clear
                </a>
            </form>

            <div style="overflow-x: auto;">
                <table class="table" aria-label="Places list">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Address</th>
                            <th scope="col">Reviews</th>
                            <th scope="col">Rating</th>
                            <th scope="col">Status</th>
                            <th scope="col" style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($places as $place)
                            <tr>
                                <td>
                                    <div style="font-weight: var(--font-weight-medium); color: var(--color-gray-900);">
                                        {{ $place->name }}
                                    </div>
                                    @if ($place->is_verified)
                                        <span class="badge badge-success" style="margin-top: 4px;">Verified</span>
                                    @endif
                                </td>
                                <td>{{ $place->category->name ?? '-' }}</td>
                                <td style="color: var(--color-gray-500);">{{ Str::limit($place->address, 30) }}</td>
                                <td>{{ $place->reviews_count }}</td>
                                <td>
                                    <span style="color: var(--color-warning); font-weight: var(--font-weight-medium);">
                                        <i class="fa fa-star" style="font-size: 10px;" aria-hidden="true"></i>
                                        {{ number_format($place->reviews_avg_rating ?? 0, 1) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $place->is_active ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $place->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                        <a href="{{ route('admin.places.show', $place) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            aria-label="View {{ $place->name }}">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route('admin.places.edit', $place) }}"
                                            class="btn btn-sm btn-outline-success"
                                            aria-label="Edit {{ $place->name }}">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                        <form action="{{ route('admin.places.destroy', $place) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this place?');"
                                            style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                aria-label="Delete {{ $place->name }}">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 48px; color: var(--color-gray-400);">
                                    <i class="fa fa-map-marker-alt" style="font-size: 32px; margin-bottom: 12px; display: block;" aria-hidden="true"></i>
                                    No places found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($places->hasPages())
                <nav style="padding-top: 24px; display: flex; justify-content: center;" aria-label="Places pagination">
                    {{ $places->links() }}
                </nav>
            @endif
        </div>
    </section>

    <style>
        @media (max-width: 767px) {
            form[style*="display: flex"] {
                flex-direction: column !important;
            }
            form[style*="display: flex"] .form-control,
            form[style*="display: flex"] .form-select,
            form[style*="display: flex"] .btn {
                width: 100% !important;
                min-width: 100% !important;
            }
        }
    </style>
@endsection
