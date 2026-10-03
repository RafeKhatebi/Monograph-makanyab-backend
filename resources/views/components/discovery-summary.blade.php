@props(['count', 'label', 'type'])

@php
    $isService = $type === 'service';
    $categoryRoute = $isService ? 'service-categories.index' : 'categories.index';
@endphp

<section class="listing-summary" aria-label="{{ $label }}">
    <div class="listing-summary__copy">
        <span class="listing-summary__eyebrow">{{ __('navigation.discover') }}</span>
        <h2>{{ $label }}</h2>
    </div>
    <nav class="listing-summary__actions" aria-label="{{ __('search.shortcuts') }}">
        <a href="{{ route('search.index', ['type' => $type]) }}">
            <i class="fa fa-sliders" aria-hidden="true"></i>
            {{ __('search.filters') }}
        </a>
        <a href="{{ route($categoryRoute) }}">
            <i class="fa fa-th-large" aria-hidden="true"></i>
            {{ __('navigation.categories') }}
        </a>
    </nav>
</section>
