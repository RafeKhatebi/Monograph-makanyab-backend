<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $categoryId = $this->route('serviceCategory')?->id
            ?? $this->route('service_category')?->id
            ?? $this->route('serviceCategory')
            ?? $this->route('service_category');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('service_categories', 'name')->ignore($categoryId)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('service_categories', 'slug')->ignore($categoryId)],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:service_categories,id', Rule::notIn([$categoryId])],
            'icon_name' => ['nullable', 'string', 'max:255'],
            'color_code' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'has_menu' => ['nullable', 'boolean'],
            'has_booking' => ['nullable', 'boolean'],
            'has_delivery' => ['nullable', 'boolean'],
            'keywords' => ['nullable', 'string'],
            'schema_type' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ];
    }
}
