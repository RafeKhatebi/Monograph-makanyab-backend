@props([
    'title',
    'description',
    'switchText',
    'switchRoute',
    'switchLinkText',
    'formTitle',
    'formAction',
    'categoryField',
    'categoryLabel',
    'categories' => [],
    'submitText' => __('suggestions.submit'),
])

<div class="mk-hero suggestion-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <h1 class="suggestion-hero__title">{{ $title }}</h1>
                <p class="suggestion-hero__text">{{ $description }}</p>
                <p class="suggestion-hero__switch">
                    {{ $switchText }}
                    <a href="{{ route($switchRoute) }}">{{ $switchLinkText }}</a>.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="suggestion-form-shell">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="suggestion-form-card">
                    <h3 class="suggestion-form-title">{{ $formTitle }}
                    </h3>

                    @include('pages.shared.suggestion-form', [
                        'action' => $formAction,
                        'categoryField' => $categoryField,
                        'categoryLabel' => $categoryLabel,
                        'categories' => $categories,
                        'submitText' => $submitText,
                    ])
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="{{ asset('assets/js/suggestion-map.js') }}"></script>
@endpush
