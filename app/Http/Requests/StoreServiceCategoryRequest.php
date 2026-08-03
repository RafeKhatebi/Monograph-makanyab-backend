<?php

namespace App\Http\Requests;

use App\Models\ServiceCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreServiceCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('service_categories', 'name')],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('service_categories', 'slug')],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:service_categories,id'],
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

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if (! $this->filled('parent_id')) {
                    return;
                }

                $parent = ServiceCategory::find($this->integer('parent_id'));

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
