@extends('layouts.app')

@section('title', $post->title)
@section('meta-description', $post->excerpt ?: Str::limit(strip_tags($post->content), 155))

@section('content')
    <div class="detail-hero">
        <div class="container">
            <div class="detail-hero__content">
                <span class="detail-pill">{{ __('content.posts.default_category') }}</span>
                <h1 class="detail-hero__title">{{ $post->title }}</h1>
                <div class="detail-hero__meta">
                    <span>{{ __('common.dates.published_on') }} {{ \App\Support\LocalizedDate::date($post->published_at ?? $post->created_at) }}</span>
                    @if ($post->user)
                        <span>{{ __('content.posts.by') }} {{ $post->user->name }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="detail-page">
        <div class="container">
            <div class="detail-grid">
                <main class="detail-main">
                    @if ($post->image)
                        <section class="detail-card detail-media-card">
                            <img src="{{ asset('storage/' . $post->image) }}" class="detail-cover-image" alt="{{ $post->title }}">
                        </section>
                    @endif

                    <article class="detail-card">
                        <div class="detail-copy detail-copy--article">
                            {!! nl2br(e($post->content)) !!}
                        </div>
                    </article>

                    <a href="{{ route('posts.index') }}" class="mk-button mk-button--secondary mk-button--md">
                        {{ __('content.posts.back') }}
                    </a>
                </main>

                <aside class="detail-sidebar">
                    <section class="detail-card">
                        <h2 class="detail-section-title">{{ __('content.posts.recent') }}</h2>
                        @if (isset($recentPosts) && $recentPosts->count())
                            <div class="detail-link-list">
                                @foreach ($recentPosts as $recent)
                                    <a href="{{ route('posts.show', $recent->slug) }}">
                                        <strong>{{ Str::limit($recent->title, 55) }}</strong>
                                        <span>{{ \App\Support\LocalizedDate::date($recent->published_at ?? $recent->created_at) }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="detail-copy detail-copy--muted">{{ __('content.posts.no_recent') }}</p>
                        @endif
                    </section>

                    <section class="detail-card">
                        <h2 class="detail-section-title">{{ __('content.posts.assistance') }}</h2>
                        <p class="detail-copy detail-copy--muted">{{ __('content.posts.assistance_text') }}</p>
                        <a href="{{ route('contact') }}" class="mk-button mk-button--primary mk-button--md">
                            {{ __('content.posts.contact_support') }}
                        </a>
                    </section>
                </aside>
            </div>
        </div>
    </div>
@endsection
