<div class="col-sm-6 col-md-4 p-2">
    <div class="box-two proerty-item shadow-sm border rounded bg-white">
        <!-- Image Section -->
        <div class="item-thumb position-relative">
            <a href="{{ route('places.show', $place) }}">
                @if ($place->media->first())
                    <img src="{{ asset('storage/' . $place->media->first()->file_path) }}" class="img-responsive w-100"
                        alt="{{ $place->name }}" style="height: 200px; object-fit: cover;">
                @else
                    <img src="{{ asset('assets/img/demo/property-1.jpg') }}" class="img-responsive w-100"
                        alt="{{ $place->name }}" style="height: 200px; object-fit: cover;">
                @endif
            </a>

            <!-- Badges on Image -->
            <div class="position-absolute" style="top: 10px; left: 10px;">
                @if ($place->is_verified)
                    <span class="badge badge-primary p-2" title="Verified"><i class="fa fa-check-circle"></i>
                        Verified</span>
                @endif
            </div>

            @auth
                <div class="position-absolute" style="top: 10px; right: 10px;">
                    <form method="POST" action="{{ route('favorites.toggle') }}">
                        @csrf
                        <input type="hidden" name="place_id" value="{{ $place->id }}">
                        <button type="submit" class="btn btn-light btn-sm rounded-circle shadow-sm"
                            style="width:35px; height:35px;">
                            <i class="fa {{ $place->is_favorited ? 'fa-heart text-danger' : 'fa-heart-o' }}"></i>
                        </button>
                    </form>
                </div>
            @endauth
        </div>

        <!-- Content Section -->
        <div class="item-entry p-3">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="m-0 font-weight-bold">
                    <a href="{{ route('places.show', $place) }}" class="text-dark">{{ $place->name }}</a>
                </h5>
                @if ($place->category)
                    <span class="badge badge-light border text-dark">{{ $place->category->name }}</span>
                @endif
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted small">
                    <i class="fa fa-map-marker text-danger"></i> {{ $place->city }}
                </span>
                <span class="proerty-price">
                    @include('components.rating-stars', ['rating' => $place->avg_rating ?? 0])
                </span>
            </div>

            <hr class="my-2">

            <!-- Footer Details -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="status-info">
                    @include('components.status-badge', ['status' => $place->status])
                    <span class="ml-2 text-muted small font-italic">
                        {{ ucfirst(str_replace('_', ' ', $place->price_level)) }}
                    </span>
                </div>

                <a href="{{ route('places.show', $place) }}" class="btn btn-primary btn-sm px-3">
                    View Details
                </a>
            </div>
        </div>
    </div>
</div>
