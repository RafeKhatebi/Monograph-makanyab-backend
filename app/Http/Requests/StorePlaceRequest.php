<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Any authenticated user can create a place
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            // Identity
            'place_category_id' => ['required', 'integer', Rule::exists('place_categories', 'id')],
            'name'              => ['required', 'string', 'max:255'],
            'slug'              => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('places', 'slug')],
            'tagline'           => ['nullable', 'string', 'max:255'],
            'description'       => ['nullable', 'string', 'max:5000'],

            // Contact
            'phone_1'           => ['required', 'string', 'max:20'],
            'phone_2'           => ['nullable', 'string', 'max:20'],
            'whatsapp'          => ['nullable', 'string', 'max:20'],
            'website'           => ['nullable', 'url', 'max:255'],
            'social_links'      => ['nullable', 'array'],
            'social_links.*'    => ['url', 'max:255'],

            // Location
            'address'           => ['required', 'string', 'max:500'],
            'country'           => ['required', 'string', 'max:100'],
            'province'          => ['required', 'string', 'max:100'],
            'city'              => ['required', 'string', 'max:100'],
            'district'          => ['required', 'string', 'max:100'],
            'subdistrict'       => ['nullable', 'string', 'max:100'],
            'village'           => ['nullable', 'string', 'max:100'],
            'rt_rw'             => ['nullable', 'string', 'max:20'],
            'neighborhood'      => ['nullable', 'string', 'max:100'],
            'postal_code'       => ['nullable', 'string', 'max:10'],
            'latitude'          => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'         => ['nullable', 'numeric', 'between:-180,180'],

            // Meta
            'status'            => ['sometimes', 'string', Rule::in(['open', 'closed', 'temporarily_closed'])],
            'price_level'       => ['sometimes', 'string', Rule::in(['low', 'medium', 'high', 'luxury'])],
        ];
    }

    public function messages(): array
    {
        return [
            'place_category_id.exists' => 'The selected category does not exist.',
            'slug.unique'              => 'This slug is already taken. Please choose another.',
            'slug.alpha_dash'          => 'The slug may only contain letters, numbers, dashes, and underscores.',
            'latitude.between'         => 'Latitude must be between -90 and 90.',
            'longitude.between'        => 'Longitude must be between -180 and 180.',
            'website.url'              => 'The website must be a valid URL.',
            'social_links.*.url'       => 'Each social link must be a valid URL.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Auto-assign the authenticated user as the owner
        $this->merge(['user_id' => $this->user()->id]);

        // Auto-generate slug from name if not provided
        if (empty($this->slug) && $this->name) {
            $this->merge(['slug' => \Illuminate\Support\Str::slug($this->name)]);
        }
    }
}
