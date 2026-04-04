<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Admin',
            'lastname' => 'User',
            'username' => 'admin_user',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'), // Always hash passwords!
            'role' => 'admin',
            'is_active' => true,
        ]);
    }
}
