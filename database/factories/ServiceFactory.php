<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'user_id' => User::factory()->owner(),
            'service_category_id' => ServiceCategory::factory(),
            'name' => Str::title($name),
            'slug' => fake()->unique()->slug(3),
            'tagline' => fake()->sentence(6),
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
            'name' => 'Professional Multi District Emergency Repair And Maintenance Service',
            'description' => str_repeat('Detailed service description for layout and truncation testing. ', 35),
        ]);
    }

    public function dariContent(): static
    {
        return $this->state(fn () => [
            'name' => 'خدمات ترمیمات خانه',
            'tagline' => 'خدمات سریع و قابل اعتماد در کابل',
            'description' => 'این متن برای آزمایش نمایش محتوای دری و راست به چپ در صفحات خدمات استفاده می‌شود.',
        ]);
    }
}
