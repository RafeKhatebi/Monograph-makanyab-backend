@extends('layouts.admin')

@section('content')
    <div class="container">

        <h2>Edit Post</h2>

        <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">

            @csrf
            @method('PUT')

            @include('admin.posts.form')

            <button class="btn btn-primary">
                Update Post
            </button>

        </form>

    </div>
@endsection
