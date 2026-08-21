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
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        /** @var OpeningHour|null $openingHour */
        $openingHour = $this->route('openingHour');

        if (! $openingHour) {
            return;
        }

        $this->merge([
            'is_closed' => $this->has('is_closed') ? $this->boolean('is_closed') : $openingHour->is_closed,
            'open_time' => $this->input('open_time', $openingHour->open_time),
            'close_time' => $this->input('close_time', $openingHour->close_time),
        ]);

        if ($this->boolean('is_closed')) {
            $this->merge(['open_time' => null, 'close_time' => null]);
        }
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->boolean('is_closed')
                && $this->input('open_time')
                && $this->input('close_time')
                && $this->input('open_time') === $this->input('close_time')) {
                $validator->errors()->add('close_time', 'Opening and closing times cannot be identical.');
            }
        });
    }
}
