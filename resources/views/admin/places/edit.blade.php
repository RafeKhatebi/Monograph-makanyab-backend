@extends('layouts.admin')

@section('title', 'Edit Place')
@section('page-title', 'Edit Place')

@section('content')
    <div class="card">
        <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
            <h6 style="margin: 0; font-weight: 600;">Edit Place</h6>
            <a href="{{ route('admin.places.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left"></i> Back to Places
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.places.update', $place) }}" method="POST" enctype="multipart/form-data"
                data-prevent-double-submit>
                @csrf
                @method('PUT')

                <div class="admin-form-grid" style="display: grid; gap: 20px;">
                    <div>
                        <label for="name" class="form-label">Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $place->name) }}" required
                            class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <div style="color: var(--color-danger); font-size: var(--font-size-xs); margin-top: 4px;">{{ $message }}</div>
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
                            <div style="color: var(--color-danger); font-size: var(--font-size-xs); margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label for="description" class="form-label">Description *</label>
                        <textarea id="description" name="description" rows="4" required
                            class="form-control @error('description') is-invalid @enderror">{{ old('description', $place->description) }}</textarea>
                        @error('description')
                            <div style="color: var(--color-danger); font-size: var(--font-size-xs); margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="form-label">Address *</label>
                        <input type="text" id="address" name="address" value="{{ old('address', $place->address) }}" required
                            class="form-control @error('address') is-invalid @enderror">
                        @error('address')
                            <div style="color: var(--color-danger); font-size: var(--font-size-xs); margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="phone_1" class="form-label">Phone *</label>
                        <input type="text" id="phone_1" name="phone_1" value="{{ old('phone_1', $place->phone_1) }}" required
                            class="form-control @error('phone_1') is-invalid @enderror">
                        @error('phone_1')
                            <div style="color: var(--color-danger); font-size: var(--font-size-xs); margin-top: 4px;">{{ $message }}</div>
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
                            <div style="color: var(--color-danger); font-size: var(--font-size-xs); margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="province-search" class="form-label">Province *</label>
                        <input type="search" id="province-search" placeholder="Search province" class="form-control" style="margin-bottom: 8px;">
                        <select id="province-select" name="province" required
                            data-selected="{{ old('province', $place->province ?? 'Kabul') }}"
                            class="form-select @error('province') is-invalid @enderror">
                        </select>
                        @error('province')
                            <div style="color: var(--color-danger); font-size: var(--font-size-xs); margin-top: 4px;">{{ $message }}</div>
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
                            <div style="color: var(--color-danger); font-size: var(--font-size-xs); margin-top: 4px;">{{ $message }}</div>
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

                    <div style="grid-column: 1 / -1;">
                        <label class="form-label">Select Exact Location *</label>
                        <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin-bottom: 8px;">
                            Choose country, province and district first, then click on the map to set the exact latitude and longitude.
                        </p>
                        <div id="place-map" data-scroll-wheel="false"
                            style="height: 320px; border: 1px solid var(--color-gray-200); border-radius: var(--radius-md); overflow: hidden;"></div>
                        <p style="font-size: var(--font-size-xs); color: var(--color-gray-500); margin-top: 8px; margin-bottom: 0;">
                            Selected coordinates:
                            <span id="selected-coords">{{ old('latitude', $place->latitude) && old('longitude', $place->longitude) ? old('latitude', $place->latitude) . ', ' . old('longitude', $place->longitude) : ($place->latitude && $place->longitude ? $place->latitude . ', ' . $place->longitude : 'None') }}</span>
                        </p>
                    </div>

                    <div>
                        <label for="latitude" class="form-label">Latitude *</label>
                        <input type="number" step="0.000001" min="-90" max="90" id="latitude" name="latitude"
                            value="{{ old('latitude', $place->latitude) }}" required readonly
                            class="form-control @error('latitude') is-invalid @enderror"
                            style="background-color: var(--color-gray-50);">
                        @error('latitude')
                            <div style="color: var(--color-danger); font-size: var(--font-size-xs); margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="form-label">Longitude *</label>
                        <input type="number" step="0.000001" min="-180" max="180" id="longitude" name="longitude"
                            value="{{ old('longitude', $place->longitude) }}" required readonly
                            class="form-control @error('longitude') is-invalid @enderror"
                            style="background-color: var(--color-gray-50);">
                        @error('longitude')
                            <div style="color: var(--color-danger); font-size: var(--font-size-xs); margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="website" class="form-label">Website</label>
                        <input type="url" id="website" name="website" value="{{ old('website', $place->website) }}"
                            class="form-control @error('website') is-invalid @enderror">
                        @error('website')
                            <div style="color: var(--color-danger); font-size: var(--font-size-xs); margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="grid-column: 1 / -1;" data-media-upload>
                        <label for="images" class="form-label">Add New Images</label>
                        <div data-drop-zone style="border:2px dashed var(--color-gray-300);border-radius:var(--radius-md);padding:18px;text-align:center;">
                        <input type="file" id="images" name="images[]" multiple
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            class="form-control @error('images') is-invalid @enderror">
                            <span style="display:block;margin-top:8px;color:var(--color-gray-500);">Choose images or drag and drop them here</span>
                        </div>
                        <input type="hidden" name="cover_image_index" value="{{ old('cover_image_index', 0) }}" data-cover-index>
                        <div data-image-preview style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;margin-top:12px;"></div>
                        <small style="display:block;margin-top:6px;color:var(--color-gray-500);">JPG, PNG, or WebP. Maximum 2MB each and 10 images total.</small>
                        @error('images')
                            <div style="color: var(--color-danger); font-size: var(--font-size-xs); margin-top: 4px;">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div style="color: var(--color-danger); font-size: var(--font-size-xs); margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    @if ($place->media->where('type', 'image')->isNotEmpty())
                        <div style="grid-column:1 / -1;">
                            <span class="form-label">Current Images</span>
                            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;">
                                @foreach ($place->media->where('type', 'image')->sortBy('sort_order') as $image)
                                    <div style="border:1px solid var(--color-gray-200);border-radius:var(--radius-md);padding:8px;">
                                        <img src="{{ asset('storage/' . $image->file_path) }}" alt="{{ $place->name }}"
                                            style="width:100%;height:100px;object-fit:cover;border-radius:var(--radius-sm);">
                                        <label style="display:block;margin-top:6px;font-size:12px;">
                                            <input type="radio" name="cover_media_id" value="{{ $image->id }}"
                                                @checked(old('cover_media_id', $place->media->firstWhere('is_cover', true)?->id) == $image->id)>
                                            Cover
                                        </label>
                                        <label style="display:block;margin-top:4px;font-size:12px;color:var(--color-danger);">
                                            <input type="checkbox" name="remove_media[]" value="{{ $image->id }}"
                                                @checked(in_array($image->id, old('remove_media', [])))>
                                            Remove when saved
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('remove_media.*')
                                <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <div style="grid-column: 1 / -1; display: flex; gap: 12px; justify-content: flex-end; padding-top: 12px; border-top: 1px solid var(--color-gray-200);">
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
