@extends('layouts.app')

@section('title', $post->title)

@section('content')

    <div class="page-head">
        <div class="container">
            <h1 class="page-title">{{ $post->title }}</h1>
        </div>
    </div>

    <div class="content-area" style="padding:50px 0;">
        <div class="container">

            @if ($post->image)
                <img src="{{ asset('storage/' . $post->image) }}"
                    style="width:100%;max-height:500px;object-fit:cover;margin-bottom:30px;">
            @endif

            <p class="text-muted">
                Published {{ $post->created_at->diffForHumans() }}
            </p>

            <div>
                {!! nl2br(e($post->content)) !!}
            </div>

            <hr>

            <a href="{{ route('posts.index') }}" class="btn btn-default">
                ← Back Posts
            </a>

        </div>
    </div>

@endsection
