@extends('layouts.app')

@section('title', $place->name)

@section('content')
    @php
        $placeImages = $place->media->where('type', 'image')->sortBy('sort_order');
        $placeCover = $placeImages->firstWhere('is_cover', true) ?? $placeImages->first();
    @endphp

    <div class="detail-hero">
        <div class="container">
            <div class="detail-hero__content">
                @if ($place->category)
                    <span class="detail-pill">{{ $place->category->name }}</span>
                @endif
                <h1 class="detail-hero__title">{{ $place->name }}</h1>
                <p class="detail-hero__text">{{ $place->tagline ?: $place->city }}</p>
                <div class="detail-hero__meta">
                    @include('components.rating-stars', ['rating' => $place->avg_rating])
                    <span>{{ $place->reviews->count() }} {{ __('places.reviews') }}</span>
                    @if ($place->is_verified)
                        <span><i class="fa fa-check-circle" aria-hidden="true"></i> {{ __('common.verified') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="detail-page">
        <div class="container">
            <div class="detail-grid">
                <main class="detail-main">
                    <section class="detail-card detail-media-card">
                        <img src="{{ $placeCover ? asset('storage/' . $placeCover->file_path) : asset('assets/img/demo/property-1.jpg') }}"
                            alt="{{ $place->name }}" class="detail-cover-image">
                        @if ($placeImages->count() > 1)
                            <div class="detail-thumb-grid">
                                @foreach ($placeImages as $image)
                                    <a href="{{ asset('storage/' . $image->file_path) }}" target="_blank" rel="noopener">
                                        <img src="{{ asset('storage/' . $image->file_path) }}" alt="{{ $place->name }}" loading="lazy">
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <section class="detail-card">
                        <h2 class="detail-section-title">{{ __('places.overview') }}</h2>
                        <p class="detail-copy">{{ $place->description ?: __('places.no_description') }}</p>
                    </section>

                    <section class="detail-card">
                        <h2 class="detail-section-title">{{ __('places.details') }}</h2>
                        <dl class="detail-facts">
                            <div><dt>{{ __('places.city_label') }}</dt><dd>{{ $place->city }}</dd></div>
                            <div><dt>{{ __('places.category_label') }}</dt><dd>{{ $place->category->name ?? __('common.none') }}</dd></div>
                            <div><dt>{{ __('places.status_label') }}</dt><dd>{{ __('common.status.' . $place->status) }}</dd></div>
                            <div><dt>{{ __('places.price_label') }}</dt><dd>{{ __('common.price.' . $place->price_level) }}</dd></div>
                            <div><dt>{{ __('common.dates.added_on') }}</dt><dd>{{ \App\Support\LocalizedDate::date($place->created_at) }}</dd></div>
                            <div><dt>{{ __('common.dates.updated_on') }}</dt><dd>{{ \App\Support\LocalizedDate::date($place->updated_at) }}</dd></div>
                            <div><dt>{{ __('places.address') }}</dt><dd>{{ collect([$place->address, $place->district, $place->city])->filter()->join(', ') }}</dd></div>
                            @if ($place->postal_code)
                                <div><dt>{{ __('places.postal_code') }}</dt><dd>{{ $place->postal_code }}</dd></div>
                            @endif
                        </dl>
                    </section>

                    <section class="detail-card">
                        <h2 class="detail-section-title">{{ __('places.opening_hours') }}</h2>
                        @if ($place->openingHours->count())
                            @php $days = __('common.days'); @endphp
                            <div class="detail-hours">
                                @foreach ($place->openingHours->sortBy('day_of_week') as $hour)
                                    <div>
                                        <span>{{ $days[$hour->day_of_week] ?? __('common.none') }}</span>
                                        <strong>
                                            {{ $hour->is_closed ? __('common.status.closed') : $hour->open_time . ' - ' . $hour->close_time }}
                                        </strong>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="detail-copy detail-copy--muted">{{ __('common.none') }}</p>
                        @endif
                    </section>

                    <section class="detail-card">
                        <h2 class="detail-section-title">{{ __('places.reviews') }}</h2>
                        @forelse($place->reviews as $review)
                            @include('components.review-card', ['review' => $review])
                        @empty
                            <p class="detail-copy detail-copy--muted">{{ __('places.no_reviews') }}</p>
                        @endforelse

                        @auth
                            @if (! $hasReviewed)
                                <form action="{{ route('places.reviews.store', $place) }}" method="POST" class="detail-review-form">
                                    @csrf
                                    <label for="place-rating">{{ __('places.rating') }}</label>
                                    <select id="place-rating" name="rating" class="form-control" required>
                                        @for ($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}">{{ __('places.stars', ['count' => $i]) }}</option>
                                        @endfor
                                    </select>
                                    <label for="place-comment">{{ __('places.comment') }}</label>
                                    <textarea id="place-comment" name="comment" class="form-control" rows="4" maxlength="2000"
                                        placeholder="{{ __('places.comment_placeholder') }}"></textarea>
                                    <button type="submit" class="mk-button mk-button--primary mk-button--md">{{ __('places.submit_review') }}</button>
                                </form>
                            @else
                                <p class="detail-copy detail-copy--muted">{{ __('places.already_reviewed') }}</p>
                            @endif
                        @else
                            <p class="detail-copy"><a href="{{ route('login') }}">{{ __('auth.ui.login') }}</a> {{ __('places.login_review') }}</p>
                        @endauth
                    </section>
                </main>

                <aside class="detail-sidebar">
                    <section class="detail-card">
                        <h2 class="detail-section-title">{{ __('places.contact') }}</h2>
                        <div class="detail-contact-list">
                            @if ($place->phone_1)
                                <a href="tel:{{ $place->phone_1 }}"><i class="fa fa-phone" aria-hidden="true"></i> {{ $place->phone_1 }}</a>
                            @endif
                            @if ($place->phone_2)
                                <a href="tel:{{ $place->phone_2 }}"><i class="fa fa-phone" aria-hidden="true"></i> {{ $place->phone_2 }}</a>
                            @endif
                            @if ($place->whatsapp)
                                <a href="https://wa.me/{{ $place->whatsapp }}" target="_blank" rel="noopener"><i class="fa fa-whatsapp" aria-hidden="true"></i> {{ $place->whatsapp }}</a>
                            @endif
                            @if ($place->website)
                                <a href="{{ $place->website }}" target="_blank" rel="noopener"><i class="fa fa-globe" aria-hidden="true"></i> {{ $place->website }}</a>
                            @endif
                        </div>
                    </section>

                    <section class="detail-card">
                        <h2 class="detail-section-title">{{ __('places.owner') }}</h2>
                        <div class="detail-owner">
                            <span class="mk-avatar">{{ Str::upper(Str::substr($place->user->name ?? __('common.user'), 0, 1)) }}</span>
                            <div>
                                <strong>{{ $place->user->name ?? __('places.place_owner') }}</strong>
                                <span>{{ __('places.place_owner') }}</span>
                            </div>
                        </div>
                        @auth
                            <form method="POST" action="{{ route('favorites.toggle') }}" class="detail-favorite-form">
                                @csrf
                                <input type="hidden" name="place_id" value="{{ $place->id }}">
                                <button type="submit" class="mk-button mk-button--secondary mk-button--md">
                                    <i class="fa {{ $isFavorited ? 'fa-star' : 'fa-star-o' }}" aria-hidden="true"></i>
                                    {{ $isFavorited ? __('places.favorite_remove') : __('places.favorite_add') }}
                                </button>
                            </form>
                        @endauth
                    </section>
                </aside>
            </div>

            @if (isset($similarPlaces) && $similarPlaces->count())
                <section class="related-section">
                    <h2 class="related-section__title">{{ __('places.similar') }}</h2>
                    <div class="row">
                        @foreach ($similarPlaces as $similar)
                            @include('components.place-card', ['place' => $similar])
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection
