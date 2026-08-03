<?php

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('filters public service categories and uses active service counts', function () {
    $repairs = ServiceCategory::factory()->create([
        'name' => 'Repairs',
        'slug' => 'repairs',
        'keywords' => 'plumber electrician',
        'is_active' => true,
    ]);
    $cleaning = ServiceCategory::factory()->create([
        'name' => 'Cleaning',
        'slug' => 'cleaning',
        'is_active' => true,
    ]);

    Service::factory()->create(['service_category_id' => $repairs->id, 'is_active' => true]);
    Service::factory()->create(['service_category_id' => $repairs->id, 'is_active' => false]);

    $this->get('/service-categories?search=plumber')
        ->assertOk()
        ->assertSee('Repairs')
        ->assertSee('1 services')
        ->assertDontSee('Cleaning');
});

it('includes active child category services on parent service category detail pages', function () {
    $parent = ServiceCategory::factory()->create(['name' => 'Home Services', 'slug' => 'home-services', 'is_active' => true]);
    $child = ServiceCategory::factory()->create([
        'name' => 'Repairs',
        'slug' => 'home-repairs',
        'parent_id' => $parent->id,
        'is_active' => true,
    ]);
    $inactiveChild = ServiceCategory::factory()->create([
        'name' => 'Hidden Services',
        'slug' => 'hidden-services',
        'parent_id' => $parent->id,
        'is_active' => false,
    ]);

    Service::factory()->create(['name' => 'Parent Service', 'service_category_id' => $parent->id, 'is_active' => true]);
    Service::factory()->create(['name' => 'Child Service', 'service_category_id' => $child->id, 'is_active' => true]);
    Service::factory()->create(['name' => 'Hidden Child Service', 'service_category_id' => $inactiveChild->id, 'is_active' => true]);

    $this->get('/service-categories/home-services')
        ->assertOk()
        ->assertSee('Parent Service')
        ->assertSee('Child Service')
        ->assertDontSee('Hidden Child Service');
});

it('prevents service category hierarchy loops and nested parents', function () {
    $admin = User::factory()->admin()->create();
    $parent = ServiceCategory::factory()->create(['name' => 'Root Services', 'slug' => 'root-services']);
    $child = ServiceCategory::factory()->create(['name' => 'Child Services', 'slug' => 'child-services', 'parent_id' => $parent->id]);
    $grandchild = ServiceCategory::factory()->create(['name' => 'Grandchild Services', 'slug' => 'grandchild-services', 'parent_id' => $child->id]);

    $this->actingAs($admin)
        ->from(route('admin.service-categories.edit', $parent))
        ->put(route('admin.service-categories.update', $parent), [
            'name' => $parent->name,
            'slug' => $parent->slug,
            'parent_id' => $child->id,
            'color_code' => '#3B82F6',
        ])
        ->assertRedirect(route('admin.service-categories.edit', $parent))
        ->assertSessionHasErrors('parent_id');

    $this->actingAs($admin)
        ->from(route('admin.service-categories.edit', $child))
        ->put(route('admin.service-categories.update', $child), [
            'name' => $child->name,
            'slug' => $child->slug,
            'parent_id' => $grandchild->id,
            'color_code' => '#3B82F6',
        ])
        ->assertRedirect(route('admin.service-categories.edit', $child))
        ->assertSessionHasErrors('parent_id');
});

it('does not delete service categories with services or child categories', function () {
    $admin = User::factory()->admin()->create();
    $parent = ServiceCategory::factory()->create(['name' => 'Parent Services', 'slug' => 'parent-services']);
    $child = ServiceCategory::factory()->create(['parent_id' => $parent->id]);
    $occupied = ServiceCategory::factory()->create(['name' => 'Occupied Services', 'slug' => 'occupied-services']);
    Service::factory()->create(['service_category_id' => $occupied->id]);

    $this->actingAs($admin)
        ->delete(route('admin.service-categories.destroy', $parent))
        ->assertRedirect()
        ->assertSessionHas('error', 'Cannot delete category with subcategories.');

    $this->actingAs($admin)
        ->delete(route('admin.service-categories.destroy', $occupied))
        ->assertRedirect()
        ->assertSessionHas('error', 'Cannot delete category with associated services.');

    expect($parent->fresh())->not->toBeNull()
        ->and($child->fresh())->not->toBeNull()
        ->and($occupied->fresh())->not->toBeNull();
});
