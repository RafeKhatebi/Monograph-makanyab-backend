<?php

use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->category = PlaceCategory::create(['name' => 'Test ' . uniqid(), 'slug' => 'test-' . uniqid(), 'is_active' => true]);
});

test('admin can create places', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->assertTrue($admin->can('create', Place::class));
});

test('owner can create places', function () {
    $owner = User::factory()->create(['role' => 'owner']);
    $this->assertTrue($owner->can('create', Place::class));
});

test('regular user cannot create places', function () {
    $user = User::factory()->create(['role' => 'user']);
    $this->assertFalse($user->can('create', Place::class));
});

test('admin can update any place', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $place = Place::factory()->create(['place_category_id' => $this->category->id]);

    $this->assertTrue($admin->can('update', $place));
});

test('place owner can update their place', function () {
    $user = User::factory()->create(['role' => 'user']);
    $place = Place::factory()->create(['user_id' => $user->id, 'place_category_id' => $this->category->id]);

    $this->assertTrue($user->can('update', $place));
});

test('non-owner cannot update place', function () {
    $user = User::factory()->create(['role' => 'user']);
    $place = Place::factory()->create(['place_category_id' => $this->category->id]);

    $this->assertFalse($user->can('update', $place));
});

test('admin can delete any place', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $place = Place::factory()->create(['place_category_id' => $this->category->id]);

    $this->assertTrue($admin->can('delete', $place));
});

test('place owner can delete their place', function () {
    $user = User::factory()->create(['role' => 'user']);
    $place = Place::factory()->create(['user_id' => $user->id, 'place_category_id' => $this->category->id]);

    $this->assertTrue($user->can('delete', $place));
});

test('non-owner cannot delete place', function () {
    $user = User::factory()->create(['role' => 'user']);
    $place = Place::factory()->create(['place_category_id' => $this->category->id]);

    $this->assertFalse($user->can('delete', $place));
});

test('anyone can view places', function () {
    $user = User::factory()->create(['role' => 'user']);
    $place = Place::factory()->create(['place_category_id' => $this->category->id]);

    $this->assertTrue($user->can('view', $place));
});
