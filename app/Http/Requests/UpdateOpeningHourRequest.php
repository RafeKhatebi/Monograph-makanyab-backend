<?php

namespace App\Http\Requests;

use App\Models\OpeningHour;
use App\Models\Place;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOpeningHourRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Place|null $place */
        $place = $this->route('place');
        /** @var OpeningHour|null $openingHour */
        $openingHour = $this->route('openingHour');

        return $place !== null
            && $openingHour?->place_id === $place->id
            && ($place->user_id === $this->user()?->id || $this->user()?->isAdmin());
    }

    public function rules(): array
    {
        return [
            'is_closed' => ['sometimes', 'boolean'],
            'open_time' => [
                Rule::requiredIf(fn () => ! $this->boolean('is_closed')),
                'nullable',
                'date_format:H:i',
            ],
            'close_time' => [
                Rule::requiredIf(fn () => ! $this->boolean('is_closed')),
                'nullable',
                'date_format:H:i',
                'after:open_time',
            ],
        ];
    }
}
