<?php

namespace Database\Factories;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactMessageFactory extends Factory
{
    protected $model = ContactMessage::class;

    public function definition(): array
    {
        return [
            'user_id' => fake()->optional(0.35)->randomElement([User::factory()]),
            'name' => fake()->name(),
            'telephone' => '+93'.fake()->numerify('#########'),
            'email' => fake()->safeEmail(),
            'subject' => fake()->sentence(4),
            'message' => fake()->paragraphs(2, true),
        ];
    }
}
