@extends('layouts.app')

@php
    use App\Enums\SuggestionStatus;

    $isEditing = isset($editingSubmission) && $editingSubmission;
    $activeType = in_array(old('type', $editingType ?? request('type', 'place')), ['place', 'service', 'post'], true)
        ? old('type', $editingType ?? request('type', 'place'))
        : 'place';
    $fieldValue = function (string $field, mixed $default = null) use ($isEditing, $editingSubmission) {
        $value = old($field, $isEditing ? ($editingSubmission->{$field} ?? $default) : $default);

        return $value instanceof \BackedEnum ? $value->value : $value;
    };
    $statusValue = $isEditing
        ? ($activeType === 'post'
            ? ($editingSubmission->submission_status ?? SuggestionStatus::Draft)
            : ($editingSubmission->suggestion_status ?? SuggestionStatus::Draft))
        : null;
    $statusLabel = $statusValue instanceof SuggestionStatus
        ? $statusValue->label()
        : ($statusValue ? __('suggestions.status.'.$statusValue) : null);
    $existingImages = $isEditing && $activeType !== 'post'
        ? $editingSubmission->media->where('type', 'image')->sortBy('sort_order')
        : collect();
    $postImage = $isEditing && $activeType === 'post' && $editingSubmission->image
        ? asset('storage/'.$editingSubmission->image)
        : null;
@endphp

@section('title', $isEditing ? __('suggestions.edit_title') : __('suggestions.hub.title'))

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
@endpush

@section('content')
    <section class="submission-workspace">
        <div class="container">
            <header class="submission-header">
                <a href="{{ url()->previous() }}" class="submission-back" aria-label="{{ __('common.actions.back') }}">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </a>
                <div class="submission-heading">
                    <h1>{{ $isEditing ? __('suggestions.edit_title') : __('suggestions.hub.title') }}</h1>
                    <p>{{ $isEditing ? __('suggestions.edit_description') : __('suggestions.hub.description') }}</p>
                </div>
                <div class="submission-type-control">
                    <span>{{ __('suggestions.add_new') }}</span>
                    @if ($isEditing)
                        <div class="submission-type-locked">
                            <i class="fa {{ $activeType === 'service' ? 'fa-wrench' : ($activeType === 'post' ? 'fa-file-text-o' : 'fa-map-marker') }}" aria-hidden="true"></i>
                            {{ __('suggestions.types.'.$activeType) }}
                        </div>
                    @else
                        <select name="type" form="submission-form" class="submission-type-select" data-suggest-type-select>
                            @foreach (['place', 'service', 'post'] as $type)
                                <option value="{{ $type }}" @selected($activeType === $type)>{{ __('suggestions.types.'.$type) }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </header>

            @if ($errors->any())
                <div class="mk-alert mk-alert--danger suggestion-error" role="alert">
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                    <span>{{ __('suggestions.validation.form_error') }}</span>
                </div>
            @endif

            @if ($isEditing)
                <div class="suggestion-edit-banner submission-edit-banner">
                    <div>
                        <span>{{ __('suggestions.editing_label') }}</span>
                        <strong>{{ $editingSubmission->name ?? $editingSubmission->title }}</strong>
                        <p>{{ __('suggestions.editing_state_help') }}</p>
                    </div>
                    <span class="profile-status-pill">{{ $statusLabel }}</span>
                </div>
            @endif

            <form id="submission-form" action="{{ $isEditing ? route('add.update', ['type' => $activeType, 'submission' => $editingSubmission->getKey()]) : route('add.store') }}" method="POST" enctype="multipart/form-data" data-suggest-form data-prevent-double-submit>
                @csrf
                @if ($isEditing)
                    @method('PUT')
                    <input type="hidden" name="type" value="{{ $activeType }}">
                @endif
                <input type="hidden" name="country" value="{{ $fieldValue('country', 'Afghanistan') }}">
                <input type="hidden" id="city-value" name="city" value="{{ $fieldValue('city') }}">

                <div class="submission-grid">
                    <main class="submission-main">
                        <section class="submission-card" data-suggest-for="place service">
                            <div class="submission-card__header">
                                <span class="submission-card__icon"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                                <div>
                                    <h2>{{ __('suggestions.sections.basic') }}</h2>
                                    <p>{{ __('suggestions.basic_help') }}</p>
                                </div>
                            </div>

                            <div class="submission-fields submission-fields--two">
                                <div data-suggest-for="place">
                                    <x-input-label for="place-name" :value="__('suggestions.place_name')" />
                                    <input id="place-name" name="name" type="text" value="{{ $fieldValue('name') }}" required
                                        class="form-control @error('name') is-invalid @enderror" dir="auto">
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                <div data-suggest-for="service">
                                    <x-input-label for="service-name" :value="__('suggestions.service_name')" />
                                    <input id="service-name" name="name" type="text" value="{{ $fieldValue('name') }}" required
                                        class="form-control @error('name') is-invalid @enderror" dir="auto">
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                <div data-suggest-for="place">
                                    <x-input-label for="place_category_id" :value="__('suggestions.category')" />
                                    <x-select-input id="place_category_id" name="place_category_id" :options="$placeCategories"
                                        :selected="$fieldValue('place_category_id')" placeholder="{{ __('suggestions.select_category') }}" required />
                                    <x-input-error :messages="$errors->get('place_category_id')" class="mt-2" />
                                </div>
                                <div data-suggest-for="service">
                                    <x-input-label for="service_category_id" :value="__('suggestions.service_category')" />
                                    <x-select-input id="service_category_id" name="service_category_id" :options="$serviceCategories"
                                        :selected="$fieldValue('service_category_id')" placeholder="{{ __('suggestions.select_service_category') }}" required />
                                    <x-input-error :messages="$errors->get('service_category_id')" class="mt-2" />
                                </div>
                            </div>

                            <div class="submission-fields">
                                <x-input-label for="description" :value="__('suggestions.description')" />
                                <x-textarea id="description" name="description" rows="4"
                                    :value="$fieldValue('description')" placeholder="{{ __('suggestions.description_placeholder') }}" dir="auto" />
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </section>

                        <section class="submission-card" data-suggest-for="post">
                            <div class="submission-card__header">
                                <span class="submission-card__icon"><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
                                <div>
                                    <h2>{{ __('suggestions.types.post') }}</h2>
                                    <p>{{ __('suggestions.post_help') }}</p>
                                </div>
                            </div>

                            <div class="submission-fields">
                                <x-form-field for="title" :label="__('suggestions.title')" :value="$fieldValue('title')" required />
                                <x-form-field for="excerpt" :label="__('suggestions.excerpt')" :value="$fieldValue('excerpt')" />
                                <x-input-label for="content" :value="__('suggestions.content')" />
                                <x-textarea id="content" name="content" rows="8"
                                    :value="$fieldValue('content')" placeholder="{{ __('suggestions.content_placeholder') }}" dir="auto" required />
                                <x-input-error :messages="$errors->get('content')" class="mt-2" />
                            </div>
                        </section>

                        <section class="submission-card" data-suggest-for="place service">
                            <div class="submission-card__header">
                                <span class="submission-card__icon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                <div>
                                    <h2>{{ $activeType === 'service' ? __('suggestions.service_location') : __('suggestions.sections.location') }}</h2>
                                    <p>{{ __('suggestions.location_help') }}</p>
                                </div>
                            </div>

                            <div class="submission-fields submission-fields--three">
                                <div>
                                    <x-input-label for="province-search" :value="__('suggestions.search_province')" />
                                    <input id="province-search" type="search" value="{{ $fieldValue('province') }}"
                                        placeholder="{{ __('suggestions.province_placeholder') }}" class="form-control">
                                </div>
                                <div>
                                    <x-input-label for="province-select" :value="__('suggestions.province')" />
                                    <select id="province-select" name="province" data-selected="{{ $fieldValue('province') }}"
                                        class="form-control @error('province') is-invalid @enderror" required>
                                        <option value="">{{ __('suggestions.select_province') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('province')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="district-select" :value="__('suggestions.district_city')" />
                                    <select id="district-select" name="district" data-selected="{{ $fieldValue('district') }}"
                                        class="form-control @error('district') is-invalid @enderror" required disabled>
                                        <option value="">{{ __('suggestions.select_province_first') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('district')" class="mt-2" />
                                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                </div>
                            </div>

                            <div class="submission-fields submission-fields--two">
                                <x-form-field for="neighborhood" :label="__('suggestions.area_neighborhood')" :value="$fieldValue('neighborhood')" />
                                <x-form-field for="address" :label="__('suggestions.address')" :value="$fieldValue('address')" />
                            </div>

                            <div class="submission-map-grid">
                                <div>
                                    <x-input-label :value="__('suggestions.map_location')" />
                                    <div id="suggestion-map" class="suggestion-map"></div>
                                </div>
                                <div class="submission-coordinates">
                                    <x-form-field for="latitude" :label="__('suggestions.latitude')" type="number" step="0.000001" :value="$fieldValue('latitude')" />
                                    <x-form-field for="longitude" :label="__('suggestions.longitude')" type="number" step="0.000001" :value="$fieldValue('longitude')" />
                                    <p class="suggestion-help">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                        {{ __('suggestions.map_help_short') }}
                                    </p>
                                    <p class="suggestion-coordinates">{{ __('suggestions.coordinates') }}:
                                        <span id="selected-coords">{{ $fieldValue('latitude') && $fieldValue('longitude') ? $fieldValue('latitude').', '.$fieldValue('longitude') : __('suggestions.none') }}</span>
                                    </p>
                                </div>
                            </div>
                        </section>
                    </main>

                    <aside class="submission-side">
                        <section class="submission-card" data-suggest-for="place service">
                            <div class="submission-card__header">
                                <span class="submission-card__icon"><i class="fa fa-camera" aria-hidden="true"></i></span>
                                <div>
                                    <h2>{{ __('suggestions.sections.images') }}</h2>
                                    <p>{{ __('suggestions.photos_help') }}</p>
                                </div>
                            </div>

                            <div data-media-upload>
                                <div data-drop-zone class="submission-drop-zone">
                                    <input id="images" type="file" name="images[]" accept="image/*" multiple>
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <strong>{{ __('suggestions.drop_photos') }}</strong>
                                    <span>{{ __('suggestions.click_to_browse') }}</span>
                                    <small>{{ __('suggestions.images_help') }}</small>
                                </div>
                                <input type="hidden" name="cover_image_index" value="{{ old('cover_image_index', 0) }}" data-cover-index>
                                <div data-image-preview class="media-preview-grid submission-preview-grid"></div>
                            </div>
                            <x-input-error :messages="$errors->get('images')" class="mt-2" />
                            <x-input-error :messages="$errors->get('images.*')" class="mt-2" />

                            @if ($existingImages->isNotEmpty())
                                <div class="submission-existing-media">
                                    @foreach ($existingImages as $image)
                                        <img src="{{ asset('storage/'.$image->file_path) }}" alt="{{ $editingSubmission->name }}" loading="lazy">
                                    @endforeach
                                </div>
                            @endif
                        </section>

                        <section class="submission-card" data-suggest-for="post">
                            <div class="submission-card__header">
                                <span class="submission-card__icon"><i class="fa fa-image" aria-hidden="true"></i></span>
                                <div>
                                    <h2>{{ __('suggestions.image') }}</h2>
                                    <p>{{ __('suggestions.image_help') }}</p>
                                </div>
                            </div>
                            <input id="image" type="file" name="image" class="form-control" accept="image/*">
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            @if ($postImage)
                                <div class="submission-post-image">
                                    <img src="{{ $postImage }}" alt="{{ $editingSubmission->title }}" loading="lazy">
                                </div>
                            @endif
                        </section>

                        <section class="submission-card" data-suggest-for="place service">
                            <div class="submission-card__header">
                                <span class="submission-card__icon"><i class="fa fa-phone" aria-hidden="true"></i></span>
                                <div>
                                    <h2>{{ __('suggestions.sections.contact') }}</h2>
                                    <p>{{ __('suggestions.contact_help') }}</p>
                                </div>
                            </div>

                            <div class="submission-fields submission-fields--two">
                                <x-form-field for="phone_1" :label="__('suggestions.phone')" type="tel" :value="$fieldValue('phone_1')" required />
                                <x-form-field for="whatsapp" :label="__('suggestions.whatsapp')" type="tel" :value="$fieldValue('whatsapp')" />
                                <x-form-field for="website" :label="__('suggestions.website')" type="url" :value="$fieldValue('website')" />
                            </div>
                        </section>

                        <details class="submission-card submission-more" data-suggest-for="place service">
                            <summary>
                                <span class="submission-card__icon"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></span>
                                <span>
                                    <strong>{{ __('suggestions.sections.extra') }}</strong>
                                    <small>{{ __('suggestions.more_details_help') }}</small>
                                </span>
                            </summary>
                            <div class="submission-fields">
                                <x-input-label for="extra_information" :value="__('suggestions.extra_information')" />
                                <x-textarea id="extra_information" name="extra_information" rows="4"
                                    :value="$fieldValue('extra_information')" placeholder="{{ __('suggestions.extra_placeholder') }}" dir="auto" />
                                <x-input-error :messages="$errors->get('extra_information')" class="mt-2" />
                            </div>
                        </details>
                    </aside>
                </div>

                <div class="submission-actions">
                    <a href="{{ route('profile.index') }}" class="mk-button mk-button--secondary mk-button--lg">{{ __('common.actions.cancel') }}</a>
                    <button type="submit" name="submit_action" value="draft" class="mk-button mk-button--secondary mk-button--lg" formnovalidate>
                        {{ __('suggestions.save_draft') }}
                    </button>
                    <button type="submit" name="submit_action" value="send_review" class="mk-button mk-button--primary mk-button--lg" data-submit-button>
                        <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                        {{ $isEditing ? __('suggestions.resubmit_for_review') : __('suggestions.send_for_review') }}
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="{{ asset('assets/js/media-upload.js') }}"></script>
    <script src="{{ asset('assets/js/suggestion-map.js') }}"></script>
    <script src="{{ asset('assets/js/suggestion-hub.js') }}"></script>
@endpush
