@props(['item', 'type' => 'place', 'showFavorite' => true, 'dateLabel' => null, 'dateValue' => null])

@php
    $isPost = $type === 'post';
    $isService = $type === 'service';
    $route = match ($type) {
        'service' => route('services.show', $item),
        'post' => route('posts.show', $item->slug),
        default => route('places.show', $item),
    };
    $media = $isPost
        ? null
        : (($item->media ?? collect())->firstWhere('is_cover', true) ?? ($item->media ?? collect())->sortBy('sort_order')->first());
    $image = $isPost
        ? ($item->image ? asset('storage/' . $item->image) : asset('assets/img/demo/property-1.jpg'))
        : ($media && Storage::disk($media->disk ?: 'public')->exists($media->file_path)
            ? asset('storage/' . $media->file_path)
            : asset('assets/img/demo/property-1.jpg'));
    $category = $isPost ? __('content.posts.default_category') : ($item->category->name ?? null);
    $description = $isPost
        ? ($item->excerpt ?: strip_tags($item->content ?? ''))
        : ($item->tagline ?: $item->description ?: __('places.no_description'));
    $displayDate = $dateValue ?? ($isPost ? ($item->published_at ?? $item->created_at) : $item->created_at);
    $displayDateLabel = $dateLabel ?? ($isPost ? __('common.dates.published_on') : __('common.dates.added_on'));
@endphp

<article class="listing-card listing-card--{{ $type }}">
    <a class="listing-card__media" href="{{ $route }}">
        <img src="{{ $image }}" alt="{{ $item->title ?? $item->name }}" loading="lazy">
        @if (! $isPost && $item->is_verified)
            <span class="listing-card__badge listing-card__badge--verified">
                <i class="fa fa-check-circle" aria-hidden="true"></i> {{ __('common.verified') }}
            </span>
        @endif
        @if ($isService && $item->status)
            <span class="listing-card__badge listing-card__badge--status listing-card__badge--{{ $item->status }}">
                {{ __('common.status.' . $item->status) }}
            </span>
        @endif
    </a>

    @if ($type === 'place' && $showFavorite)
        @auth
            <div class="listing-card__favorite">
                <form method="POST" action="{{ route('favorites.toggle') }}">
                    @csrf
                    <input type="hidden" name="place_id" value="{{ $item->id }}">
                    <button type="submit" class="listing-card__favorite-button" aria-label="{{ __('common.toggle_favorite') }}">
                        <i class="fa {{ $item->is_favorited ? 'fa-heart text-danger' : 'fa-heart-o' }}" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
        @endauth
    @endif

    <div class="listing-card__body">
        @if ($category)
            <span class="listing-card__category">{{ $category }}</span>
        @endif

        @if ($displayDate)
            <p class="listing-card__date">
                {{ $displayDateLabel }} {{ \App\Support\LocalizedDate::date($displayDate) }}
            </p>
        @endif

        <h3 class="listing-card__title">
            <a href="{{ $route }}">{{ $item->title ?? $item->name }}</a>
        </h3>

        @unless ($isPost)
            <p class="listing-card__location">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                {{ $item->city }}@if ($item->district), {{ $item->district }}@endif
            </p>
        @endunless

        @if ($isService && isset($item->reviews_count) && $item->reviews_count > 0)
            <div class="mk-rating-row">
                @include('components.rating-stars', ['rating' => $item->reviews_avg_rating ?? 0])
                <span>({{ $item->reviews_count }})</span>
            </div>
        @endif

        <p class="listing-card__description">{{ Str::limit($description, 96) }}</p>

        <footer class="listing-card__footer">
            @if ($isPost)
                <span>{{ __('content.posts.published') }}</span>
            @else
                <span>{{ __('common.price.' . ($item->price_level ?? 'medium')) }}</span>
            @endif
            <a class="listing-card__button" href="{{ $route }}">
                {{ __('common.actions.read_more') }}
            </a>
        </footer>
    </div>
</article>
