<?php

namespace Database\Factories;

use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlaceFactory extends Factory
{
    protected $model = Place::class;

    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'user_id' => User::factory()->owner(),
            'place_category_id' => function () {
                return PlaceCategory::create([
                    'name' => fake()->unique()->word(),
                    'slug' => fake()->unique()->slug(),
                    'is_active' => true,
                ])->id;
            },
            'name' => Str::title($name),
            'slug' => fake()->unique()->slug(),
            'tagline' => fake()->sentence(),
            'description' => fake()->paragraphs(2, true),
            'phone_1' => '+93'.fake()->numerify('#########'),
            'phone_2' => fake()->optional()->numerify('+93#########'),
            'whatsapp' => fake()->optional()->numerify('+93#########'),
            'website' => fake()->optional()->url(),
            'social_links' => ['facebook' => fake()->optional()->url()],
            'address' => fake()->streetAddress(),
            'country' => 'Afghanistan',
            'province' => fake()->randomElement(['Kabul', 'Herat', 'Balkh', 'Nangarhar']),
            'city' => fake()->randomElement(['Kabul', 'Herat', 'Mazar-e-Sharif', 'Jalalabad']),
            'district' => fake()->randomElement(['Kabul', 'Bagrami', 'Injil', 'Mazar-e-Sharif']),
            'latitude' => fake()->latitude(31, 37),
            'longitude' => fake()->longitude(61, 71),
            'status' => fake()->randomElement(['open', 'closed', 'temporarily_closed']),
            'price_level' => fake()->randomElement(['low', 'medium', 'high', 'luxury']),
            'is_verified' => fake()->boolean(45),
            'is_active' => true,
        ];
    }

    public function verified(): static
    {
        return $this->state(fn () => ['is_verified' => true]);
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['is_verified' => false]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function longContent(): static
    {
        return $this->state(fn () => [
            'name' => 'Historic Community Marketplace With An Exceptionally Long Display Name',
            'description' => str_repeat('Detailed place description for layout and truncation testing. ', 35),
        ]);
    }

    public function dariContent(): static
    {
        return $this->state(fn () => [
            'name' => 'رستورانت خانوادگی کابل',
            'tagline' => 'غذاهای محلی با فضای آرام',
            'description' => 'این متن برای آزمایش نمایش محتوای دری و راست به چپ در صفحات مکان‌ها استفاده می‌شود.',
        ]);
    }
}
