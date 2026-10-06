@extends('layouts.app')

@php
    $isPost = $previewType === 'post';
    $status = $isPost ? $previewSubmission->submission_status : $previewSubmission->suggestion_status;
    $statusLabel = $status instanceof \App\Enums\SuggestionStatus
        ? $status->label()
        : __('suggestions.status.'.($status ?: 'draft'));
    $statusValue = $status instanceof \App\Enums\SuggestionStatus ? $status->value : (string) $status;
    $isPrivatePreview = ! in_array($statusValue, ['approved', 'published'], true)
        && (! $isPost || ! $previewSubmission->is_published);
    $media = $isPost ? null : ($previewSubmission->media->firstWhere('is_cover', true) ?? $previewSubmission->media->sortBy('sort_order')->first());
    $imagePath = $isPost ? $previewSubmission->image : $media?->file_path;
    $imageDisk = $isPost ? 'public' : ($media?->disk ?: 'public');
    $imageUrl = $imagePath && \Illuminate\Support\Facades\Storage::disk($imageDisk)->exists($imagePath)
        ? asset('storage/'.$imagePath)
        : asset('assets/img/placeholders/no-image.svg');
    $title = $previewSubmission->title ?? $previewSubmission->name;
    $description = $isPost
        ? ($previewSubmission->excerpt ?: strip_tags($previewSubmission->content ?? ''))
        : ($previewSubmission->tagline ?: $previewSubmission->description ?: __('places.no_description'));
@endphp

@section('title', __('suggestions.preview_title'))
@section('body-class', 'submission-page')

@section('content')
    <section class="submission-preview-page">
        <div class="container">
            <header class="submission-preview-page__header">
                <span class="submission-preview-page__eyebrow">{{ __('suggestions.types.'.$previewType) }} · {{ $statusLabel }}</span>
                <h1>{{ __('suggestions.preview_title') }}</h1>
                <p>{{ __('suggestions.preview_help') }}</p>
            </header>

            <div class="submission-preview-page__card">
                <article class="listing-card listing-card--{{ $previewType }}">
                    <div class="listing-card__media">
                        <img src="{{ $imageUrl }}" alt="{{ $title }}">
                        <span class="submission-preview-page__badge">{{ __('suggestions.preview_title') }}</span>
                    </div>
                    <div class="listing-card__body">
                        <span class="listing-card__category">{{ $isPost ? __('content.posts.default_category') : ($previewSubmission->category?->name ?? __('suggestions.types.'.$previewType)) }}</span>
                        <p class="listing-card__date">{{ __('common.dates.added_on') }} {{ \App\Support\LocalizedDate::date($previewSubmission->created_at) }}</p>
                        <h2 class="listing-card__title">{{ $title }}</h2>
                        @unless ($isPost)
                            @if ($previewSubmission->city || $previewSubmission->district)
                                <p class="listing-card__location">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                    <span>{{ $previewSubmission->city }}@if ($previewSubmission->district && $previewSubmission->district !== $previewSubmission->city), {{ $previewSubmission->district }}@endif</span>
                                </p>
                            @endif
                        @endunless
                        <p class="listing-card__description">{{ \Illuminate\Support\Str::limit($description, 96) }}</p>
                        <footer class="listing-card__footer">
                            <span>{{ $isPost ? $statusLabel : __('suggestions.types.'.$previewType) }}</span>
                            <button type="button" class="listing-card__button" disabled>{{ __('common.actions.read_more') }}</button>
                        </footer>
                    </div>
                </article>
            </div>

            @if ($isPrivatePreview)
                <p class="submission-preview-page__note"><i class="fa fa-lock" aria-hidden="true"></i> {{ __('suggestions.preview_private') }}</p>
            @endif

            <div class="submission-preview-page__actions">
                @if ($canEdit)
                    <a href="{{ route('add.edit', ['type' => $previewType, 'submission' => $previewSubmission->getKey()]) }}" class="mk-button mk-button--primary mk-button--md">
                        <i class="fa fa-edit" aria-hidden="true"></i> {{ __('profile.edit_submission') }}
                    </a>
                @endif
                <a href="{{ route('profile.index') }}#tab-submissions" class="mk-button mk-button--secondary mk-button--md">
                    {{ __('suggestions.my_submissions') }}
                </a>
            </div>
        </div>
    </section>
@endsection
