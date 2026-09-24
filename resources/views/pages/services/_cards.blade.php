@if (count($services) === 0)
    <div class="col-md-12 listing-empty">
        <div class="mk-empty-icon"><i class="fa fa-briefcase" aria-hidden="true"></i></div>
        <h3 class="mk-heading mk-heading--md">{{ __('services.no_results') }}</h3>
        <p class="mk-text--muted mk-stack-sm">{{ __('places.adjust') }}</p>
        <a href="{{ route('services.index') }}" class="mk-button mk-button--primary mk-button--md">{{ __('places.reset_filters') }}</a>
    </div>
@else
    @foreach ($services as $service)
        <x-service-card :service="$service" />
    @endforeach
@endif
