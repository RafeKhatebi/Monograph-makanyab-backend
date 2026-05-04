@extends('layouts.admin')

@section('title', 'Add Place')
@section('page-title', 'Add Place')

@section('content')
    <div class="bg-light rounded h-100 p-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
            <h6 class="mb-0">Add New Place</h6>
            <a href="{{ route('admin.places.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left me-1"></i>Back to Places
            </a>
        </div>

        <form action="{{ route('admin.places.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="place_category_id" class="form-label">Category *</label>
                    <select id="place_category_id" name="place_category_id" required
                        class="form-select @error('place_category_id') is-invalid @enderror">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('place_category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('place_category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="address" class="form-label">Address *</label>
                    <input type="text" id="address" name="address" value="{{ old('address') }}" required
                        class="form-control @error('address') is-invalid @enderror">
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="phone_1" class="form-label">Phone *</label>
                    <input type="text" id="phone_1" name="phone_1" value="{{ old('phone_1') }}" required
                        class="form-control @error('phone_1') is-invalid @enderror">
                    @error('phone_1')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="website" class="form-label">Website</label>
                    <input type="url" id="website" name="website" value="{{ old('website') }}"
                        class="form-control @error('website') is-invalid @enderror">
                    @error('website')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="country" class="form-label">Country *</label>
                    <select id="country" name="country" required
                        class="form-select @error('country') is-invalid @enderror">
                        <option value="Afghanistan"
                            {{ old('country', 'Afghanistan') === 'Afghanistan' ? 'selected' : '' }}>
                            Afghanistan
                        </option>
                    </select>
                    @error('country')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="province-search" class="form-label">Province *</label>
                    <input type="search" id="province-search" placeholder="Search province" class="form-control mb-2">
                    <select id="province-select" name="province" required data-selected="{{ old('province', 'Kabul') }}"
                        class="form-select @error('province') is-invalid @enderror">
                    </select>
                    @error('province')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="district-select" class="form-label">District *</label>
                    <select id="district-select" name="district" required disabled data-selected="{{ old('district') }}"
                        class="form-select @error('district') is-invalid @enderror">
                        <option value="">Select province first</option>
                    </select>
                    @error('district')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="description" class="form-label">Description *</label>
                    <textarea id="description" name="description" rows="3" required
                        class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch mt-2">
                        <input type="checkbox" class="form-check-input" id="is_verified" name="is_verified" value="1"
                            {{ old('is_verified') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_verified">Verified</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-check form-switch mt-2">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
                <div class="col-12">
                    <label for="images" class="form-label">Images</label>
                    <input type="file" id="images" name="images[]" multiple accept="image/*"
                        class="form-control @error('images') is-invalid @enderror">
                    @error('images')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Select Exact Location *</label>
                    <p class="small text-muted mb-2">Choose country, province and district first, then click on the map to
                        set
                        the exact latitude and longitude.</p>
                    <div id="place-map" data-scroll-wheel="false" class="border rounded overflow-hidden"
                        style="height: 320px;"></div>
                    <p class="small text-muted mt-2 mb-0">Selected coordinates:
                        <span
                            id="selected-coords">{{ old('latitude') && old('longitude') ? old('latitude') . ', ' . old('longitude') : 'None' }}</span>
                    </p>
                </div>

                <div class="col-md-6">
                    <label for="latitude" class="form-label">Latitude *</label>
                    <input type="number" step="0.000001" min="-90" max="90" id="latitude" name="latitude"
                        value="{{ old('latitude') }}" required readonly
                        class="form-control bg-light @error('latitude') is-invalid @enderror">
                    @error('latitude')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="longitude" class="form-label">Longitude *</label>
                    <input type="number" step="0.000001" min="-180" max="180" id="longitude"
                        name="longitude" value="{{ old('longitude') }}" required readonly
                        class="form-control bg-light @error('longitude') is-invalid @enderror">
                    @error('longitude')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 d-flex justify-content-end gap-2 pt-2">
                    <a href="{{ route('admin.places.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Place</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="{{ asset('assets/js/places-search-local.js') }}"></script>
@endpush
