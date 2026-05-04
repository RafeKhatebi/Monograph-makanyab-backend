@extends('layouts.admin')

@section('title', 'Manage Service Categories')
@section('page-title', 'Service Categories')

@section('content')
    <div class="bg-light rounded h-100 p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">All Service Categories ({{ $categories->total() }})</h6>
            <a href="{{ route('admin.service-categories.create') }}" class="btn btn-primary btn-sm">+ Add New Service Category</a>
        </div>

        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-dark">
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Parent</th>
                        <th>Services</th>
                        <th>Status</th>
                        <th>Actions</th>
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
                                <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.service-categories.show', $category) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="{{ route('admin.service-categories.edit', $category) }}" class="btn btn-sm btn-outline-success">Edit</a>
                                    <form action="{{ route('admin.service-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No service categories found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="pt-4">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection
