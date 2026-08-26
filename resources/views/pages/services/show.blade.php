@extends('layouts.app')

@section('title', $service->name)

@section('content')
    <div class="page-head">
        <div class="container">
            <h1 class="page-title">{{ $service->name }}</h1>
        </div>
    </div>

    <div class="content-area mk-page-section">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="mk-card service-media-card">
                        @php
                            $serviceImages = $service->media->where('type', 'image')->sortBy('sort_order');
                            $serviceCover = $serviceImages->firstWhere('is_cover', true) ?? $serviceImages->first();
                        @endphp
                        <img src="{{ $serviceCover ? asset('storage/' . $serviceCover->file_path) : asset('assets/img/demo/property-1.jpg') }}"
                            alt="{{ $service->name }}" class="service-cover-image">
                        @if ($serviceImages->count() > 1)
                            <div class="service-thumb-grid">
                                @foreach ($serviceImages as $image)
                                    <a href="{{ asset('storage/' . $image->file_path) }}" target="_blank" rel="noopener">
                                        <img src="{{ asset('storage/' . $image->file_path) }}" alt="{{ $service->name }} image {{ $loop->iteration }}"
                                            loading="lazy">
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="mk-card service-detail-card">
                        <div class="service-detail-meta">
                            @if ($service->category)
                                <span class="badge badge-success">
                                    {{ $service->category->name }}
                                </span>
                            @endif
                            @if ($service->is_verified)
                                <span class="service-verified">✓ {{ __('common.verified') }}</span>
                            @endif
                            @auth
                                <form method="POST" action="{{ route('services.favorite', $service) }}" class="service-favorite-form">
                                    @csrf
                                    <button type="submit" class="btn btn-default btn-sm">
                                        <i class="fa {{ $isFavorited ? 'fa-star' : 'fa-star-o' }}"></i>
                                        {{ $isFavorited ? __('services.saved') : __('services.save') }}
                                    </button>
                                </form>
                            @endauth
                        </div>

                        <h2 class="service-title">{{ $service->name }}</h2>
                        <div class="service-rating-row">
                            @include('components.rating-stars', ['rating' => $service->avg_rating])
                            <small>({{ $service->reviews->count() }} {{ __('places.reviews') }})</small>
                        </div>
                        <p class="service-copy">
                            {{ $service->tagline ?? __('services.professional') }}
                        </p>

                        <div class="service-facts">
                            <div class="service-fact">
                                <strong>{{ __('places.city_label') }}:</strong> {{ $service->city }}
                            </div>
                            <div class="service-fact">
                                <strong>{{ __('places.status_label') }}:</strong> {{ ucfirst($service->status) }}
                            </div>
                            <div class="service-fact">
                                <strong>{{ __('places.price_label') }}:</strong> {{ ucfirst($service->price_level) }}
                            </div>
                        </div>

                        <div class="service-description">
                            {!! nl2br(e($service->description)) !!}
                        </div>

                        <div class="service-contact-list">
                            <div class="service-contact-item">
                                <strong>{{ __('places.phone') }} 1:</strong> {{ $service->phone_1 }}
                            </div>
                            @if ($service->phone_2)
                                <div class="service-contact-item">
                                    <strong>{{ __('places.phone') }} 2:</strong> {{ $service->phone_2 }}
                                </div>
                            @endif
                            @if ($service->whatsapp)
                                <div class="service-contact-item">
                                    <strong>WhatsApp:</strong> {{ $service->whatsapp }}
                                </div>
                            @endif
                            @if ($service->website)
                                <div class="service-contact-item">
                                    <strong>Website:</strong>
                                    <a href="{{ $service->website }}" target="_blank"
                                        rel="noopener noreferrer">
                                        {{ $service->website }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <section class="mk-card service-review-card" aria-labelledby="service-reviews-heading">
                        <h2 id="service-reviews-heading" class="service-detail-title">{{ __('places.reviews') }}</h2>
                        @forelse ($service->reviews as $review)
                            @include('components.review-card', ['review' => $review])
                        @empty
                            <p class="text-muted">{{ __('places.no_reviews') }}</p>
                        @endforelse

                        @auth
                            @if (! $hasReviewed)
                                <form method="POST" action="{{ route('services.reviews.store', $service) }}" class="service-review-form">
                                    @csrf
                                    <label for="service-rating">{{ __('places.rating') }}</label>
                                    <select id="service-rating" name="rating" class="form-control" required>
                                        @for ($rating = 5; $rating >= 1; $rating--)
                                            <option value="{{ $rating }}">{{ $rating }} star{{ $rating === 1 ? '' : 's' }}</option>
                                        @endfor
                                    </select>
                                    <label for="service-review-comment">{{ __('places.comment') }}</label>
                                    <textarea id="service-review-comment" name="comment" class="form-control" rows="4" maxlength="2000"></textarea>
                                    <button type="submit" class="btn btn-primary">{{ __('places.submit_review') }}</button>
                                </form>
                            @else
                                <p class="text-muted service-form-note">You have already reviewed this service. Edit it from your profile after approval.</p>
                            @endif
                        @else
                            <p><a href="{{ route('login') }}">Log in</a> to review this service.</p>
                        @endauth
                    </section>
                </div>

                <div class="col-md-4">
                    <div class="mk-card service-detail-card">
                        <h3 class="service-detail-title">{{ __('services.location_details') }}</h3>
                        <p class="service-location-text">
                            <strong>{{ __('places.address') }}:</strong> {{ $service->address }}, {{ $service->city }},
                            {{ $service->district }}
                        </p>
                        @if ($service->latitude && $service->longitude)
                            <p class="service-location-text"><strong>{{ __('services.coordinates') }}:</strong>
                                {{ $service->latitude }}, {{ $service->longitude }}</p>
                        @endif
                        <p class="service-location-text"><strong>{{ __('services.province') }}:</strong> {{ $service->province }}
                        </p>
                        <p class="service-location-text"><strong>{{ __('services.country') }}:</strong> {{ $service->country }}</p>
                    </div>
                </div>
            </div>

            @if ($similar->isNotEmpty())
                <section class="related-section" aria-labelledby="related-services-heading">
                    <h2 id="related-services-heading" class="related-section__title">{{ __('services.related') }}</h2>
                    <div class="row">
                        @foreach ($similar as $relatedService)
                            <div class="col-sm-6 col-md-3 service-grid-col">
                                <x-service-card :service="$relatedService" />
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection
