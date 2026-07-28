<?php

use App\Models\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('anyone can list active service categories', function () {
    ServiceCategory::create(['name' => 'Active Category', 'slug' => 'active', 'is_active' => true]);
    ServiceCategory::create(['name' => 'Inactive Category', 'slug' => 'inactive', 'is_active' => false]);

    $response = $this->getJson('/api/service-categories')
        ->assertOk();

    $this->assertCount(1, $response->json());
});

test('anyone can view a service category', function () {
    $category = ServiceCategory::create(['name' => 'Test', 'slug' => 'test', 'is_active' => true]);

    $this->getJson("/api/service-categories/{$category->id}")
        ->assertOk()
        ->assertJsonFragment(['name' => 'Test']);
});

test('service categories can be filtered by parent', function () {
    $parent = ServiceCategory::create(['name' => 'Parent', 'slug' => 'parent', 'is_active' => true]);
    ServiceCategory::create(['name' => 'Child', 'slug' => 'child', 'parent_id' => $parent->id, 'is_active' => true]);
    ServiceCategory::create(['name' => 'Root', 'slug' => 'root', 'is_active' => true]);

    $response = $this->getJson("/api/service-categories?parent_id={$parent->id}")
        ->assertOk();

    $this->assertCount(1, $response->json());
});

test('service categories returns all when no filter', function () {
    ServiceCategory::create(['name' => 'Cat1', 'slug' => 'cat1', 'is_active' => true]);
    ServiceCategory::create(['name' => 'Cat2', 'slug' => 'cat2', 'is_active' => true]);

    $response = $this->getJson('/api/service-categories')
        ->assertOk();

    $this->assertCount(2, $response->json());
});
