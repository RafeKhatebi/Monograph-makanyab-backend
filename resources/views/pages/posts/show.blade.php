@extends('layouts.app')

@section('title', $post->title)
@section('meta-description', $post->excerpt ?: Str::limit(strip_tags($post->content), 155))

@section('content')
    @php
        $postImage = $post->image && Storage::disk('public')->exists($post->image)
            ? asset('storage/' . $post->image)
            : asset('assets/img/placeholders/no-image.svg');
    @endphp

    <div class="detail-hero detail-hero--post">
        <div class="container">
            <div class="detail-hero__content">
                <span class="detail-pill">{{ __('content.posts.default_category') }}</span>
                <h1 class="detail-hero__title" dir="auto">{{ $post->title }}</h1>
                <div class="detail-hero__meta">
                    <span>{{ __('common.dates.published_on') }} {{ \App\Support\LocalizedDate::date($post->published_at ?? $post->created_at) }}</span>
                    @if ($post->user)
                        <span>{{ __('content.posts.by') }} {{ $post->user->name }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="detail-page detail-page--post">
        <div class="container">
            <div class="detail-grid">
                <main class="detail-main">
                    <section class="detail-card detail-media-card">
                        <img src="{{ $postImage }}" class="detail-cover-image" alt="{{ $post->title }}">
                    </section>

                    <article class="detail-card">
                        <div class="detail-copy detail-copy--article" dir="auto">
                            {!! nl2br(e($post->content)) !!}
                        </div>
                    </article>

                    @auth
                        <form method="POST" action="{{ route('favorites.toggle') }}" class="detail-action-form">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <button type="submit" class="mk-button mk-button--secondary mk-button--md">
                                <i class="fa {{ $post->isFavoritedBy(auth()->user()) ? 'fa-bookmark' : 'fa-bookmark-o' }}" aria-hidden="true"></i>
                                {{ $post->isFavoritedBy(auth()->user()) ? __('favorites.remove_post') : __('favorites.save_post') }}
                            </button>
                        </form>
                    @endauth

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
                                        <strong dir="auto">{{ Str::limit($recent->title, 55) }}</strong>
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
