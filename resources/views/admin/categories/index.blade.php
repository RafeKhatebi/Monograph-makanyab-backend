@extends('layouts.admin')

@section('title', 'Manage Categories')
@section('page-title', 'Categories')

@section('content')
    <section class="card" aria-label="Categories Management">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">All Categories ({{ $categories->total() }})</h2>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus" aria-hidden="true"></i> Add New Category
            </a>
        </div>

        <div class="card-body">
            <div class="admin-table-wrap">
                <table class="table" aria-label="Categories list">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Parent</th>
                            <th scope="col">Places</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ $category->parent->name ?? '-' }}</td>
                                <td>{{ $category->places_count }}</td>
                                <td>
                                    <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="admin-actions">
                                        <a href="{{ route('admin.categories.show', $category) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            aria-label="View {{ $category->name }}">View</a>
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                            class="btn btn-sm btn-outline-success"
                                            aria-label="Edit {{ $category->name }}">Edit</a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this category?');"
                                            class="admin-action-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                aria-label="Delete {{ $category->name }}">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="admin-empty">
                                    No categories found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($categories->hasPages())
                <nav class="admin-pagination" aria-label="Categories pagination">
                    {{ $categories->links() }}
                </nav>
            @endif
        </div>
    </section>
@endsection
