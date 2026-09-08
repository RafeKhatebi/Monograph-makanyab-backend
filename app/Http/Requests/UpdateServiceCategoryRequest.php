<?php

namespace App\Http\Requests;

use App\Models\ServiceCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

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
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('service_categories', 'slug')->ignore($categoryId)],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:service_categories,id', Rule::notIn([$categoryId])],
            'has_menu' => ['nullable', 'boolean'],
            'has_booking' => ['nullable', 'boolean'],
            'has_delivery' => ['nullable', 'boolean'],
            'keywords' => ['nullable', 'string'],
            'schema_type' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if (! $this->filled('parent_id')) {
                    return;
                }

                $routeCategory = $this->route('serviceCategory') ?? $this->route('service_category');
                $category = $routeCategory instanceof ServiceCategory
                    ? $routeCategory
                    : ServiceCategory::find($routeCategory);

                $parent = ServiceCategory::find($this->integer('parent_id'));

                if ($parent?->parent_id !== null) {
                    $validator->errors()->add('parent_id', 'Select a top-level category as the parent.');
                }

                if ($category && $parent && $category->descendantIds()->contains($parent->id)) {
                    $validator->errors()->add('parent_id', 'A category cannot use one of its subcategories as a parent.');
                }
            },
        ];
    }
}
