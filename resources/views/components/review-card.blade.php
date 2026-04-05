<div class="testimonial" style="border-bottom: 1px solid #eee; padding: 15px 0;">
    <div class="row">
        <div class="col-xs-2 col-sm-1">
            <img src="{{ asset('assets/img/client-face1.png') }}" class="img-circle" style="width:40px; height:40px;"
                alt="{{ $review->user->name ?? 'User' }}">
        </div>
        <div class="col-xs-10 col-sm-11">
            <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
            <span class="text-muted" style="margin-left:10px; font-size:12px;">
                {{ $review->created_at->diffForHumans() }}
            </span>
            <div>
                @include('components.rating-stars', ['rating' => $review->rating])
            </div>
            @if (isset($showPlace) && $showPlace && $review->place)
                <p style="margin:5px 0 0;">
                    <a href="{{ route('places.show', $review->place) }}">
                        <i class="fa fa-map-marker"></i> {{ $review->place->name }}
                    </a>
                </p>
            @endif
            @if ($review->comment)
                <p style="margin-top: 8px;">{{ $review->comment }}</p>
            @endif
        </div>
    </div>
</div>
