@extends('layouts.admin')

@section('title', 'Manage Posts')
@section('page-title', 'Posts')

@section('content')
    <section class="card" aria-label="Posts Management">
        <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
            <h2 style="margin: 0; font-weight: 600; font-size: var(--font-size-base);">All Posts ({{ $posts->total() }})</h2>
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus" aria-hidden="true"></i> Add New Post
            </a>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.posts.index') }}" role="search" aria-label="Filter posts" style="display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 24px;">
                <div style="flex: 1; min-width: 200px;">
                    <label for="search" class="sr-only">Search posts</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search posts..."
                        class="form-control">
                </div>
                <div>
                    <label for="is_published" class="sr-only">Filter by status</label>
                    <select id="is_published" name="is_published" class="form-select" style="width: auto; min-width: 140px;">
                        <option value="">All Status</option>
                        <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>Published</option>
                        <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter" aria-hidden="true"></i> Filter
                </button>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">Clear</a>
            </form>

            <div style="overflow-x: auto;">
                <table class="table" aria-label="Posts list">
                    <thead>
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Author</th>
                            <th scope="col">Status</th>
                            <th scope="col">Published At</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td>{{ Str::limit($post->title, 50) }}</td>
                                <td>{{ $post->user->name ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $post->is_published ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $post->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </td>
                                <td>{{ $post->published_at ? $post->published_at->format('M d, Y') : '-' }}</td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="{{ route('admin.posts.edit', $post) }}"
                                            class="btn btn-sm btn-outline-success"
                                            aria-label="Edit {{ Str::limit($post->title, 30) }}">Edit</a>
                                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                                            onsubmit="return confirm('Delete this post?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                aria-label="Delete {{ Str::limit($post->title, 30) }}">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 48px; color: var(--color-gray-400);">
                                    No posts found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($posts->hasPages())
                <nav style="padding-top: 24px; display: flex; justify-content: center;" aria-label="Posts pagination">
                    {{ $posts->links() }}
                </nav>
            @endif
        </div>
    </section>
@endsection
