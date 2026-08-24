@php
    $placeMedia = $place->media->firstWhere('is_cover', true) ?? $place->media->sortBy('sort_order')->first();
    $placeImage = $placeMedia && Storage::disk($placeMedia->disk ?: 'public')->exists($placeMedia->file_path)
        ? asset('storage/' . $placeMedia->file_path)
        : asset('assets/img/demo/property-1.jpg');
@endphp

<div class="col-sm-6 col-md-4 p-2 place-card-col">
    <div class="place-card">
        <div class="place-card__media">
            <a href="{{ route('places.show', $place) }}">
                <img src="{{ $placeImage }}" alt="{{ $place->name }}">
            </a>

            @if ($place->is_verified)
                <span class="place-card__badge">
                    <i class="fa fa-check-circle" aria-hidden="true"></i> {{ __('common.verified') }}
                </span>
            @endif

            @auth
                <div class="place-card__favorite">
                    <form method="POST" action="{{ route('favorites.toggle') }}">
                        @csrf
                        <input type="hidden" name="place_id" value="{{ $place->id }}">
                        <button type="submit" class="place-card__favorite-button" aria-label="{{ __('common.toggle_favorite') }}">
                            <i class="fa {{ $place->is_favorited ? 'fa-heart text-danger' : 'fa-heart-o' }}"></i>
                        </button>
                    </form>
                </div>
            @endauth
        </div>

        <div class="place-card__body">
            <div class="place-card__header">
                @if ($place->category)
                    <span class="place-card__category">{{ $place->category->name }}</span>
                @endif
                <h5 class="place-card__title">
                    <a href="{{ route('places.show', $place) }}">{{ $place->name }}</a>
                </h5>
            </div>

            <p class="place-card__location">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                {{ $place->city }}@if ($place->district)
                    , {{ $place->district }}
                @endif
            </p>

            <p class="place-card__description">
                {{ Str::limit($place->tagline ?: $place->description ?: __('places.no_description'), 120) }}
            </p>

            <div class="place-card__footer">
                <span class="place-card__price">
                    {{ __("common.price.{$place->price_level}") }}
                </span>
                <a href="{{ route('places.show', $place) }}" class="place-card__button">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                    {{ __('common.actions.view') }}
                </a>
            </div>
        </div>
    </div>
</div>
