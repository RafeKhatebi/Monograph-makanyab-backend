<?php

namespace Database\Factories;

use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceCategoryFactory extends Factory
{
    protected $model = ServiceCategory::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => Str::title($name),
            'slug' => fake()->unique()->slug(2),
            'description' => fake()->sentence(),
            'icon_name' => fake()->randomElement(['wrench', 'briefcase', 'scissors', 'truck', 'home']),
            'color_code' => fake()->hexColor(),
            'has_menu' => fake()->boolean(20),
            'has_booking' => fake()->boolean(50),
            'has_delivery' => fake()->boolean(25),
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
            'name' => 'Empty Service Category '.fake()->unique()->numberBetween(1000, 9999),
            'slug' => 'empty-service-category-'.fake()->unique()->numberBetween(1000, 9999),
        ]);
    }
}
