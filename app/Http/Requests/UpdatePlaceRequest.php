<?php

namespace App\Http\Requests;

use App\Models\Place;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Place $place */
        $place = $this->route('place');

        // Only the place owner or an admin can update
        return $this->user()?->id === $place->user_id
            || $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        $placeId = $this->route('place')?->id;

        return [
            // Identity
            'place_category_id' => ['sometimes', 'integer', Rule::exists('place_categories', 'id')],
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255', 'alpha_dash', Rule::unique('places', 'slug')->ignore($placeId)],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],

            // Contact
            'phone_1' => ['sometimes', 'string', 'max:20'],
            'phone_2' => ['nullable', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'website' => ['nullable', 'url', 'max:255'],
            'social_links' => ['nullable', 'array'],
            'social_links.*' => ['url', 'max:255'],

            // Location
            'address' => ['sometimes', 'string', 'max:500'],
            'country' => ['sometimes', 'string', 'max:100'],
            'province' => ['sometimes', 'string', 'max:100'],
            'city' => ['sometimes', 'string', 'max:100'],
            'district' => ['sometimes', 'string', 'max:100'],
            'subdistrict' => ['nullable', 'string', 'max:100'],
            'village' => ['nullable', 'string', 'max:100'],
            'rt_rw' => ['nullable', 'string', 'max:20'],
            'neighborhood' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],

            // Meta — owner can change status/price, only admin can verify
            'status' => ['sometimes', 'string', Rule::in(['open', 'closed', 'temporarily_closed'])],
            'price_level' => ['sometimes', 'string', Rule::in(['low', 'medium', 'high', 'luxury'])],
            'is_active' => ['sometimes', 'boolean'],
            'is_verified' => ['sometimes', 'boolean', Rule::when(
                $this->user()?->role !== 'admin',
                ['prohibited'] // only admins can set is_verified
            )],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.unique' => 'This slug is already taken by another place.',
            'slug.alpha_dash' => 'The slug may only contain letters, numbers, dashes, and underscores.',
            'latitude.between' => 'Latitude must be between -90 and 90.',
            'longitude.between' => 'Longitude must be between -180 and 180.',
            'is_verified.prohibited' => 'Only admins can verify a place.',
        ];
    }
}
