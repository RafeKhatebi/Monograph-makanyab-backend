@extends('layouts.admin')

@section('title', 'Add Place')
@section('page-title', 'Add Place')

@section('content')
    <section class="card" aria-label="Add New Place">
        <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
            <h2 style="margin: 0; font-weight: 600; font-size: var(--font-size-base);">Add New Place</h2>
            <a href="{{ route('admin.places.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Places
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.places.store') }}" method="POST" enctype="multipart/form-data"
                data-prevent-double-submit novalidate>
                @csrf

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label for="name" class="form-label">Name <span aria-hidden="true">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="form-control @error('name') is-invalid @enderror"
                            aria-describedby="@error('name') name-error @enderror"
                            aria-required="true">
                        @error('name')
                            <div id="name-error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="place_category_id" class="form-label">Category <span aria-hidden="true">*</span></label>
                        <select id="place_category_id" name="place_category_id" required
                            class="form-select @error('place_category_id') is-invalid @enderror"
                            aria-describedby="@error('place_category_id') category-error @enderror"
                            aria-required="true">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('place_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('place_category_id')
                            <div id="category-error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="form-label">Address <span aria-hidden="true">*</span></label>
                        <input type="text" id="address" name="address" value="{{ old('address') }}" required
                            class="form-control @error('address') is-invalid @enderror"
                            aria-describedby="@error('address') address-error @enderror"
                            aria-required="true">
                        @error('address')
                            <div id="address-error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="phone_1" class="form-label">Phone <span aria-hidden="true">*</span></label>
                        <input type="text" id="phone_1" name="phone_1" value="{{ old('phone_1') }}" required
                            class="form-control @error('phone_1') is-invalid @enderror"
                            aria-describedby="@error('phone_1') phone-error @enderror"
                            aria-required="true">
                        @error('phone_1')
                            <div id="phone-error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="website" class="form-label">Website</label>
                        <input type="url" id="website" name="website" value="{{ old('website') }}"
                            class="form-control @error('website') is-invalid @enderror"
                            aria-describedby="@error('website') website-error @enderror">
                        @error('website')
                            <div id="website-error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="country" class="form-label">Country <span aria-hidden="true">*</span></label>
                        <select id="country" name="country" required
                            class="form-select @error('country') is-invalid @enderror"
                            aria-required="true">
                            <option value="Afghanistan"
                                {{ old('country', 'Afghanistan') === 'Afghanistan' ? 'selected' : '' }}>
                                Afghanistan
                            </option>
                        </select>
                        @error('country')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="province" class="form-label">Province <span aria-hidden="true">*</span></label>
                        <input type="search" id="province-search" placeholder="Search province"
                            class="form-control" style="margin-bottom: 8px;" autocomplete="off">
                        <select id="province-select" name="province" required
                            data-selected="{{ old('province', 'Kabul') }}"
                            class="form-control @error('province') is-invalid @enderror"
                            aria-required="true">
                            <option value="">Select Province</option>
                            @foreach (array_keys($locations) as $province)
                                <option value="{{ $province }}" @selected(old('province', 'Kabul') === $province)>
                                    {{ $province }}
                                </option>
                            @endforeach
                        </select>
                        @error('province')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="district-select" class="form-label">District <span aria-hidden="true">*</span></label>
                        <select id="district-select" name="district" required
                            data-selected="{{ old('district') }}"
                            class="form-control @error('district') is-invalid @enderror"
                            aria-required="true">
                            <option value="">Select province first</option>
                        </select>
                        @error('district')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="city" class="form-label">City</label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}"
                            placeholder="Defaults to the selected district"
                            class="form-control @error('city') is-invalid @enderror">
                        @error('city')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="neighborhood" class="form-label">Area / Neighborhood</label>
                        <input type="text" id="neighborhood" name="neighborhood" value="{{ old('neighborhood') }}"
                            class="form-control @error('neighborhood') is-invalid @enderror">
                        @error('neighborhood')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label for="description" class="form-label">Description <span aria-hidden="true">*</span></label>
                        <textarea id="description" name="description" rows="3" required
                            class="form-control @error('description') is-invalid @enderror"
                            aria-describedby="@error('description') description-error @enderror"
                            aria-required="true">{{ old('description') }}</textarea>
                        @error('description')
                            <div id="description-error" class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="grid-column: 1 / -1;" data-media-upload>
                        <label for="images" class="form-label">Images</label>
                        <div data-drop-zone style="border: 2px dashed var(--color-gray-300); border-radius: var(--radius-md); padding: 18px; text-align: center;">
                        <input type="file" id="images" name="images[]" multiple accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            class="form-control @error('images') is-invalid @enderror"
                            aria-describedby="images-help">
                            <span style="display:block;margin-top:8px;color:var(--color-gray-500);">Choose images or drag and drop them here</span>
                        </div>
                        <input type="hidden" name="cover_image_index" value="{{ old('cover_image_index', 0) }}" data-cover-index>
                        <div data-image-preview style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;margin-top:12px;"></div>
                        <div id="images-help" style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin-top: 4px;">
                            JPG, PNG, or WebP. Maximum 2MB each and 10 images total. Select a preview to make it the cover.
                        </div>
                        @error('images')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <fieldset>
                            <legend style="font-size: var(--font-size-sm); font-weight: var(--font-weight-medium); color: var(--color-gray-700); margin-bottom: 8px;">Location</legend>
                            <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin-bottom: 12px;">
                                Click on the map to set the exact latitude and longitude.
                            </p>
                            <div id="place-map" data-scroll-wheel="false"
                                style="height: 320px; border: 1px solid var(--color-gray-200); border-radius: var(--radius-md); overflow: hidden;"
                                role="application"
                                aria-label="Map for selecting location"></div>
                            <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin-top: 8px; margin-bottom: 0;">
                                Selected coordinates:
                                <span id="selected-coords">{{ old('latitude') && old('longitude') ? old('latitude') . ', ' . old('longitude') : 'None' }}</span>
                            </p>
                        </fieldset>
                    </div>

                    <div>
                        <label for="latitude" class="form-label">Latitude <span aria-hidden="true">*</span></label>
                        <input type="number" step="0.000001" min="-90" max="90" id="latitude" name="latitude"
                            value="{{ old('latitude') }}" required readonly
                            class="form-control @error('latitude') is-invalid @enderror"
                            style="background-color: var(--color-gray-50);"
                            aria-required="true">
                        @error('latitude')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="form-label">Longitude <span aria-hidden="true">*</span></label>
                        <input type="number" step="0.000001" min="-180" max="180" id="longitude"
                            name="longitude" value="{{ old('longitude') }}" required readonly
                            class="form-control @error('longitude') is-invalid @enderror"
                            style="background-color: var(--color-gray-50);"
                            aria-required="true">
                        @error('longitude')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="grid-column: 1 / -1; display: flex; gap: 12px; justify-content: flex-end; padding-top: 12px; border-top: 1px solid var(--color-gray-200);">
                        <a href="{{ route('admin.places.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary" data-submit-button data-loading-text="Creating…">Create Place</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <style>
        @media (max-width: 767px) {
            div[style*="grid-template-columns: 1fr 1fr"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
@endpush

@push('scripts')
    <script type="application/json" id="place-location-data">@json($locations)</script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="{{ asset('assets/js/places-search-local.js') }}"></script>
    <script src="{{ asset('assets/js/media-upload.js') }}"></script>
@endpush
