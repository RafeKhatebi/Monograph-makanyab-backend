@extends('layouts.app')
@section('title', __('services.title'))
@php use Illuminate\Support\Str; @endphp
@section('content')

    {{-- Header --}}
    <div class="listing-hero listing-hero--service">
        <div class="container">
            <h1 class="listing-hero__title">{{ __('services.title') }}</h1>
            <p class="listing-hero__text">{{ __('services.subtitle') }}</p>
        </div>
    </div>

    <div class="listing-layout listing-layout--simple">
        <div class="container">
            <div class="row" data-load-more-target="services">
                @include('pages.services._cards', ['services' => $services])
            </div>

            @if ($services->hasMorePages())
                <div class="listing-load-more" data-load-more-wrap data-next-page="{{ $services->currentPage() + 1 }}" data-endpoint="{{ route('services.load-more') }}" data-target="services">
                    <button type="button" class="mk-button mk-button--primary mk-button--lg" data-load-more-trigger>
                        {{ __('services.load_more') }}
                    </button>
                </div>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/listing-load-more.js') }}"></script>
@endpush
