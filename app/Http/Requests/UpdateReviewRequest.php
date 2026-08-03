<?php

namespace App\Http\Requests;

use App\Models\Review;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Review|null $review */
        $review = $this->route('review');

        return $review !== null
            && $review->place_id === $this->route('place')?->id
            && ($review->user_id === $this->user()?->id || $this->user()?->isAdmin());
    }

    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
