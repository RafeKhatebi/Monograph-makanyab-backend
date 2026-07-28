@extends('layouts.admin')

@section('title', 'Manage Service Categories')
@section('page-title', 'Service Categories')

@section('content')
    <section class="card" aria-label="Service Categories Management">
        <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
            <h2 style="margin: 0; font-weight: 600; font-size: var(--font-size-base);">All Service Categories ({{ $categories->total() }})</h2>
            <a href="{{ route('admin.service-categories.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus" aria-hidden="true"></i> Add New Service Category
            </a>
        </div>

        <div class="card-body">
            <div style="overflow-x: auto;">
                <table class="table" aria-label="Service Categories list">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Parent</th>
                            <th scope="col">Services</th>
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
                                <td>{{ $category->services_count }}</td>
                                <td>
                                    <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="{{ route('admin.service-categories.show', $category) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            aria-label="View {{ $category->name }}">View</a>
                                        <a href="{{ route('admin.service-categories.edit', $category) }}"
                                            class="btn btn-sm btn-outline-success"
                                            aria-label="Edit {{ $category->name }}">Edit</a>
                                        <form action="{{ route('admin.service-categories.destroy', $category) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this service category?');">
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
                                <td colspan="6" style="text-align: center; padding: 48px; color: var(--color-gray-400);">
                                    No service categories found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($categories->hasPages())
                <nav style="padding-top: 24px; display: flex; justify-content: center;" aria-label="Service Categories pagination">
                    {{ $categories->links() }}
                </nav>
            @endif
        </div>
    </section>
@endsection
