<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\HandlesSuggestionValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('service_suggestions', 'name')->where(fn ($query) => $query
                    ->where('service_category_id', $this->input('service_category_id'))
                    ->where('city', $this->input('city'))
                    ->whereIn('suggestion_status', ['pending', 'approved'])),
            ],
        ];

        return $this->handleGuestSubmitterRules(array_merge($this->suggestionRules(), $rules));
    }
}
