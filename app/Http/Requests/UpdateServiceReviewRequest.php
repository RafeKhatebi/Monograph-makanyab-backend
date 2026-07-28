<?php

namespace App\Http\Requests;

use App\Models\Review;
use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Review|null $review */
        $review = $this->route('review');

        return $review !== null
            && $review->service_id === $this->route('service')?->id
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
