<div class="col-sm-6 col-md-6 p0">
    <div class="box-two proerty-item">
        <div class="item-thumb">
            <a href="{{ route('places.show', $place) }}">
                @if ($place->media->first())
                    <img src="{{ asset('storage/' . $place->media->first()->file_path) }}" alt="{{ $place->name }}">
                @else
                    <img src="{{ asset('assets/img/demo/property-1.jpg') }}" alt="{{ $place->name }}">
                @endif
            </a>
            @if ($place->is_verified)
                <span class="property-seeker">
                    <b class="b-1 p-3" title="Verified"><i class="fa fa-check"></i></b>
                </span>
            @endif
        </div>
        <div class="item-entry overflow">
            <h5><a href="{{ route('places.show', $place) }}">{{ $place->name }}</a></h5>
            @if ($place->category)
                <span class="label label-success p-4" style="color:black; margin:3px">
                    {{ $place->category->name }}
                </span>
            @endif
            <div class="dot-hr"></div>
            <span class="pull-left">
                <i class="fa fa-map-marker"></i> {{ $place->city }}
            </span>
            <span class="proerty-price pull-right">
                @include('components.rating-stars', ['rating' => $place->avg_rating ?? 0])
            </span>
            <div class="property-icon" style="clear:both; padding-top:8px;">
                @include('components.status-badge', ['status' => $place->status])
                <span class="text-muted" style="margin-left:8px;">
                    {{ ucfirst(str_replace('_', ' ', $place->price_level)) }}
                </span>
                <button class="btn btn-sm btn-primary p-3"><a href="{{ route('places.show', $place) }}">View
                        Details</a></button>
                @auth
                    <form method="POST" action="{{ route('favorites.toggle') }}" style="display:inline;"
                        class="pull-right">
                        @csrf
                        <input type="hidden" name="place_id" value="{{ $place->id }}">
                        <button type="submit" class="btn btn-link" style="padding:0; color:inherit;"
                            title="Toggle favorite">
                            <i class="fa fa-heart-o"></i>
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</div>
