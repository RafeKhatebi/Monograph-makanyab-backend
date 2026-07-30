@extends('layouts.admin')

@section('title', 'Edit Place')
@section('page-title', 'Edit Place')

@section('content')
    <div class="card">
        <div class="card-header admin-card-header">
            <h6 class="admin-card-title">Edit Place</h6>
            <a href="{{ route('admin.places.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left"></i> Back to Places
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.places.update', $place) }}" method="POST" enctype="multipart/form-data"
                data-prevent-double-submit>
                @csrf
                @method('PUT')

                <div class="admin-form-grid">
                    <div>
                        <label for="name" class="form-label">Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $place->name) }}" required
                            class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="place_category_id" class="form-label">Category *</label>
                        <select id="place_category_id" name="place_category_id" required
                            class="form-select @error('place_category_id') is-invalid @enderror">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('place_category_id', $place->place_category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('place_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-full-span">
                        <label for="description" class="form-label">Description *</label>
                        <textarea id="description" name="description" rows="4" required
                            class="form-control @error('description') is-invalid @enderror">{{ old('description', $place->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="form-label">Address *</label>
                        <input type="text" id="address" name="address" value="{{ old('address', $place->address) }}" required
                            class="form-control @error('address') is-invalid @enderror">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="phone_1" class="form-label">Phone *</label>
                        <input type="text" id="phone_1" name="phone_1" value="{{ old('phone_1', $place->phone_1) }}" required
                            class="form-control @error('phone_1') is-invalid @enderror">
                        @error('phone_1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="country" class="form-label">Country *</label>
                        <select id="country" name="country" required class="form-select @error('country') is-invalid @enderror">
                            <option value="Afghanistan"
                                {{ old('country', $place->country ?? 'Afghanistan') === 'Afghanistan' ? 'selected' : '' }}>
                                Afghanistan
                            </option>
                        </select>
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="province-search" class="form-label">Province *</label>
                        <input type="search" id="province-search" placeholder="Search province" class="form-control admin-form-inline-gap">
                        <select id="province-select" name="province" required
                            data-selected="{{ old('province', $place->province ?? 'Kabul') }}"
                            class="form-select @error('province') is-invalid @enderror">
                        </select>
                        @error('province')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="district-select" class="form-label">District *</label>
                        <select id="district-select" name="district" required disabled
                            data-selected="{{ old('district', $place->district) }}"
                            class="form-select @error('district') is-invalid @enderror">
                            <option value="">Select province first</option>
                        </select>
                        @error('district')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="city" class="form-label">City</label>
                        <input type="text" id="city" name="city" value="{{ old('city', $place->city) }}"
                            placeholder="Defaults to the selected district"
                            class="form-control @error('city') is-invalid @enderror">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="neighborhood" class="form-label">Area / Neighborhood</label>
                        <input type="text" id="neighborhood" name="neighborhood"
                            value="{{ old('neighborhood', $place->neighborhood) }}"
                            class="form-control @error('neighborhood') is-invalid @enderror">
                        @error('neighborhood')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-full-span">
                        <label class="form-label">Select Exact Location *</label>
                        <p class="admin-help-text admin-help-text--block">
                            Choose country, province and district first, then click on the map to set the exact latitude and longitude.
                        </p>
                        <div id="place-map" data-scroll-wheel="false"
                            class="admin-map"></div>
                        <p class="admin-help-text">
                            Selected coordinates:
                            <span id="selected-coords">{{ old('latitude', $place->latitude) && old('longitude', $place->longitude) ? old('latitude', $place->latitude) . ', ' . old('longitude', $place->longitude) : ($place->latitude && $place->longitude ? $place->latitude . ', ' . $place->longitude : 'None') }}</span>
                        </p>
                    </div>

                    <div>
                        <label for="latitude" class="form-label">Latitude *</label>
                        <input type="number" step="0.000001" min="-90" max="90" id="latitude" name="latitude"
                            value="{{ old('latitude', $place->latitude) }}" required readonly
                            class="form-control @error('latitude') is-invalid @enderror admin-readonly">
                        @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="form-label">Longitude *</label>
                        <input type="number" step="0.000001" min="-180" max="180" id="longitude" name="longitude"
                            value="{{ old('longitude', $place->longitude) }}" required readonly
                            class="form-control @error('longitude') is-invalid @enderror admin-readonly">
                        @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="website" class="form-label">Website</label>
                        <input type="url" id="website" name="website" value="{{ old('website', $place->website) }}"
                            class="form-control @error('website') is-invalid @enderror">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-full-span" data-media-upload>
                        <label for="images" class="form-label">Add New Images</label>
                        <div data-drop-zone class="admin-drop-zone">
                        <input type="file" id="images" name="images[]" multiple
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            class="form-control @error('images') is-invalid @enderror">
                            <span class="admin-help-text">Choose images or drag and drop them here</span>
                        </div>
                        <input type="hidden" name="cover_image_index" value="{{ old('cover_image_index', 0) }}" data-cover-index>
                        <div data-image-preview class="admin-image-preview-grid"></div>
                        <small class="admin-help-text">JPG, PNG, or WebP. Maximum 2MB each and 10 images total.</small>
                        @error('images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if ($place->media->where('type', 'image')->isNotEmpty())
                        <div class="admin-full-span">
                            <span class="form-label">Current Images</span>
                            <div class="admin-current-image-grid">
                                @foreach ($place->media->where('type', 'image')->sortBy('sort_order') as $image)
                                    <div class="admin-current-image-card">
                                        <img src="{{ asset('storage/' . $image->file_path) }}" alt="{{ $place->name }}"
                                            class="admin-current-image">
                                        <label class="admin-checkbox-label">
                                            <input type="radio" name="cover_media_id" value="{{ $image->id }}"
                                                @checked(old('cover_media_id', $place->media->firstWhere('is_cover', true)?->id) == $image->id)>
                                            Cover
                                        </label>
                                        <label class="admin-checkbox-label admin-checkbox-label--danger">
                                            <input type="checkbox" name="remove_media[]" value="{{ $image->id }}"
                                                @checked(in_array($image->id, old('remove_media', [])))>
                                            Remove when saved
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('remove_media.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <div class="admin-full-span admin-form-actions">
                        <a href="{{ route('admin.places.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary" data-submit-button data-loading-text="Updating…">Update Place</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
