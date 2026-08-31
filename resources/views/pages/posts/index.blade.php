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
                            <x-listing-card :item="$post" type="post" />
                        </div>
                    @endforeach
                </div>
                @if ($posts->hasMorePages())
                    <div class="listing-load-more">
                        <a href="{{ $posts->appends(request()->query())->nextPageUrl() }}" class="mk-button mk-button--primary mk-button--lg">
                            {{ __('content.posts.load_more') }}
                        </a>
                    </div>
                @endif
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
