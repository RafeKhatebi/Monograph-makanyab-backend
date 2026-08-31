@php
    use App\Enums\PlaceStatus;
    use App\Enums\PriceLevel;

    $statusOptions = [
        PlaceStatus::Open->value => PlaceStatus::Open->label(),
        PlaceStatus::Closed->value => PlaceStatus::Closed->label(),
        PlaceStatus::TemporarilyClosed->value => PlaceStatus::TemporarilyClosed->label(),
    ];

    $priceOptions = [
        PriceLevel::Low->value => __('common.price.low'),
        PriceLevel::Medium->value => __('common.price.medium'),
        PriceLevel::High->value => __('common.price.high'),
        PriceLevel::Luxury->value => __('common.price.luxury'),
    ];
@endphp

<form action="{{ $action }}" method="POST">
    @csrf
    <input type="hidden" name="country" value="{{ old('country', 'Afghanistan') }}">

    <div class="suggestion-form-grid">
        <x-form-field for="name" :label="__('suggestions.name')" :value="old('name')" />

        <div>
            <x-input-label for="{{ $categoryField }}" :value="$categoryLabel ?: __('suggestions.category')" />
            <x-select-input id="{{ $categoryField }}" name="{{ $categoryField }}" :options="$categories"
                placeholder="{{ __('suggestions.select_category') }}" />
            <x-input-error :messages="$errors->get($categoryField)" class="mt-2" />
        </div>
    </div>

    <div class="suggestion-form-grid">
        <div>
            <x-input-label for="province-search" :value="__('suggestions.search_province')" />
            <input id="province-search" type="search" value="{{ old('province') }}" placeholder="{{ __('suggestions.province_placeholder') }}"
                class="form-control" />
        </div>

        <div>
            <x-input-label for="province-select" :value="__('suggestions.province')" />
            <select id="province-select" name="province" data-selected="{{ old('province') }}"
                class="form-control">
                <option value="">{{ __('suggestions.select_province') }}</option>
            </select>
            <x-input-error :messages="$errors->get('province')" class="mt-2" />
        </div>
    </div>

    <div class="suggestion-form-grid">
        <div>
            <x-input-label for="district-select" :value="__('suggestions.district')" />
            <select id="district-select" name="district" data-selected="{{ old('district') }}"
                class="form-control" disabled>
                <option value="">{{ __('suggestions.select_province_first') }}</option>
            </select>
            <x-input-error :messages="$errors->get('district')" class="mt-2" />
        </div>

        <x-form-field for="city" :label="__('suggestions.city')" :value="old('city')" />
    </div>

    <div class="suggestion-form-grid">
        <x-form-field for="address" :label="__('suggestions.address')" :value="old('address')" />
        <x-form-field for="website" :label="__('suggestions.website')" :value="old('website')" />
    </div>

    <div class="suggestion-form-grid">
        <x-form-field for="phone_1" :label="__('suggestions.phone')" :value="old('phone_1')" />
        <x-form-field for="whatsapp" :label="__('suggestions.whatsapp')" :value="old('whatsapp')" />
    </div>

    <div class="suggestion-form-grid">
        <div>
            <x-input-label for="price_level" :value="__('suggestions.price_level')" />
            <x-select-input id="price_level" name="price_level" :options="$priceOptions" placeholder="{{ __('suggestions.select_price') }}" />
            <x-input-error :messages="$errors->get('price_level')" class="mt-2" />
        </div>
    </div>

    <div class="suggestion-form-grid suggestion-form-grid--single">
        <x-form-field for="tagline" :label="__('suggestions.tagline')" :value="old('tagline')" />
    </div>

    <div class="profile-form-group">
        <x-input-label for="description" :value="__('suggestions.description')" />
        <x-textarea id="description" name="description" rows="6"
            placeholder="{{ __('suggestions.description_placeholder') }}">{{ old('description') }}</x-textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div class="suggestion-location">
        <x-input-label :value="__('suggestions.exact_location')" />
        <p class="suggestion-help">{{ __('suggestions.map_help') }}</p>
        <div id="suggestion-map" class="suggestion-map"></div>
        <p class="suggestion-coordinates">{{ __('suggestions.coordinates') }}: <span id="selected-coords">{{ old('latitude') && old('longitude') ? old('latitude') . ', ' . old('longitude') : __('suggestions.none') }}</span></p>
    </div>

    <div class="suggestion-form-grid">
        <x-form-field for="latitude" :label="__('suggestions.latitude')" :value="old('latitude')" />
        <x-form-field for="longitude" :label="__('suggestions.longitude')" :value="old('longitude')" />
    </div>

    @guest
        <div class="suggestion-user-details">
            <h4 class="suggestion-user-title">{{ __('suggestions.your_details') }}</h4>
            <div class="suggestion-form-grid">
                <x-form-field for="submitted_by_name" :label="__('suggestions.your_name')" :value="old('submitted_by_name')" />
                <x-form-field for="submitted_by_email" :label="__('auth.ui.email')" type="email" :value="old('submitted_by_email')" />
            </div>
        </div>
    @endguest

    <div class="suggestion-actions">
        <button type="submit" class="mk-btn mk-btn-primary mk-btn-lg">{{ $submitText }}</button>
    </div>
</form>
