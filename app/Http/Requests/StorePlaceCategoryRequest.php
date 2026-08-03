<?php

namespace App\Http\Requests;

use App\Models\PlaceCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StorePlaceCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'integer', Rule::exists('place_categories', 'id')],
            'name' => ['required', 'string', 'max:255', Rule::unique('place_categories', 'name')],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('place_categories', 'slug')],
            'icon_name' => ['nullable', 'string', 'max:100'],
            'color_code' => ['sometimes', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'has_menu' => ['sometimes', 'boolean'],
            'has_booking' => ['sometimes', 'boolean'],
            'has_delivery' => ['sometimes', 'boolean'],
            'keywords' => ['nullable', 'string', 'max:1000'],
            'schema_type' => ['nullable', 'string', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0', 'max:65535'],
        ];
    }

    public function messages(): array
    {
        return [
            'color_code.regex' => 'color_code must be a valid hex color (e.g. #3b82f6).',
            'parent_id.exists' => 'The selected parent category does not exist.',
            'name.unique' => 'A category with this name already exists.',
            'slug.unique' => 'A category with this slug already exists.',
            'slug.alpha_dash' => 'The slug may only contain letters, numbers, dashes, and underscores.',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if (! $this->filled('parent_id')) {
                    return;
                }

                $parent = PlaceCategory::find($this->integer('parent_id'));

                if ($parent?->parent_id !== null) {
                    $validator->errors()->add('parent_id', 'Select a top-level category as the parent.');
                }
            },
        ];
    }

    protected function prepareForValidation(): void
    {
        if (empty($this->slug) && $this->name) {
            $this->merge(['slug' => Str::slug($this->name)]);
        }
    }
}
