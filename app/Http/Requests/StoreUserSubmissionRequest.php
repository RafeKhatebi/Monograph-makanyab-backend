<?php

namespace App\Http\Requests;

use App\Enums\PriceLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserSubmissionRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if (in_array($this->input('type'), ['place', 'service'], true)) {
            $this->merge([
                'country' => $this->input('country') ?: 'Afghanistan',
                'city' => $this->input('district') ?: $this->input('city'),
            ]);
        }
    }

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $type = $this->input('type', 'place');
        $isDraft = $this->input('submit_action') === 'draft';

        $rules = [
            'type' => ['required', Rule::in(['place', 'service', 'post'])],
            'submit_action' => ['required', Rule::in(['draft', 'send_review'])],
            'name' => [$type === 'post' ? 'nullable' : 'required', 'nullable', 'string', 'max:255'],
            'title' => ['required_if:type,post', 'nullable', 'string', 'max:255'],
            'description' => [$isDraft || $type === 'post' ? 'nullable' : 'required', 'nullable', 'string', $isDraft ? 'max:2000' : 'min:20', 'max:2000'],
            'content' => [
                $type === 'post' && ! $isDraft ? 'required' : 'nullable',
                'string',
                ...($type === 'post' && ! $isDraft ? ['min:80'] : []),
                'max:20000',
            ],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'extra_information' => ['nullable', 'string', 'max:2000'],
            'cover_image_index' => ['nullable', 'integer', 'min:0', 'max:5'],
        ];

        if ($type === 'place') {
            if (! $isDraft) {
                $rules['name'][] = Rule::unique('place_suggestions', 'name')->where(fn ($query) => $query
                    ->where('place_category_id', $this->input('place_category_id'))
                    ->where('city', $this->input('city'))
                    ->whereIn('suggestion_status', ['pending', 'approved']))
                    ->ignore($this->route('type') === 'place' ? $this->route('submission') : null);
            }
            $rules += $this->placeOrServiceRules('place_categories', 'place_category_id', $isDraft);
        }

        if ($type === 'service') {
            if (! $isDraft) {
                $rules['name'][] = Rule::unique('service_suggestions', 'name')->where(fn ($query) => $query
                    ->where('service_category_id', $this->input('service_category_id'))
                    ->where('city', $this->input('city'))
                    ->whereIn('suggestion_status', ['pending', 'approved']))
                    ->ignore($this->route('type') === 'service' ? $this->route('submission') : null);
            }
            $rules += $this->placeOrServiceRules('service_categories', 'service_category_id', $isDraft);
        }

        if ($type === 'post') {
            $rules['image'] = [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ];
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
            'in' => __('suggestions.validation.exists'),
            'image' => __('suggestions.validation.image'),
            'mimes' => __('suggestions.validation.mimes'),
            'uploaded' => __('suggestions.validation.uploaded'),
            'max' => __('suggestions.validation.max'),
            'images.*.max' => __('suggestions.validation.image_max'),
            'image.max' => __('suggestions.validation.image_max'),
            'min' => __('suggestions.validation.min'),
            'description.min' => __('suggestions.validation.min_characters'),
            'content.min' => __('suggestions.validation.min_characters'),
            'url' => __('suggestions.validation.url'),
            'images.required' => __('suggestions.validation.images_required'),
            'image.required' => __('suggestions.validation.image_required'),
        ];
    }

    private function placeOrServiceRules(string $categoryTable, string $categoryField, bool $isDraft): array
    {
        return [
            $categoryField => [$isDraft ? 'nullable' : 'required', Rule::exists($categoryTable, 'id')->where('is_active', true)],
            'phone_1' => [$isDraft ? 'nullable' : 'required', 'string', 'max:20'],
            'price_level' => [$isDraft ? 'nullable' : 'required', Rule::in(PriceLevel::values())],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'country' => [$isDraft ? 'nullable' : 'required', 'string', 'max:100'],
            'province' => [$isDraft ? 'nullable' : 'required', 'string', 'max:100'],
            'city' => [$isDraft ? 'nullable' : 'required', 'string', 'max:100'],
            'district' => [$isDraft ? 'nullable' : 'required', 'string', 'max:100'],
            'neighborhood' => ['nullable', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'images' => [
                'nullable',
                'array',
                'min:1',
                'max:6',
            ],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }
}
