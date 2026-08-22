@props(['service'])

@php
    $cover = $service->media->firstWhere('is_cover', true) ?? $service->media->sortBy('sort_order')->first();
    $coverImage = $cover && Storage::disk($cover->disk ?: 'public')->exists($cover->file_path)
        ? asset('storage/' . $cover->file_path)
        : asset('assets/img/demo/property-1.jpg');
    $statusLabels = [
        'open' => 'Open',
        'closed' => 'Closed',
        'temporarily_closed' => 'Temporarily closed',
    ];
@endphp

<article class="service-card">
    <a class="service-card__media" href="{{ route('services.show', $service) }}">
        <img src="{{ $coverImage }}"
            alt="{{ $service->name }}" loading="lazy">
        @if ($service->is_verified)
            <span class="service-card__badge service-card__badge--verified">
                <i class="fa fa-check-circle" aria-hidden="true"></i> Verified
            </span>
        @endif
        <span class="service-card__badge service-card__badge--status service-card__badge--{{ $service->status }}">
            {{ $statusLabels[$service->status] ?? ucfirst($service->status) }}
        </span>
    </a>
    <div class="service-card__body">
        @if ($service->category)
            <a class="service-card__category" href="{{ route('service-categories.show', $service->category->slug) }}">
                {{ $service->category->name }}
            </a>
        @endif
        <h3 class="service-card__title">
            <a href="{{ route('services.show', $service) }}">{{ $service->name }}</a>
        </h3>
        <p class="service-card__location">
            <i class="fa fa-map-marker" aria-hidden="true"></i>
            {{ $service->city }}@if ($service->district), {{ $service->district }}@endif
        </p>
        @if (isset($service->reviews_count) && $service->reviews_count > 0)
            <div class="mk-rating-row">
                @include('components.rating-stars', ['rating' => $service->reviews_avg_rating ?? 0])
                <span>({{ $service->reviews_count }})</span>
            </div>
        @endif
        <p class="service-card__description">
            {{ Str::limit($service->tagline ?: $service->description, 90) }}
        </p>
        <footer class="service-card__footer">
            <span>{{ ucfirst($service->price_level ?? 'medium') }}</span>
            <a class="service-card__button" href="{{ route('services.show', $service) }}">View service</a>
        </footer>
    </div>
</article>
