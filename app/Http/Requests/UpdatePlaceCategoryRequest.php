<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlaceCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        $categoryId = $this->route('place_category')?->id
            ?? $this->route('place_category');

        return [
            'parent_id'    => ['nullable', 'integer', Rule::exists('place_categories', 'id')],
            'name'         => ['sometimes', 'string', 'max:255', Rule::unique('place_categories', 'name')->ignore($categoryId)],
            'slug'         => ['sometimes', 'string', 'max:255', 'alpha_dash', Rule::unique('place_categories', 'slug')->ignore($categoryId)],
            'icon_name'    => ['nullable', 'string', 'max:100'],
            'color_code'   => ['sometimes', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'has_menu'     => ['sometimes', 'boolean'],
            'has_booking'  => ['sometimes', 'boolean'],
            'has_delivery' => ['sometimes', 'boolean'],
            'keywords'     => ['nullable', 'string', 'max:1000'],
            'schema_type'  => ['nullable', 'string', 'max:100'],
            'is_active'    => ['sometimes', 'boolean'],
            'sort_order'   => ['sometimes', 'integer', 'min:0', 'max:65535'],
        ];
    }

    public function messages(): array
    {
        return [
            'color_code.regex' => 'color_code must be a valid hex color (e.g. #3b82f6).',
            'parent_id.exists' => 'The selected parent category does not exist.',
            'name.unique'      => 'A category with this name already exists.',
            'slug.unique'      => 'A category with this slug already exists.',
            'slug.alpha_dash'  => 'The slug may only contain letters, numbers, dashes, and underscores.',
        ];
    }
}
