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

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <x-form-field for="name" :label="__('suggestions.name')" :value="old('name')" />

        <div>
            <x-input-label for="{{ $categoryField }}" :value="$categoryLabel ?: __('suggestions.category')" />
            <x-select-input id="{{ $categoryField }}" name="{{ $categoryField }}" :options="$categories"
                placeholder="{{ __('suggestions.select_category') }}" />
            <x-input-error :messages="$errors->get($categoryField)" class="mt-2" />
        </div>
    </div>

    <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
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

    <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
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

    <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <x-form-field for="address" :label="__('suggestions.address')" :value="old('address')" />
        <x-form-field for="website" :label="__('suggestions.website')" :value="old('website')" />
    </div>

    <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <x-form-field for="phone_1" :label="__('suggestions.phone')" :value="old('phone_1')" />
        <x-form-field for="whatsapp" :label="__('suggestions.whatsapp')" :value="old('whatsapp')" />
    </div>

    <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div>
            <x-input-label for="price_level" :value="__('suggestions.price_level')" />
            <x-select-input id="price_level" name="price_level" :options="$priceOptions" placeholder="{{ __('suggestions.select_price') }}" />
            <x-input-error :messages="$errors->get('price_level')" class="mt-2" />
        </div>
    </div>

    <div style="margin-top:16px;display:grid;grid-template-columns:1fr;gap:16px;">
        <x-form-field for="tagline" :label="__('suggestions.tagline')" :value="old('tagline')" />
    </div>

    <div style="margin-top:16px;">
        <x-input-label for="description" :value="__('suggestions.description')" />
        <x-textarea id="description" name="description" rows="6"
            placeholder="{{ __('suggestions.description_placeholder') }}">{{ old('description') }}</x-textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div style="margin-top:20px;">
        <x-input-label :value="__('suggestions.exact_location')" />
        <p style="margin-top:6px;margin-bottom:12px;color:#6B7280;font-size:14px;">{{ __('suggestions.map_help') }}</p>
        <div id="suggestion-map" style="height:320px;border:1px solid #E5E7EB;border-radius:14px;overflow:hidden;background:#f9fafb;"></div>
        <p style="margin-top:10px;color:#6B7280;font-size:14px;">{{ __('suggestions.coordinates') }}: <span id="selected-coords">{{ old('latitude') && old('longitude') ? old('latitude') . ', ' . old('longitude') : __('suggestions.none') }}</span></p>
    </div>

    <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <x-form-field for="latitude" :label="__('suggestions.latitude')" :value="old('latitude')" />
        <x-form-field for="longitude" :label="__('suggestions.longitude')" :value="old('longitude')" />
    </div>

    @guest
        <div style="margin-top:24px;padding:20px;border:1px solid #E5E7EB;border-radius:14px;background:#F8FAFC;">
            <h4 style="font-size:18px;font-weight:700;color:#111827;margin-bottom:12px;">{{ __('suggestions.your_details') }}</h4>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <x-form-field for="submitted_by_name" :label="__('suggestions.your_name')" :value="old('submitted_by_name')" />
                <x-form-field for="submitted_by_email" :label="__('auth.ui.email')" type="email" :value="old('submitted_by_email')" />
            </div>
        </div>
    @endguest

    <div style="margin-top:28px;text-align:center;">
        <button type="submit"
            style="background:#10B981;color:#fff;padding:14px 28px;border:none;border-radius:12px;font-weight:700;font-size:15px;cursor:pointer;">{{ $submitText }}</button>
    </div>
</form>
