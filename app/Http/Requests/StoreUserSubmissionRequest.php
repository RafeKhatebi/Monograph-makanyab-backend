<?php

namespace App\Http\Requests;

use App\Enums\PlaceStatus;
use App\Enums\PriceLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $type = $this->input('type', 'place');

        $rules = [
            'type' => ['required', Rule::in(['place', 'service', 'post'])],
            'submit_action' => ['required', Rule::in(['draft', 'send_review'])],
            'name' => ['required_unless:type,post', 'nullable', 'string', 'max:255'],
            'title' => ['required_if:type,post', 'nullable', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['required_unless:type,post', 'nullable', 'string', 'min:20', 'max:2000'],
            'content' => ['required_if:type,post', 'nullable', 'string', 'min:80'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'extra_information' => ['nullable', 'string', 'max:2000'],
        ];

        if ($type === 'place') {
            $rules += $this->placeOrServiceRules('place_categories', 'place_category_id');
        }

        if ($type === 'service') {
            $rules += $this->placeOrServiceRules('service_categories', 'service_category_id');
        }

        if ($type === 'post') {
            $rules['image'] = ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'type' => __('suggestions.submission_type'),
            'submit_action' => __('suggestions.submit_action'),
            'name' => __('suggestions.name'),
            'title' => __('suggestions.title'),
            'place_category_id' => __('suggestions.category'),
            'service_category_id' => __('suggestions.category'),
            'description' => __('suggestions.description'),
            'content' => __('suggestions.content'),
            'province' => __('suggestions.province'),
            'district' => __('suggestions.district'),
            'city' => __('suggestions.city'),
            'address' => __('suggestions.address'),
            'phone_1' => __('suggestions.phone'),
            'price_level' => __('suggestions.price_level'),
            'images' => __('suggestions.images'),
            'image' => __('suggestions.image'),
            'extra_information' => __('suggestions.extra_information'),
        ];
    }

    public function messages(): array
    {
        return [
            'required' => __('suggestions.validation.required'),
            'required_if' => __('suggestions.validation.required'),
            'required_unless' => __('suggestions.validation.required'),
            'exists' => __('suggestions.validation.exists'),
            'image' => __('suggestions.validation.image'),
            'mimes' => __('suggestions.validation.mimes'),
            'max' => __('suggestions.validation.max'),
            'min' => __('suggestions.validation.min'),
            'url' => __('suggestions.validation.url'),
            'images.required' => __('suggestions.validation.images_required'),
            'image.required' => __('suggestions.validation.image_required'),
        ];
    }

    private function placeOrServiceRules(string $categoryTable, string $categoryField): array
    {
        return [
            $categoryField => ['required', "exists:{$categoryTable},id"],
            'phone_1' => ['required', 'string', 'max:20'],
            'phone_2' => ['nullable', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'country' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'subdistrict' => ['nullable', 'string', 'max:100'],
            'village' => ['nullable', 'string', 'max:100'],
            'rt_rw' => ['nullable', 'string', 'max:20'],
            'neighborhood' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'status' => ['nullable', Rule::enum(PlaceStatus::class)],
            'price_level' => ['required', Rule::enum(PriceLevel::class)],
            'images' => ['required', 'array', 'min:1', 'max:6'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }
}
