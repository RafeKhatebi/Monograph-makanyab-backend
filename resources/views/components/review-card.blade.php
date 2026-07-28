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
            @if (isset($showPlace) && $showPlace && ($review->place || $review->service))
                <p style="margin:5px 0 0;">
                    <a href="{{ $review->service
                        ? route('services.show', $review->service)
                        : route('places.show', $review->place) }}">
                        <i class="fa {{ $review->service ? 'fa-briefcase' : 'fa-map-marker' }}"></i>
                        {{ $review->service?->name ?? $review->place?->name }}
                    </a>
                </p>
            @endif
            @if ($review->comment)
                <p style="margin-top: 8px;">{{ $review->comment }}</p>
            @endif
            @auth
                @if (auth()->id() === $review->user_id)
                    @php
                        $isServiceReview = filled($review->service_id);
                        $reviewTarget = $isServiceReview ? $review->service : $review->place;
                        $updateRoute = $isServiceReview ? 'services.reviews.update' : 'places.reviews.update';
                        $destroyRoute = $isServiceReview ? 'services.reviews.destroy' : 'places.reviews.destroy';
                    @endphp
                    <details style="margin-top:10px;">
                        <summary style="cursor:pointer;color:#2563EB;font-size:13px;">Edit review</summary>
                        <form method="POST" action="{{ route($updateRoute, [$reviewTarget, $review]) }}"
                            style="margin-top:10px;">
                            @csrf
                            @method('PATCH')
                            <label>
                                Rating
                                <select name="rating" required>
                                    @for ($rating = 5; $rating >= 1; $rating--)
                                        <option value="{{ $rating }}" @selected($review->rating === $rating)>{{ $rating }}</option>
                                    @endfor
                                </select>
                            </label>
                            <textarea name="comment" maxlength="2000" rows="3" class="form-control">{{ $review->comment }}</textarea>
                            <button type="submit" class="btn btn-primary btn-sm" style="margin-top:6px;">Save review</button>
                        </form>
                        <form method="POST" action="{{ route($destroyRoute, [$reviewTarget, $review]) }}"
                            onsubmit="return confirm('Delete this review?')" style="margin-top:6px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete review</button>
                        </form>
                    </details>
                @endif
            @endauth
        </div>
    </div>
</div>
