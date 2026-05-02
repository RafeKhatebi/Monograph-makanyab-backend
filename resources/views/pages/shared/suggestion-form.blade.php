@php
    use App\Enums\PlaceStatus;
    use App\Enums\PriceLevel;

    $statusOptions = [
        PlaceStatus::Open->value => PlaceStatus::Open->label(),
        PlaceStatus::Closed->value => PlaceStatus::Closed->label(),
        PlaceStatus::TemporarilyClosed->value => PlaceStatus::TemporarilyClosed->label(),
    ];

    $priceOptions = [
        PriceLevel::Low->value => 'Low',
        PriceLevel::Medium->value => 'Medium',
        PriceLevel::High->value => 'High',
        PriceLevel::Luxury->value => 'Luxury',
    ];
@endphp

<form action="{{ $action }}" method="POST">
    @csrf

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <x-form-field for="name" label="Name" :value="old('name')" />

        <div>
            <x-input-label for="{{ $categoryField }}" :value="$categoryLabel" />
            <x-select-input id="{{ $categoryField }}" name="{{ $categoryField }}" :options="$categories"
                placeholder="Select category" />
            <x-input-error :messages="$errors->get($categoryField)" class="mt-2" />
        </div>
    </div>

    <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div>
            <x-input-label for="province-search" value="Search Province" />
            <input id="province-search" type="search" value="{{ old('province') }}" placeholder="Type province name"
                class="form-control" />
        </div>

        <div>
            <x-input-label for="province-select" value="Province" />
            <select id="province-select" name="province" data-selected="{{ old('province') }}"
                class="form-control">
                <option value="">Select province</option>
            </select>
            <x-input-error :messages="$errors->get('province')" class="mt-2" />
        </div>
    </div>

    <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div>
            <x-input-label for="district-select" value="District" />
            <select id="district-select" name="district" data-selected="{{ old('district') }}"
                class="form-control" disabled>
                <option value="">Select province first</option>
            </select>
            <x-input-error :messages="$errors->get('district')" class="mt-2" />
        </div>

        <x-form-field for="city" label="City" :value="old('city')" />
    </div>

    <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <x-form-field for="address" label="Address" :value="old('address')" />
        <x-form-field for="website" label="Website" :value="old('website')" />
    </div>

    <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <x-form-field for="phone_1" label="Phone" :value="old('phone_1')" />
        <x-form-field for="whatsapp" label="WhatsApp" :value="old('whatsapp')" />
    </div>

    <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div>
            <x-input-label for="price_level" value="Price Level" />
            <x-select-input id="price_level" name="price_level" :options="$priceOptions" placeholder="Select price level" />
            <x-input-error :messages="$errors->get('price_level')" class="mt-2" />
        </div>
    </div>

    <div style="margin-top:16px;display:grid;grid-template-columns:1fr;gap:16px;">
        <x-form-field for="tagline" label="Tagline" :value="old('tagline')" />
    </div>

    <div style="margin-top:16px;">
        <x-input-label for="description" value="Description" />
        <x-textarea id="description" name="description" rows="6"
            placeholder="Describe the place or service">{{ old('description') }}</x-textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div style="margin-top:20px;">
        <x-input-label value="Select Exact Location" />
        <p style="margin-top:6px;margin-bottom:12px;color:#6B7280;font-size:14px;">Choose a province and district, then click on the map to set the exact latitude and longitude for this suggestion.</p>
        <div id="suggestion-map" style="height:320px;border:1px solid #E5E7EB;border-radius:14px;overflow:hidden;background:#f9fafb;"></div>
        <p style="margin-top:10px;color:#6B7280;font-size:14px;">Selected coordinates: <span id="selected-coords">{{ old('latitude') && old('longitude') ? old('latitude') . ', ' . old('longitude') : 'None' }}</span></p>
    </div>

    <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <x-form-field for="latitude" label="Latitude" :value="old('latitude')" />
        <x-form-field for="longitude" label="Longitude" :value="old('longitude')" />
    </div>

    @guest
        <div style="margin-top:24px;padding:20px;border:1px solid #E5E7EB;border-radius:14px;background:#F8FAFC;">
            <h4 style="font-size:18px;font-weight:700;color:#111827;margin-bottom:12px;">Your contact details</h4>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <x-form-field for="submitted_by_name" label="Your Name" :value="old('submitted_by_name')" />
                <x-form-field for="submitted_by_email" label="Email" type="email" :value="old('submitted_by_email')" />
            </div>
        </div>
    @endguest

    <div style="margin-top:28px;text-align:center;">
        <button type="submit"
            style="background:#10B981;color:#fff;padding:14px 28px;border:none;border-radius:12px;font-weight:700;font-size:15px;cursor:pointer;">{{ $submitText }}</button>
    </div>
</form>
