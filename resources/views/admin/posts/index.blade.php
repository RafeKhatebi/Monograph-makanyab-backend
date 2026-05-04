@extends('layouts.admin')

@section('title', 'Manage Posts')
@section('page-title', 'Posts')

@section('content')
    <div class="bg-light rounded h-100 p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">All Posts ({{ $posts->total() }})</h6>
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">+ Add New Post</a>
        </div>

        <form method="GET" action="{{ route('admin.posts.index') }}" class="row g-2 mb-4">
            <div class="col-md-6">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..."
                    class="form-control">
            </div>
            <div class="col-md-3">
                <select name="is_published" class="form-select">
                    <option value="">All Status</option>
                    <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>Published</option>
                    <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-dark">
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Published At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td>{{ Str::limit($post->title, 50) }}</td>
                            <td>{{ $post->user->name ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $post->is_published ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $post->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td>{{ $post->published_at ? $post->published_at->format('M d, Y') : '-' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.posts.edit', $post) }}"
                                        class="btn btn-sm btn-outline-success">Edit</a>
                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                                        onsubmit="return confirm('Delete this post?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No posts found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($posts->hasPages())
            <div class="pt-4">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
