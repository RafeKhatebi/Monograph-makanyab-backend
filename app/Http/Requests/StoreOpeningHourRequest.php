<?php

namespace App\Http\Requests;

use App\Models\Place;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOpeningHourRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Place $place */
        $place = Place::find($this->input('place_id'));

        // Only the place owner or an admin can set opening hours
        return $place
            && ($this->user()?->id === $place->user_id
                || $this->user()?->isAdmin());
    }

    public function rules(): array
    {
        return [
            'place_id' => [
                'required',
                'uuid',
                Rule::exists('places', 'id')->whereNull('deleted_at'),
            ],
            'day_of_week' => [
                'required',
                'integer',
                'between:0,6',
                // Prevent duplicate day for the same place
                Rule::unique('opening_hours', 'day_of_week')->where('place_id', $this->input('place_id')),
            ],
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

    public function messages(): array
    {
        return [
            'place_id.exists' => 'The selected place does not exist or has been removed.',
            'day_of_week.between' => 'day_of_week must be 0 (Sunday) through 6 (Saturday).',
            'day_of_week.unique' => 'Opening hours for this day already exist for this place.',
            'open_time.required_if' => 'open_time is required when the place is not closed.',
            'close_time.required_if' => 'close_time is required when the place is not closed.',
            'close_time.after' => 'close_time must be after open_time.',
            'open_time.date_format' => 'open_time must be in HH:MM format (e.g. 09:00).',
            'close_time.date_format' => 'close_time must be in HH:MM format (e.g. 22:00).',
        ];
    }
}
