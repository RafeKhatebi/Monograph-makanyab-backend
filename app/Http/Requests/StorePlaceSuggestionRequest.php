<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\HandlesSuggestionValidation;
use Illuminate\Foundation\Http\FormRequest;

class StorePlaceSuggestionRequest extends FormRequest
{
    use HandlesSuggestionValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'place_category_id' => 'required|exists:place_categories,id',
        ];

        return $this->handleGuestSubmitterRules(array_merge($rules, $this->suggestionRules()));
    }
}
