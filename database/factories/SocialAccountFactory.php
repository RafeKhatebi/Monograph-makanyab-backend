<?php

namespace Database\Factories;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SocialAccountFactory extends Factory
{
    protected $model = SocialAccount::class;

    public function definition(): array
    {
        $provider = fake()->randomElement(['google', 'facebook']);

        return [
            'user_id' => User::factory(),
            'provider' => $provider,
            'provider_user_id' => $provider.'-'.fake()->unique()->numerify('##########'),
            'provider_email' => fake()->safeEmail(),
            'avatar_url' => fake()->optional()->imageUrl(),
        ];
    }
}
