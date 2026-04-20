<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $serviceId = $this->route('service')?->id;

        return [
            'name' => 'required|string|max:255',
            'service_category_id' => 'required|exists:service_categories,id',
            'description' => 'required|string',
            'tagline' => 'nullable|string|max:255',
            'phone_1' => 'required|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|string|max:255',
            'address' => 'required|string|max:500',
            'country' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'subdistrict' => 'nullable|string|max:100',
            'village' => 'nullable|string|max:100',
            'rt_rw' => 'nullable|string|max:20',
            'neighborhood' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'nullable|in:open,closed,temporarily_closed',
            'price_level' => 'nullable|in:low,medium,high,luxury',
            'is_published' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
}
