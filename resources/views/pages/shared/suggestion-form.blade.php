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
        <x-form-field for="city" label="City" :value="old('city')" />
        <x-form-field for="province" label="Province" :value="old('province')" />
    </div>

    <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <x-form-field for="district" label="District" :value="old('district')" />
        <x-form-field for="address" label="Address" :value="old('address')" />
    </div>

    <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <x-form-field for="phone_1" label="Phone" :value="old('phone_1')" />
        <x-form-field for="whatsapp" label="WhatsApp" :value="old('whatsapp')" />
    </div>

    <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <x-form-field for="website" label="Website" :value="old('website')" />
        <div>
            <x-input-label for="price_level" value="Price Level" />
            <x-select-input id="price_level" name="price_level" :options="$priceOptions" placeholder="Select price level" />
            <x-input-error :messages="$errors->get('price_level')" class="mt-2" />
        </div>
    </div>

    <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div>
            <x-input-label for="status" value="Status" />
            <x-select-input id="status" name="status" :options="$statusOptions" placeholder="Choose status" />
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
        <x-form-field for="tagline" label="Tagline" :value="old('tagline')" />
    </div>

    <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div>
            <x-input-label for="description" value="Description" />
            <x-textarea id="description" name="description" rows="6"
                placeholder="Describe the place or service">{{ old('description') }}</x-textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <x-form-field for="latitude" label="Latitude" :value="old('latitude')" />
            <x-form-field for="longitude" label="Longitude" :value="old('longitude')" />
        </div>
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
