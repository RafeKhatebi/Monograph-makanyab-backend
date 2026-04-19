@extends('layouts.admin')

@section('content')
    <div class="container">

        <h2>Manage Posts</h2>

        <a href="{{ route('admin.posts.create') }}" class="btn btn-success mb-3">
            + Add New Post
        </a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">

            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            @foreach ($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->title }}</td>
                    <td>
                        {{ $post->is_published ? 'Published' : 'Draft' }}
                    </td>
                    <td>

                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button onclick="return confirm('Delete?')" class="btn btn-danger btn-sm">
                                Delete
                            </button>
                        </form>

                    </td>
                </tr>
            @endforeach

        </table>

        {{ $posts->links() }}

    </div>
@endsection
