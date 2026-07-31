<?php

namespace Database\Factories;

use App\Models\Favorite;
use App\Models\Place;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavoriteFactory extends Factory
{
    protected $model = Favorite::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'place_id' => Place::factory(),
        ];
    }

    public function forService(?Service $service = null): static
    {
        return $this->state(fn () => [
            'place_id' => null,
            'service_id' => $service?->id ?? Service::factory(),
        ]);
    }
}
