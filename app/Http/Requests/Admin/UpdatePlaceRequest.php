<?php

namespace App\Http\Requests\Admin;

use App\Enums\PlaceStatus;
use App\Enums\PriceLevel;
use App\Http\Requests\Admin\Concerns\ValidatesPlaceLocationAndImages;
use App\Models\Place;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlaceRequest extends FormRequest
{
    use ValidatesPlaceLocationAndImages;

    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'place_category_id' => [
                'required',
                Rule::exists('place_categories', 'id')->where('is_active', true),
            ],
            'address' => ['required', 'string', 'max:500'],
            'phone_1' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'website' => ['nullable', 'url', 'max:255'],
            'status' => ['nullable', Rule::enum(PlaceStatus::class)],
            'price_level' => ['nullable', Rule::enum(PriceLevel::class)],
            'neighborhood' => ['nullable', 'string', 'max:100'],
            'images' => ['sometimes', 'array', 'max:10'],
            'images.*' => ['required', 'file', 'image', 'mimetypes:image/jpeg,image/png,image/webp', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_media' => ['sometimes', 'array'],
            'remove_media.*' => [
                'integer',
                Rule::exists('media', 'id')->where(
                    fn ($query) => $query
                        ->where('mediable_type', Place::class)
                        ->where('mediable_id', $this->route('place')?->id)
                ),
            ],
            'cover_media_id' => [
                'nullable',
                'integer',
                Rule::exists('media', 'id')->where(
                    fn ($query) => $query
                        ->where('mediable_type', Place::class)
                        ->where('mediable_id', $this->route('place')?->id)
                ),
            ],
            'cover_image_index' => ['nullable', 'integer', 'min:0', 'max:9'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->preparePlaceLocation();
    }
}
