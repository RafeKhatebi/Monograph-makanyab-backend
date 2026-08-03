<div class="col-sm-6 col-md-4 p-2">
    <div class="place-card">
        <div class="place-card__media">
            <a href="{{ route('places.show', $place) }}">
                @if ($place->media->first())
                    <img src="{{ asset('storage/' . $place->media->first()->file_path) }}" alt="{{ $place->name }}">
                @else
                    <img src="{{ asset('assets/img/demo/property-1.jpg') }}" alt="{{ $place->name }}">
                @endif
            </a>

            @if ($place->is_verified)
                <span class="place-card__badge">
                    <i class="fa fa-check-circle" aria-hidden="true"></i> Verified
                </span>
            @endif

            @auth
                <div class="place-card__favorite">
                    <form method="POST" action="{{ route('favorites.toggle') }}">
                        @csrf
                        <input type="hidden" name="place_id" value="{{ $place->id }}">
                        <button type="submit" class="place-card__favorite-button" aria-label="Toggle favorite">
                            <i class="fa {{ $place->is_favorited ? 'fa-heart text-danger' : 'fa-heart-o' }}"></i>
                        </button>
                    </form>
                </div>
            @endauth
        </div>

        <div class="place-card__body">
            <div class="place-card__header">
                <h5 class="place-card__title">
                    <a href="{{ route('places.show', $place) }}">{{ $place->name }}</a>
                </h5>
                @if ($place->category)
                    <span class="place-card__category">{{ $place->category->name }}</span>
                @endif
            </div>

            <p class="place-card__location">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                {{ $place->city }}@if ($place->district)
                    , {{ $place->district }}
                @endif
            </p>

            @if ($place->tagline)
                <p class="place-card__description">
                    {{ Str::limit($place->tagline, 70) }}</p>
            @endif

            <div class="place-card__footer">
                <span class="place-card__price">
                    {{ ucfirst(str_replace('_', ' ', $place->price_level)) }}
                </span>
                <a href="{{ route('places.show', $place) }}" class="place-card__button">View</a>
            </div>
        </div>
    </div>
</div>
