<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'place_id' => [
                'required',
                'uuid',
                Rule::exists('places', 'id')->whereNull('deleted_at'),
                // Prevent duplicate review: one user can only review a place once
                Rule::unique('reviews', 'place_id')->where('user_id', $this->user()->id),
            ],
            'rating'  => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'min:10', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'place_id.exists'  => 'The selected place does not exist or has been removed.',
            'place_id.unique'  => 'You have already submitted a review for this place.',
            'rating.between'   => 'Rating must be between 1 and 5.',
            'comment.min'      => 'Comment must be at least 10 characters if provided.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['user_id' => $this->user()->id]);
    }
}
