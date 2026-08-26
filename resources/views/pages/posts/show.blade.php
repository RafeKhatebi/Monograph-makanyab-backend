@extends('layouts.app')

@section('title', $post->title)
@section('meta-description', $post->excerpt ?: Str::limit(strip_tags($post->content), 155))

@section('content')
    <div class="content-area mk-page-section">
        <div class="container">
            <div class="row content-article-header">
                <div class="col-md-12">
                    <div class="mk-card">
                        <h1 class="content-article-title">{{ $post->title }}
                        </h1>
                        <p class="content-article-meta">
                            {{ __('content.posts.published') }} {{ $post->published_at?->format('F d, Y') }}
                            @if ($post->user)
                                {{ __('content.posts.by') }} {{ $post->user->name }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="mk-card content-article-card service-media-card">
                        @if ($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}"
                                class="content-article-image" alt="{{ $post->title }}">
                        @endif
                    </div>

                    <div class="mk-card content-article-card">
                        <div class="content-article-body">
                            {!! nl2br(e($post->content)) !!}
                        </div>
                    </div>

                    <a href="{{ route('posts.index') }}" class="mk-btn mk-btn-secondary">
                        ← {{ __('content.posts.back') }}
                    </a>
                </div>

                <div class="col-md-4">
                    <div class="mk-card content-sidebar-card">
                        <h3 class="content-sidebar-title">{{ __('content.posts.recent') }}</h3>
                        @if (isset($recentPosts) && $recentPosts->count())
                            @foreach ($recentPosts as $recent)
                                <a href="{{ route('posts.show', $recent->slug) }}" class="content-sidebar-link">
                                    <strong>{{ Str::limit($recent->title, 55) }}</strong>
                                    <div class="content-sidebar-date">
                                        {{ $recent->published_at?->format('M d, Y') }}</div>
                                </a>
                            @endforeach
                        @else
                            <p class="mk-text mk-text--muted">{{ __('content.posts.no_recent') }}</p>
                        @endif
                    </div>
                    <div class="mk-card content-sidebar-card">
                        <h3 class="content-sidebar-title">{{ __('content.posts.assistance') }}
                        </h3>
                        <p class="mk-text mk-text--muted">{{ __('content.posts.assistance_text') }}</p>
                        <a href="{{ route('contact') }}" class="mk-btn mk-btn-primary">{{ __('content.posts.contact_support') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
