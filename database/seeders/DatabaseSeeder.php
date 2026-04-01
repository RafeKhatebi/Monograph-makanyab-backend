<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'id' => (string) Str::uuid(),
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@gmail.com',
            'password' => Hash::make('test123'), // Always hash passwords!
            'role' => 'admin',
        ]);

        $this->call([
            UserSeeder::class,
            PlaceCategorySeeder::class,
            PlaceSeeder::class,
        ]);
    }
}
