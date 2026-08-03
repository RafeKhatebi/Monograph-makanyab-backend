<?php

use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('filters public place categories and links to category detail pages with active counts', function () {
    $restaurants = PlaceCategory::factory()->create([
        'name' => 'Restaurants',
        'slug' => 'restaurants',
        'keywords' => 'food dining',
        'is_active' => true,
    ]);
    $shops = PlaceCategory::factory()->create([
        'name' => 'Shops',
        'slug' => 'shops',
        'is_active' => true,
    ]);

    Place::factory()->create(['place_category_id' => $restaurants->id, 'is_active' => true]);
    Place::factory()->create(['place_category_id' => $restaurants->id, 'is_active' => false]);

    $this->get('/categories?search=food')
        ->assertOk()
        ->assertSee('Restaurants')
        ->assertSee(route('categories.show', 'restaurants'), false)
        ->assertSee('1 places')
        ->assertDontSee('Shops');
});

it('includes active child category places on parent category detail pages', function () {
    $parent = PlaceCategory::factory()->create(['name' => 'Food', 'slug' => 'food', 'is_active' => true]);
    $child = PlaceCategory::factory()->create([
        'name' => 'Cafes',
        'slug' => 'cafes',
        'parent_id' => $parent->id,
        'is_active' => true,
    ]);
    $inactiveChild = PlaceCategory::factory()->create([
        'name' => 'Hidden Food',
        'slug' => 'hidden-food',
        'parent_id' => $parent->id,
        'is_active' => false,
    ]);

    Place::factory()->create(['name' => 'Parent Place', 'place_category_id' => $parent->id, 'is_active' => true]);
    Place::factory()->create(['name' => 'Child Place', 'place_category_id' => $child->id, 'is_active' => true]);
    Place::factory()->create(['name' => 'Hidden Child Place', 'place_category_id' => $inactiveChild->id, 'is_active' => true]);

    $this->get('/categories/food')
        ->assertOk()
        ->assertSee('Parent Place')
        ->assertSee('Child Place')
        ->assertDontSee('Hidden Child Place');
});

it('prevents place category hierarchy loops and nested parents', function () {
    $admin = User::factory()->admin()->create();
    $parent = PlaceCategory::factory()->create(['name' => 'Root', 'slug' => 'root']);
    $child = PlaceCategory::factory()->create(['name' => 'Child', 'slug' => 'child', 'parent_id' => $parent->id]);
    $grandchild = PlaceCategory::factory()->create(['name' => 'Grandchild', 'slug' => 'grandchild', 'parent_id' => $child->id]);

    $this->actingAs($admin)
        ->from(route('admin.categories.edit', $parent))
        ->put(route('admin.categories.update', $parent), [
            'name' => $parent->name,
            'slug' => $parent->slug,
            'parent_id' => $child->id,
            'color_code' => '#10B981',
        ])
        ->assertRedirect(route('admin.categories.edit', $parent))
        ->assertSessionHasErrors('parent_id');

    $this->actingAs($admin)
        ->from(route('admin.categories.edit', $child))
        ->put(route('admin.categories.update', $child), [
            'name' => $child->name,
            'slug' => $child->slug,
            'parent_id' => $grandchild->id,
            'color_code' => '#10B981',
        ])
        ->assertRedirect(route('admin.categories.edit', $child))
        ->assertSessionHasErrors('parent_id');
});

it('does not delete categories that still have places or child categories', function () {
    $admin = User::factory()->admin()->create();
    $parent = PlaceCategory::factory()->create(['name' => 'Parent', 'slug' => 'parent']);
    $child = PlaceCategory::factory()->create(['parent_id' => $parent->id]);
    $occupied = PlaceCategory::factory()->create(['name' => 'Occupied', 'slug' => 'occupied']);
    Place::factory()->create(['place_category_id' => $occupied->id]);

    $this->actingAs($admin)
        ->delete(route('admin.categories.destroy', $parent))
        ->assertRedirect()
        ->assertSessionHas('error', 'Cannot delete category with subcategories.');

    $this->actingAs($admin)
        ->delete(route('admin.categories.destroy', $occupied))
        ->assertRedirect()
        ->assertSessionHas('error', 'Cannot delete category with associated places.');

    expect($parent->fresh())->not->toBeNull()
        ->and($child->fresh())->not->toBeNull()
        ->and($occupied->fresh())->not->toBeNull();
});
