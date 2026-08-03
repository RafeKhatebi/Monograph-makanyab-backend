<?php

namespace App\Http\Requests;

use App\Enums\PlaceStatus;
use App\Enums\PriceLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return ($this->user()?->isAdmin() || $this->user()?->isOwner()) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'service_category_id' => [
                'required',
                Rule::exists('service_categories', 'id')->where('is_active', true),
            ],
            'description' => 'required|string',
            'tagline' => 'nullable|string|max:255',
            'phone_1' => 'required|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|string|max:255',
            'address' => 'required|string|max:500',
            'country' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'subdistrict' => 'nullable|string|max:100',
            'village' => 'nullable|string|max:100',
            'rt_rw' => 'nullable|string|max:20',
            'neighborhood' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => ['nullable', Rule::enum(PlaceStatus::class)],
            'price_level' => ['nullable', Rule::enum(PriceLevel::class)],
            'is_active' => 'nullable|boolean',
            'images' => 'sometimes|array|max:10',
            'images.*' => 'required|file|image|mimetypes:image/jpeg,image/png,image/webp|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_image_index' => 'nullable|integer|min:0|max:9',
        ];
    }
}
