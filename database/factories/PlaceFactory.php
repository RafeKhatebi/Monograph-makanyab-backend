<?php

namespace Database\Factories;

use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlaceFactory extends Factory
{
    protected $model = Place::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'place_category_id' => function () {
                return PlaceCategory::create([
                    'name' => fake()->unique()->word(),
                    'slug' => fake()->unique()->slug(),
                    'is_active' => true,
                ])->id;
            },
            'name' => fake()->words(3, true),
            'slug' => fake()->unique()->slug(),
            'tagline' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'phone_1' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'country' => 'Afghanistan',
            'province' => 'Kabul',
            'city' => 'Kabul',
            'district' => fake()->city(),
            'latitude' => fake()->latitude(34, 35),
            'longitude' => fake()->longitude(69, 70),
            'status' => 'open',
            'price_level' => 'medium',
            'is_verified' => false,
            'is_active' => true,
        ];
    }
}
