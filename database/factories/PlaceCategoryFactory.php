<?php

namespace Database\Factories;

use App\Models\PlaceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlaceCategoryFactory extends Factory
{
    protected $model = PlaceCategory::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => Str::title($name),
            'slug' => fake()->unique()->slug(),
            'icon_name' => fake()->randomElement(['map-pin', 'utensils', 'building', 'shopping-bag']),
            'color_code' => fake()->hexColor(),
            'has_menu' => fake()->boolean(25),
            'has_booking' => fake()->boolean(35),
            'has_delivery' => fake()->boolean(30),
            'keywords' => implode(', ', fake()->words(4)),
            'schema_type' => 'LocalBusiness',
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function empty(): static
    {
        return $this->state(fn () => [
            'name' => 'Empty Place Category '.fake()->unique()->numberBetween(1000, 9999),
            'slug' => 'empty-place-category-'.fake()->unique()->numberBetween(1000, 9999),
        ]);
    }
}
