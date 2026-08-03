<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    protected $model = Media::class;

    public function definition(): array
    {
        return [
            'mediable_type' => Place::class,
            'mediable_id' => Place::factory(),
            'file_path' => 'seed/place-placeholder-'.fake()->numberBetween(1, 5).'.jpg',
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
            'file_size' => fake()->numberBetween(25_000, 500_000),
            'type' => 'image',
            'is_cover' => false,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }

    public function forModel(object $model): static
    {
        return $this->state(fn () => [
            'mediable_type' => $model::class,
            'mediable_id' => $model->getKey(),
        ]);
    }

    public function cover(): static
    {
        return $this->state(fn () => ['is_cover' => true, 'sort_order' => 0]);
    }
}
