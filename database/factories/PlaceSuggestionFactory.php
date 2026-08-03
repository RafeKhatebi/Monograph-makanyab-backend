<?php

namespace Database\Factories;

use App\Models\PlaceCategory;
use App\Models\PlaceSuggestion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlaceSuggestionFactory extends Factory
{
    protected $model = PlaceSuggestion::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'place_category_id' => PlaceCategory::factory(),
            'name' => Str::title(fake()->words(3, true)),
            'tagline' => fake()->sentence(5),
            'description' => fake()->paragraph(),
            'phone_1' => '+93'.fake()->numerify('#########'),
            'address' => fake()->streetAddress(),
            'country' => 'Afghanistan',
            'province' => 'Kabul',
            'city' => 'Kabul',
            'district' => 'Kabul',
            'status' => 'open',
            'price_level' => 'medium',
            'submitted_by_name' => fake()->name(),
            'submitted_by_email' => fake()->safeEmail(),
            'suggestion_status' => 'pending',
        ];
    }

    public function approved(): static
    {
        return $this->state(fn () => ['suggestion_status' => 'approved', 'approved_at' => now()]);
    }

    public function rejected(): static
    {
        return $this->state(fn () => ['suggestion_status' => 'rejected', 'rejected_at' => now()]);
    }
}
