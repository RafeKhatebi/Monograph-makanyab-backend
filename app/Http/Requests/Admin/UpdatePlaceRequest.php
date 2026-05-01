<?php

namespace App\Http\Requests\Admin;

use App\Enums\PlaceStatus;
use App\Enums\PriceLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'place_category_id' => ['required', 'exists:place_categories,id'],
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
            'images' => ['sometimes', 'array'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('city') && $this->filled('province')) {
            $this->merge(['city' => $this->input('province')]);
        }
    }
}
