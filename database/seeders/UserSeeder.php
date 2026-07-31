<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin',
            'lastname' => 'User',
            'username' => 'admin_user',
            'email' => 'admin@gmail.com',
            'password' => 'password',
            'is_active' => true,
        ]);
    }
}
