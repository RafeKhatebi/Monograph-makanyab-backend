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
    $previewImages = $isPost ? collect() : $previewSubmission->media->where('type', 'image')->sortBy('sort_order')
        ->filter(fn ($image) => \Illuminate\Support\Facades\Storage::disk($image->disk ?: 'public')->exists($image->file_path));
    $media = $previewImages->firstWhere('is_cover', true) ?? $previewImages->first();
    $imagePath = $isPost ? $previewSubmission->image : $media?->file_path;
    $imageDisk = $isPost ? 'public' : ($media?->disk ?: 'public');
    $imageUrl = $imagePath && \Illuminate\Support\Facades\Storage::disk($imageDisk)->exists($imagePath)
        ? asset('storage/'.$imagePath)
        : asset('assets/img/placeholders/no-image.svg');
    $title = $previewSubmission->title ?? $previewSubmission->name;
    $description = $isPost
        ? ($previewSubmission->excerpt ?: strip_tags($previewSubmission->content ?? ''))
        : ($previewSubmission->tagline ?: $previewSubmission->description ?: __('places.no_description'));
    $displayProvince = $isPost ? null : \App\Support\LocalizedAfghanistanLocation::province($previewSubmission->province);
    $displayDistrict = $isPost ? null : \App\Support\LocalizedAfghanistanLocation::district($previewSubmission->district, $previewSubmission->province);
    $displayCity = $isPost ? null : \App\Support\LocalizedAfghanistanLocation::district($previewSubmission->city, $previewSubmission->province);
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

            @if (in_array($statusValue, ['changes_requested', 'rejected'], true) && $previewSubmission->admin_note)
                <div class="submission-feedback submission-feedback--error">
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                    <div><strong>{{ __('suggestions.admin_feedback') }}</strong><p>{{ $previewSubmission->admin_note }}</p></div>
                </div>
            @endif

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
                            @if ($displayCity || $displayDistrict)
                                <p class="listing-card__location">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                    <span>{{ $displayCity ?: $displayDistrict }}@if ($displayDistrict && $displayDistrict !== $displayCity && $displayCity), {{ $displayDistrict }}@endif</span>
                                </p>
                            @endif
                        @endunless
                        <p class="listing-card__description">{{ \Illuminate\Support\Str::limit($description, 96) }}</p>
                        <footer class="listing-card__footer">
                            <span>{{ $isPost ? $statusLabel : ($previewSubmission->price_level ? __('common.price.'.$previewSubmission->price_level->value) : __('suggestions.types.'.$previewType)) }}</span>
                            <a href="#submission-preview-details" class="listing-card__button">{{ __('common.actions.read_more') }}</a>
                        </footer>
                    </div>
                </article>
            </div>

            <div id="submission-preview-details" class="submission-preview-page__details detail-grid {{ $isPost ? 'submission-preview-page__details--post' : '' }}">
                <main class="detail-main">
                    <section class="detail-card detail-media-card">
                        <img src="{{ $imageUrl }}" alt="{{ $title }}" class="detail-cover-image">
                        @if ($previewImages->count() > 1)
                            <div class="detail-thumb-grid">
                                @foreach ($previewImages as $image)
                                    <a href="{{ asset('storage/'.$image->file_path) }}" target="_blank" rel="noopener">
                                        <img src="{{ asset('storage/'.$image->file_path) }}" alt="{{ $title }}" loading="lazy">
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <section class="detail-card">
                        <h2 class="detail-section-title">{{ $isPost ? __('suggestions.content') : __('places.overview') }}</h2>
                        @if ($isPost)
                            <div class="detail-copy detail-copy--article" dir="auto">{!! $previewHtml !!}</div>
                        @else
                            <p class="detail-copy" dir="auto">{{ $previewSubmission->description ?: __('places.no_description') }}</p>
                        @endif
                    </section>

                    @unless ($isPost)
                        <section class="detail-card">
                            <h2 class="detail-section-title">{{ __('places.details') }}</h2>
                            <dl class="detail-facts">
                                <div><dt>{{ __('places.category_label') }}</dt><dd>{{ $previewSubmission->category?->name ?? __('common.none') }}</dd></div>
                                <div><dt>{{ __('suggestions.province') }}</dt><dd>{{ $displayProvince ?: __('common.none') }}</dd></div>
                                <div><dt>{{ __('suggestions.district') }}</dt><dd>{{ $displayDistrict ?: __('common.none') }}</dd></div>
                                @if ($previewSubmission->address)
                                    <div><dt>{{ __('places.address') }}</dt><dd>{{ $previewSubmission->address }}</dd></div>
                                @endif
                                @if ($previewSubmission->price_level)
                                    <div><dt>{{ __('places.price_label') }}</dt><dd>{{ __('common.price.'.$previewSubmission->price_level->value) }}</dd></div>
                                @endif
                            </dl>
                        </section>

                        <section class="detail-card">
                            <h2 class="detail-section-title">{{ __('places.reviews') }}</h2>
                            @if ($publishedItem)
                                @forelse ($publishedItem->reviews as $review)
                                    @include('components.review-card', ['review' => $review])
                                @empty
                                    <p class="detail-copy detail-copy--muted">{{ __('places.no_reviews') }}</p>
                                @endforelse
                            @else
                                <p class="detail-copy detail-copy--muted">{{ __('suggestions.preview_reviews_after_publication') }}</p>
                            @endif
                        </section>
                    @endunless
                </main>

                @unless ($isPost)
                    <aside class="detail-sidebar">
                        <section class="detail-card detail-contact-card">
                            <h2 class="detail-card__title">{{ __('places.contact') }}</h2>
                            <div class="detail-contact-list">
                                @if ($previewSubmission->phone_1)
                                    <a href="tel:{{ $previewSubmission->phone_1 }}"><i class="fa fa-phone" aria-hidden="true"></i> {{ $previewSubmission->phone_1 }}</a>
                                @endif
                                @if ($previewSubmission->whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/\D+/', '', $previewSubmission->whatsapp) }}" target="_blank" rel="noopener"><i class="fa fa-whatsapp" aria-hidden="true"></i> {{ $previewSubmission->whatsapp }}</a>
                                @endif
                                @if ($previewSubmission->website)
                                    <a href="{{ $previewSubmission->website }}" target="_blank" rel="noopener"><i class="fa fa-globe" aria-hidden="true"></i> {{ $previewSubmission->website }}</a>
                                @endif
                            </div>
                        </section>
                    </aside>
                @endunless
            </div>

            @if ($isPrivatePreview)
                <p class="submission-preview-page__note"><i class="fa fa-lock" aria-hidden="true"></i> {{ __('suggestions.preview_private') }}</p>
            @endif

            <div class="submission-preview-page__actions">
                @if ($publishedItem)
                    <a href="{{ route($previewType === 'place' ? 'places.show' : 'services.show', $publishedItem) }}" class="mk-button mk-button--primary mk-button--md">
                        {{ __('suggestions.view_published') }}
                    </a>
                @endif
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
