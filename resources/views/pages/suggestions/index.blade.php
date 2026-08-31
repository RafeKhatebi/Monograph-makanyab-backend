@extends('layouts.app')

@php
    use App\Enums\PlaceStatus;
    use App\Enums\PriceLevel;

    $activeType = in_array(old('type', request('type', 'place')), ['place', 'service'], true)
        ? old('type', request('type', 'place'))
        : 'place';
    $priceOptions = [
        PriceLevel::Low->value => __('common.price.low'),
        PriceLevel::Medium->value => __('common.price.medium'),
        PriceLevel::High->value => __('common.price.high'),
        PriceLevel::Luxury->value => __('common.price.luxury'),
    ];
    $statusOptions = [
        PlaceStatus::Open->value => __('common.status.open'),
        PlaceStatus::Closed->value => __('common.status.closed'),
        PlaceStatus::TemporarilyClosed->value => __('common.status.temporarily_closed'),
    ];
@endphp

@section('title', __('suggestions.hub.title'))

@section('content')
    <section class="mk-hero">
        <div class="container">
            <h1 class="mk-hero__title">{{ __('suggestions.hub.title') }}</h1>
            <p class="mk-hero__text">{{ __('suggestions.hub.description') }}</p>
        </div>
    </section>

    <section class="suggestion-hub">
        <div class="container">
            @if (session('success'))
                <div class="mk-alert mk-alert--success suggestion-success">
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            <div class="suggestion-hub__grid">
                <main class="suggestion-panel">
                    <form action="{{ route('add.store') }}" method="POST" enctype="multipart/form-data" data-suggest-form>
                        @csrf
                        <input type="hidden" name="country" value="{{ old('country', 'Afghanistan') }}">

                        <fieldset class="suggestion-tabs">
                            <legend class="sr-only">{{ __('suggestions.submission_type') }}</legend>
                            @foreach (['place', 'service'] as $type)
                                <label class="suggestion-tab">
                                    <input type="radio" name="type" value="{{ $type }}" @checked($activeType === $type)
                                        data-suggest-type>
                                    <span>{{ __('suggestions.types.'.$type) }}</span>
                                </label>
                            @endforeach
                        </fieldset>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />

                        <section class="suggestion-section">
                            <h2>{{ __('suggestions.sections.basic') }}</h2>
                            <div class="suggestion-form-grid">
                                <div data-suggest-for="place service">
                                    <x-form-field for="name" :label="__('suggestions.name')" :value="old('name')" />
                                </div>
                                <div data-suggest-for="place">
                                    <x-input-label for="place_category_id" :value="__('suggestions.category')" />
                                    <x-select-input id="place_category_id" name="place_category_id" :options="$placeCategories"
                                        placeholder="{{ __('suggestions.select_category') }}" />
                                    <x-input-error :messages="$errors->get('place_category_id')" class="mt-2" />
                                </div>
                                <div data-suggest-for="service">
                                    <x-input-label for="service_category_id" :value="__('suggestions.category')" />
                                    <x-select-input id="service_category_id" name="service_category_id" :options="$serviceCategories"
                                        placeholder="{{ __('suggestions.select_category') }}" />
                                    <x-input-error :messages="$errors->get('service_category_id')" class="mt-2" />
                                </div>
                            </div>

                            <div data-suggest-for="place service" class="suggestion-form-grid suggestion-form-grid--single">
                                <x-form-field for="tagline" :label="__('suggestions.tagline')" :value="old('tagline')" />
                            </div>

                            <div data-suggest-for="place service" class="profile-form-group">
                                <x-input-label for="description" :value="__('suggestions.description')" />
                                <x-textarea id="description" name="description" rows="5"
                                    placeholder="{{ __('suggestions.description_placeholder') }}">{{ old('description') }}</x-textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                        </section>

                        <section class="suggestion-section" data-suggest-for="place service">
                            <h2>{{ __('suggestions.sections.location') }}</h2>
                            <div class="suggestion-form-grid">
                                <x-form-field for="province" :label="__('suggestions.province')" :value="old('province')" />
                                <x-form-field for="city" :label="__('suggestions.city')" :value="old('city')" />
                                <x-form-field for="district" :label="__('suggestions.district')" :value="old('district')" />
                                <x-form-field for="address" :label="__('suggestions.address')" :value="old('address')" />
                            </div>
                            <div class="suggestion-form-grid">
                                <x-form-field for="subdistrict" :label="__('suggestions.subdistrict')" :value="old('subdistrict')" />
                                <x-form-field for="neighborhood" :label="__('suggestions.neighborhood')" :value="old('neighborhood')" />
                                <x-form-field for="village" :label="__('suggestions.village')" :value="old('village')" />
                                <x-form-field for="postal_code" :label="__('suggestions.postal_code')" :value="old('postal_code')" />
                            </div>
                            <div class="suggestion-form-grid">
                                <x-form-field for="latitude" :label="__('suggestions.latitude')" :value="old('latitude')" />
                                <x-form-field for="longitude" :label="__('suggestions.longitude')" :value="old('longitude')" />
                            </div>
                        </section>

                        <section class="suggestion-section" data-suggest-for="place service">
                            <h2>{{ __('suggestions.sections.contact') }}</h2>
                            <div class="suggestion-form-grid">
                                <x-form-field for="phone_1" :label="__('suggestions.phone')" :value="old('phone_1')" />
                                <x-form-field for="phone_2" :label="__('suggestions.phone_2')" :value="old('phone_2')" />
                                <x-form-field for="whatsapp" :label="__('suggestions.whatsapp')" :value="old('whatsapp')" />
                                <x-form-field for="website" :label="__('suggestions.website')" :value="old('website')" />
                            </div>
                        </section>

                        <section class="suggestion-section" data-suggest-for="place service">
                            <h2>{{ __('suggestions.sections.details') }}</h2>
                            <div class="suggestion-form-grid">
                                <div>
                                    <x-input-label for="price_level" :value="__('suggestions.price_level')" />
                                    <x-select-input id="price_level" name="price_level" :options="$priceOptions"
                                        placeholder="{{ __('suggestions.select_price') }}" />
                                    <x-input-error :messages="$errors->get('price_level')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="status" :value="__('suggestions.open_status')" />
                                    <x-select-input id="status" name="status" :options="$statusOptions"
                                        placeholder="{{ __('common.status.open') }}" />
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>
                            </div>
                        </section>

                        <section class="suggestion-section">
                            <h2>{{ __('suggestions.sections.images') }}</h2>
                            <div data-suggest-for="place service">
                                <x-input-label for="images" :value="__('suggestions.images')" />
                                <input id="images" type="file" name="images[]" class="form-control" accept="image/*" multiple>
                                <p class="suggestion-help">{{ __('suggestions.images_help') }}</p>
                                <x-input-error :messages="$errors->get('images')" class="mt-2" />
                                <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                            </div>
                        </section>

                        <section class="suggestion-section">
                            <h2>{{ __('suggestions.sections.extra') }}</h2>
                            <x-input-label for="extra_information" :value="__('suggestions.extra_information')" />
                            <x-textarea id="extra_information" name="extra_information" rows="4"
                                placeholder="{{ __('suggestions.extra_placeholder') }}">{{ old('extra_information') }}</x-textarea>
                            <x-input-error :messages="$errors->get('extra_information')" class="mt-2" />
                        </section>

                        <div class="suggestion-actions">
                            <button type="submit" name="submit_action" value="draft" class="mk-button mk-button--secondary mk-button--lg">
                                {{ __('suggestions.save_draft') }}
                            </button>
                            <button type="submit" name="submit_action" value="send_review" class="mk-button mk-button--primary mk-button--lg">
                                {{ __('suggestions.send_for_review') }}
                            </button>
                        </div>
                    </form>
                </main>

                <aside class="suggestion-status-panel">
                    <h2>{{ __('suggestions.my_submissions') }}</h2>
                    <p>{{ __('suggestions.my_submissions_text') }}</p>
                    <div class="suggestion-status-list">
                        @forelse ($submissions as $submission)
                            <article class="suggestion-status-card">
                                <span>{{ $submission['type'] }}</span>
                                <h3>{{ $submission['title'] }}</h3>
                                @if ($submission['category'])
                                    <p>{{ $submission['category'] }}</p>
                                @endif
                                <strong>{{ $submission['status'] }}</strong>
                                <time>{{ \App\Support\LocalizedDate::date($submission['date']) }}</time>
                            </article>
                        @empty
                            <p class="mk-text mk-text--muted">{{ __('suggestions.no_submissions') }}</p>
                        @endforelse
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/suggestion-hub.js') }}"></script>
@endpush
