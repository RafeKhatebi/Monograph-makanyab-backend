<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\HandlesSuggestionValidation;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceSuggestionRequest extends FormRequest
{
    use HandlesSuggestionValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'service_category_id' => 'required|exists:service_categories,id',
        ];

        return $this->handleGuestSubmitterRules(array_merge($rules, $this->suggestionRules()));
    }
}
