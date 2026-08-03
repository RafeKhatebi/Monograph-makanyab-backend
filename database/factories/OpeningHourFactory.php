<?php

namespace Database\Factories;

use App\Models\OpeningHour;
use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpeningHourFactory extends Factory
{
    protected $model = OpeningHour::class;

    public function definition(): array
    {
        return [
            'place_id' => Place::factory(),
            'day_of_week' => fake()->numberBetween(0, 6),
            'open_time' => '09:00',
            'close_time' => '17:00',
            'is_closed' => false,
        ];
    }

    public function closed(): static
    {
        return $this->state(fn () => [
            'open_time' => null,
            'close_time' => null,
            'is_closed' => true,
        ]);
    }
}
