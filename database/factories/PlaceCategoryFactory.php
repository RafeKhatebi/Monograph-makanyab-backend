<?php

namespace Database\Factories;

use App\Models\PlaceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlaceCategoryFactory extends Factory
{
    protected $model = PlaceCategory::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'slug' => fake()->unique()->slug(),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
