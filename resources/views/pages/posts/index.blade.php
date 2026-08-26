@extends('layouts.app')
@section('title', __('content.posts.title'))
@section('meta-description', __('content.posts.meta'))
@section('content')

    <div class="mk-hero mk-hero--compact">
        <div class="container">
            <h1 class="mk-hero__title">{{ __('content.posts.title') }}</h1>
            <p class="mk-hero__text">{{ __('content.posts.intro') }}
            </p>
        </div>
    </div>

    <div class="mk-page-section">
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
                <div class="mk-pagination-wrap">{{ $posts->links() }}</div>
            @else
                <div class="mk-ui-empty">
                    <div class="mk-empty-icon"><i class="fa fa-file-text-o" aria-hidden="true"></i></div>
                    <h3 class="mk-heading mk-heading--md">{{ __('content.posts.empty') }}</h3>
                    <p class="mk-text mk-text--muted">{{ __('content.posts.empty_text') }}</p>
                </div>
            @endif
        </div>
    </div>

@endsection
