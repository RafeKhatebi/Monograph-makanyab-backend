<?php

namespace Database\Seeders;

use App\Models\PlaceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlaceCategorySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a Parent Category
        $food = PlaceCategory::create([
            'name' => 'Food & Drink',
            'slug' => 'food-and-drink',
            'icon_name' => 'heroicon-o-utensils',
            'color_code' => '#ef4444',
            'has_menu' => true,
            'has_delivery' => true,
            'is_active' => true,
        ]);

        // 2. Create a Sub-Category
        PlaceCategory::create([
            'parent_id' => $food->id,
            'name' => 'Coffee Shop',
            'slug' => 'coffee-shop',
            'icon_name' => 'heroicon-o-beaker',
            'color_code' => '#92400e',
            'has_menu' => true,
            'is_active' => true,
        ]);

        // 3. Create a Standalone Category
        PlaceCategory::create([
            'name' => 'Hotels',
            'slug' => 'hotels',
            'icon_name' => 'heroicon-o-home',
            'has_booking' => true,
            'is_active' => true,
        ]);
    }
}
