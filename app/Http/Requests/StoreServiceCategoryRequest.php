<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:service_categories,name',
            'slug' => 'nullable|string|max:255|unique:service_categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:service_categories,id',
            'icon_name' => 'nullable|string|max:255',
            'color_code' => 'nullable|string|max:7',
            'has_menu' => 'nullable|boolean',
            'has_booking' => 'nullable|boolean',
            'has_delivery' => 'nullable|boolean',
            'keywords' => 'nullable|string',
            'schema_type' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ];
    }
}
