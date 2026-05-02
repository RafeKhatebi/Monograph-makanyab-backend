<?php

namespace App\Http\Requests\Concerns;

use App\Enums\PlaceStatus;
use App\Enums\PriceLevel;
use Illuminate\Validation\Rule;

trait HandlesSuggestionValidation
{
    public function suggestionRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'phone_1' => 'required|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
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
            'status' => ['nullable', Rule::enum(PlaceStatus::class)],
            'price_level' => ['required', Rule::enum(PriceLevel::class)],
        ];
    }

    protected function handleGuestSubmitterRules(array $rules): array
    {
        if (! auth()->check()) {
            $rules['submitted_by_name'] = 'required|string|max:255';
            $rules['submitted_by_email'] = 'required|email|max:255';
        }

        return $rules;
    }
}
