@if (count($places) === 0)
    <div class="col-md-12 listing-empty">
        <div class="mk-empty-icon"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
        <h3 class="mk-heading mk-heading--md">{{ __('places.no_results') }}</h3>
        <p class="mk-text--muted mk-stack-sm">{{ __('places.adjust') }}</p>
        <a href="{{ route('places.index') }}" class="mk-button mk-button--primary mk-button--md">{{ __('places.reset_filters') }}</a>
    </div>
@else
    @foreach ($places as $place)
        @include('components.place-card', ['place' => $place])
    @endforeach
@endif
