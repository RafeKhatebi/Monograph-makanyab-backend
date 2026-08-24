@extends('layouts.app')
@section('title', __('content.posts.title'))
@section('meta-description', __('content.posts.meta'))
@section('content')

    <div style="background:linear-gradient(135deg,#064e3b,#10B981);padding:40px 0;">
        <div class="container">
            <h1 style="font-size:30px;font-weight:800;color:#fff;margin:0 0 6px;">{{ __('content.posts.title') }}</h1>
            <p style="color:rgba(255,255,255,.8);margin:0;font-size:15px;">{{ __('content.posts.intro') }}
            </p>
        </div>
    </div>

    <div style="background:#F8FAFC;padding:50px 0 70px;">
        <div class="container">
            @if (isset($posts) && $posts->count())
                <div class="row">
                    @foreach ($posts as $post)
                        <div class="col-md-4 col-sm-6 home-card-col">
                            <article class="home-post-card">
                                <a href="{{ route('posts.show', $post->slug) }}" class="home-post-card__media">
                                    <img src="{{ $post->image ? asset('storage/' . $post->image) : asset('assets/img/demo/property-1.jpg') }}"
                                        alt="{{ $post->title }}" loading="lazy">
                                </a>
                                <div class="home-post-card__body">
                                    <div class="home-post-card__meta">
                                        <span class="home-post-card__date">
                                            {{ $post->category->name ?? __('content.posts.default_category') }}
                                        </span>
                                        <span class="home-post-card__date">
                                            {{ optional($post->published_at ?? $post->created_at)->translatedFormat(__('home.date_format')) }}
                                        </span>
                                    </div>
                                    <h3 class="home-post-card__title">
                                        <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                                    </h3>
                                    <p class="home-post-card__text">
                                        {{ Str::limit($post->excerpt ?: strip_tags($post->content), 120) }}
                                    </p>
                                    <a href="{{ route('posts.show', $post->slug) }}" class="home-post-card__link">
                                        {{ __('content.posts.read_more') }}
                                    </a>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
                <div style="text-align:center;margin-top:20px;">{{ $posts->links() }}</div>
            @else
                <div style="text-align:center;padding:80px 0;">
                    <div class="mk-empty-icon"><i class="fa fa-file-text-o" aria-hidden="true"></i></div>
                    <h3 style="font-size:22px;font-weight:700;color:#111827;margin-bottom:12px;">{{ __('content.posts.empty') }}</h3>
                    <p style="color:#6B7280;">{{ __('content.posts.empty_text') }}</p>
                </div>
            @endif
        </div>
    </div>

@endsection
