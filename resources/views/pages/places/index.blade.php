@extends('layouts.app')
@section('title', __('places.title'))
@section('content')

    {{-- Page Header --}}
    <div class="listing-hero">
        <div class="container">
            <h1 class="listing-hero__title">{{ __('places.title') }}</h1>
            <p class="listing-hero__text">{{ __('places.subtitle') }}</p>
        </div>
    </div>

    <div class="listing-layout listing-layout--simple">
        <div class="container">
            <div class="row" data-load-more-target="places">
                @include('pages.places._cards', ['places' => $places])
            </div>

            @if ($places->hasMorePages())
                <div class="listing-load-more" data-load-more-wrap data-next-page="{{ $places->currentPage() + 1 }}" data-endpoint="{{ route('places.load-more') }}" data-target="places">
                    <button type="button" class="mk-button mk-button--primary mk-button--lg" data-load-more-trigger>
                        {{ __('places.load_more') }}
                    </button>
                </div>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/listing-load-more.js') }}"></script>
@endpush
