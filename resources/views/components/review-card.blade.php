<div class="testimonial mk-review">
    <div class="row">
        <div class="col-xs-2 col-sm-1">
            <span class="mk-avatar" aria-label="{{ $review->user->name ?? __('common.user') }}">
                {{ Str::upper(Str::substr($review->user->name ?? 'U', 0, 1)) }}
            </span>
        </div>
        <div class="col-xs-10 col-sm-11">
            <strong>{{ $review->user->name ?? __('common.anonymous') }}</strong>
            <span class="text-muted mk-review__meta">
                {{ __('common.dates.reviewed_on') }} {{ \App\Support\LocalizedDate::dateTime($review->created_at) }}
            </span>
            <div>
                @include('components.rating-stars', ['rating' => $review->rating])
            </div>
            @if (isset($showPlace) && $showPlace && ($review->place || $review->service))
                <p class="mk-review__target">
                    <a href="{{ $review->service
                        ? route('services.show', $review->service)
                        : route('places.show', $review->place) }}">
                        <i class="fa {{ $review->service ? 'fa-briefcase' : 'fa-map-marker' }}"></i>
                        {{ $review->service?->name ?? $review->place?->name }}
                    </a>
                </p>
            @endif
            @if ($review->comment)
                <p class="mk-review__comment">{{ $review->comment }}</p>
            @endif
            @auth
                @if (auth()->id() === $review->user_id)
                    @php
                        $isServiceReview = filled($review->service_id);
                        $reviewTarget = $isServiceReview ? $review->service : $review->place;
                        $updateRoute = $isServiceReview ? 'services.reviews.update' : 'places.reviews.update';
                        $destroyRoute = $isServiceReview ? 'services.reviews.destroy' : 'places.reviews.destroy';
                    @endphp
                    <details class="mk-review__editor">
                        <summary class="mk-review__summary">{{ __('common.actions.edit') }} {{ __('places.reviews') }}</summary>
                        <form method="POST" action="{{ route($updateRoute, [$reviewTarget, $review]) }}"
                            class="mk-review__editor">
                            @csrf
                            @method('PATCH')
                            <label>
                                {{ __('places.rating') }}
                                <select name="rating" required>
                                    @for ($rating = 5; $rating >= 1; $rating--)
                                        <option value="{{ $rating }}" @selected($review->rating === $rating)>{{ $rating }}</option>
                                    @endfor
                                </select>
                            </label>
                            <textarea name="comment" maxlength="2000" rows="3" class="form-control">{{ $review->comment }}</textarea>
                            <button type="submit" class="btn btn-primary btn-sm mt-2">{{ __('common.actions.save') }} {{ __('places.reviews') }}</button>
                        </form>
                        <form method="POST" action="{{ route($destroyRoute, [$reviewTarget, $review]) }}"
                            onsubmit="return confirm('{{ __('common.actions.delete') }} {{ __('places.reviews') }}?')" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">{{ __('common.actions.delete') }} {{ __('places.reviews') }}</button>
                        </form>
                    </details>
                @endif
            @endauth
        </div>
    </div>
</div>
