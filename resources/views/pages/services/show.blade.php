@extends('layouts.app')

@section('title', $service->name)

@section('content')
    @php
        $serviceImages = $service->media->where('type', 'image')->sortBy('sort_order');
        $serviceCover = $serviceImages->firstWhere('is_cover', true) ?? $serviceImages->first();
        $addressParts = collect([
            $service->address ?? null,
            $service->district ?? null,
            $service->city ?? null,
            $service->province ?? null,
        ])->filter();
    @endphp

    <header class="detail-hero">
        <div class="container">
            <div class="detail-hero__content">
                <nav class="detail-hero__breadcrumbs" aria-label="{{ __('common.breadcrumb') }}">
                    <a href="{{ route('home') }}">{{ __('navigation.home') }}</a>
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    <a href="{{ route('services.index') }}">{{ __('services.title') }}</a>
                    @if ($service->category)
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        <a href="{{ route('service-categories.show', $service->category->slug) }}">{{ $service->category->name }}</a>
                    @endif
                </nav>

                @if ($service->category)
                    <span class="detail-pill">
                        <i class="fa {{ $service->category->icon_name ?? 'fa-briefcase' }}" aria-hidden="true"></i>
                        {{ $service->category->name }}
                    </span>
                @endif

                <h1 class="detail-hero__title">{{ $service->name }}</h1>

                <p class="detail-hero__text">
                    {{ $service->tagline ?: __('services.professional') }}
                </p>

                <div class="detail-hero__meta">
                    @include('components.rating-stars', ['rating' => $service->avg_rating])
                    <span>{{ number_format($service->avg_rating ?? 0, 1) }} · {{ $service->reviews->count() }} {{ __('places.reviews') }}</span>
                    @if ($service->is_verified)
                        <span><i class="fa fa-check-circle" aria-hidden="true"></i> {{ __('common.verified') }}</span>
                    @endif
                    @if ($service->status)
                        <span><i class="fa fa-circle" aria-hidden="true"></i> {{ __('common.status.' . $service->status) }}</span>
                    @endif
                    @if ($service->price_level)
                        <span><i class="fa fa-tag" aria-hidden="true"></i> {{ __('common.price.' . $service->price_level) }}</span>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <div class="detail-page">
        <div class="container">
            <div class="detail-grid">
                <main class="detail-main">
                    <section class="detail-card detail-media-card">
                        <img src="{{ $serviceCover ? asset('storage/' . $serviceCover->file_path) : asset('assets/img/demo/property-1.jpg') }}"
                            alt="{{ $service->name }}" class="detail-cover-image">
                        @if ($serviceImages->count() > 1)
                            <div class="detail-thumb-grid">
                                @foreach ($serviceImages as $image)
                                    <a href="{{ asset('storage/' . $image->file_path) }}" target="_blank" rel="noopener">
                                        <img src="{{ asset('storage/' . $image->file_path) }}" alt="{{ $service->name }}" loading="lazy">
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <section class="detail-card">
                        <h2 class="detail-section-title"><i class="fa fa-info-circle" aria-hidden="true"></i> {{ __('places.overview') }}</h2>
                        <p class="detail-copy">{{ $service->description ?: __('places.no_description') }}</p>
                    </section>

                    <section class="detail-card">
                        <h2 class="detail-section-title"><i class="fa fa-list-ul" aria-hidden="true"></i> {{ __('places.details') }}</h2>
                        <dl class="detail-facts">
                            <div>
                                <dt>{{ __('places.city_label') }}</dt>
                                <dd>{{ $service->city ?: __('common.none') }}</dd>
                            </div>
                            <div>
                                <dt>{{ __('places.category_label') }}</dt>
                                <dd>{{ $service->category->name ?? __('common.none') }}</dd>
                            </div>
                            <div>
                                <dt>{{ __('places.status_label') }}</dt>
                                <dd>{{ __('common.status.' . $service->status) }}</dd>
                            </div>
                            <div>
                                <dt>{{ __('places.price_label') }}</dt>
                                <dd>{{ __('common.price.' . ($service->price_level ?? 'medium')) }}</dd>
                            </div>
                            @if ($addressParts->isNotEmpty())
                                <div>
                                    <dt>{{ __('places.address') }}</dt>
                                    <dd>{{ $addressParts->join(', ') }}</dd>
                                </div>
                            @endif
                            @if ($service->country)
                                <div>
                                    <dt>{{ __('services.country') }}</dt>
                                    <dd>{{ $service->country }}</dd>
                                </div>
                            @endif
                            @if ($service->latitude && $service->longitude)
                                <div>
                                    <dt>{{ __('services.coordinates') }}</dt>
                                    <dd>{{ $service->latitude }}, {{ $service->longitude }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt>{{ __('common.dates.added_on') }}</dt>
                                <dd>{{ \App\Support\LocalizedDate::date($service->created_at) }}</dd>
                            </div>
                            <div>
                                <dt>{{ __('common.dates.updated_on') }}</dt>
                                <dd>{{ \App\Support\LocalizedDate::date($service->updated_at) }}</dd>
                            </div>
                        </dl>
                    </section>

                    <section class="detail-card">
                        <h2 class="detail-section-title"><i class="fa fa-comments" aria-hidden="true"></i> {{ __('places.reviews') }}</h2>
                        @forelse($service->reviews as $review)
                            @include('components.review-card', ['review' => $review])
                        @empty
                            <p class="detail-copy detail-copy--muted">{{ __('places.no_reviews') }}</p>
                        @endforelse

                        @auth
                            @if (! $hasReviewed)
                                <form method="POST" action="{{ route('services.reviews.store', $service) }}" class="detail-review-form">
                                    @csrf
                                    <label for="service-rating">{{ __('places.rating') }}</label>
                                    <select id="service-rating" name="rating" class="form-control" required>
                                        @for ($rating = 5; $rating >= 1; $rating--)
                                            <option value="{{ $rating }}">{{ __('places.stars', ['count' => $rating]) }}</option>
                                        @endfor
                                    </select>
                                    <label for="service-review-comment">{{ __('places.comment') }}</label>
                                    <textarea id="service-review-comment" name="comment" class="form-control" rows="4" maxlength="2000"
                                        placeholder="{{ __('places.comment_placeholder') }}"></textarea>
                                    <button type="submit" class="mk-button mk-button--primary mk-button--md">{{ __('places.submit_review') }}</button>
                                </form>
                            @else
                                <p class="detail-copy detail-copy--muted">{{ __('services.already_reviewed') }}</p>
                            @endif
                        @else
                            <p class="detail-copy"><a href="{{ route('login') }}">{{ __('auth.ui.login') }}</a> {{ __('services.login_review') }}</p>
                        @endauth
                    </section>
                </main>

                <aside class="detail-sidebar">
                    <section class="detail-card detail-contact-card">
                        <h2 class="detail-card__title"><i class="fa fa-address-book" aria-hidden="true"></i> {{ __('places.contact') }}</h2>

                        <div class="detail-contact-list">
                            @if ($service->phone_1)
                                <a href="tel:{{ $service->phone_1 }}">
                                    <span class="fa fa-phone" aria-hidden="true"></span>
                                    <span>
                                        <span class="detail-contact-label">{{ __('places.phone_primary') }}</span>
                                        <span class="detail-contact-value">{{ $service->phone_1 }}</span>
                                    </span>
                                </a>
                            @endif
                            @if ($service->phone_2)
                                <a href="tel:{{ $service->phone_2 }}">
                                    <span class="fa fa-phone" aria-hidden="true"></span>
                                    <span>
                                        <span class="detail-contact-label">{{ __('places.phone_secondary') }}</span>
                                        <span class="detail-contact-value">{{ $service->phone_2 }}</span>
                                    </span>
                                </a>
                            @endif
                            @if ($service->whatsapp)
                                <a href="https://wa.me/{{ $service->whatsapp }}" target="_blank" rel="noopener">
                                    <span class="fa fa-whatsapp" aria-hidden="true"></span>
                                    <span>
                                        <span class="detail-contact-label">{{ __('places.whatsapp') }}</span>
                                        <span class="detail-contact-value">{{ $service->whatsapp }}</span>
                                    </span>
                                </a>
                            @endif
                            @if ($service->email)
                                <a href="mailto:{{ $service->email }}">
                                    <span class="fa fa-envelope" aria-hidden="true"></span>
                                    <span>
                                        <span class="detail-contact-label">{{ __('places.email_label') }}</span>
                                        <span class="detail-contact-value">{{ $service->email }}</span>
                                    </span>
                                </a>
                            @endif
                            @if ($service->website)
                                <a href="{{ $service->website }}" target="_blank" rel="noopener">
                                    <span class="fa fa-globe" aria-hidden="true"></span>
                                    <span>
                                        <span class="detail-contact-label">{{ __('places.website') }}</span>
                                        <span class="detail-contact-value">{{ $service->website }}</span>
                                    </span>
                                </a>
                            @endif
                        </div>

                        @auth
                            <form method="POST" action="{{ route('services.favorite', $service) }}" class="detail-favorite-form">
                                @csrf
                                <button type="submit" class="mk-button mk-button--primary mk-button--md">
                                    <i class="fa {{ $isFavorited ? 'fa-star' : 'fa-star-o' }}" aria-hidden="true"></i>
                                    {{ $isFavorited ? __('services.saved') : __('services.save') }}
                                </button>
                            </form>
                        @endauth
                    </section>

                    <section class="detail-card">
                        <h2 class="detail-section-title"><i class="fa fa-user-circle-o" aria-hidden="true"></i> {{ __('services.provider') }}</h2>
                        <div class="detail-owner">
                            <span class="mk-avatar">{{ Str::upper(Str::substr($service->user->name ?? __('common.user'), 0, 1)) }}</span>
                            <div>
                                <strong class="detail-owner-name">{{ $service->user->name ?? __('services.service_provider') }}</strong>
                                <span>{{ __('services.service_provider') }}</span>
                            </div>
                        </div>
                    </section>
                </aside>
            </div>

            @if ($similar->isNotEmpty())
                <section class="related-section">
                    <h2 class="related-section__title">
                        <i class="fa fa-compass" aria-hidden="true"></i>
                        {{ __('services.related') }}
                    </h2>
                    <div class="row">
                        @foreach ($similar as $relatedService)
                            <x-service-card :service="$relatedService" />
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection
