@extends('layouts.admin')
@section('content')
    <div class="container">
        <h2>Create Post</h2>
        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.posts.form')
            <button class="btn btn-success">
                Save Post
            </button>
        </form>
    </div>
@endsection
