<?php

namespace Database\Factories;

use App\Models\Place;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'place_id' => Place::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->paragraph(),
            'is_approved' => true,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['is_approved' => false]);
    }

    public function rejected(): static
    {
        return $this->state(fn () => [
            'is_approved' => false,
            'comment' => 'Rejected during moderation: '.fake()->sentence(),
        ]);
    }

    public function forService(?Service $service = null): static
    {
        return $this->state(fn () => [
            'place_id' => null,
            'service_id' => $service?->id ?? Service::factory(),
        ]);
    }
}
