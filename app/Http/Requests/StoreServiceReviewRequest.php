<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'service_id' => [
                'required',
                'uuid',
                Rule::exists('services', 'id')->whereNull('deleted_at')->where('is_active', true),
                Rule::unique('reviews', 'service_id')->where('user_id', $this->user()->id),
            ],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['service_id' => $this->route('service')?->id]);
    }
}
