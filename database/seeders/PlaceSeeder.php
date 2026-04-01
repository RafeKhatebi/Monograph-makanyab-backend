<?php

namespace Database\Seeders;

use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlaceSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();
        $category = PlaceCategory::first();

        Place::create([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'place_categories_id' => $category->id,
            'name' => 'Central Coffee Roasters',
            'slug' => 'central-coffee-roasters',
            'tagline' => 'Best beans in the city',
            'description' => 'A cozy place to work and enjoy specialty coffee.',
            'phone_1' => '08123456789',
            'address' => 'Jl. Sudirman No. 123',
            'country' => 'Indonesia',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Kebun Jeruk',
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'social_links' => json_encode(['instagram' => '@centralcoffee']),
            'status' => 'open',
            'price_level' => 'medium',
            'is_verified' => true,
        ]);
    }
}
