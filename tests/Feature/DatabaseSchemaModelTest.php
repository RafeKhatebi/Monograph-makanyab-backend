<?php

use App\Models\ContactMessage;
use App\Models\PlaceCategory;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user schema fields are mass assignable and cast correctly', function () {
    $user = User::create([
        'name' => 'Schema User',
        'username' => 'schema-user',
        'email' => 'schema-user@example.com',
        'password' => 'password',
        'gender' => 'prefer_not_to_say',
        'date_of_birth' => '1990-01-15',
        'address' => 'Kabul, Afghanistan',
        'is_active' => false,
        'settings' => ['locale' => 'fa'],
    ]);

    expect($user->is_active)->toBeFalse()
        ->and($user->settings)->toBe(['locale' => 'fa'])
        ->and($user->date_of_birth->toDateString())->toBe('1990-01-15');
});

test('category feature flags and ordering are cast to native types', function () {
    $placeCategory = PlaceCategory::create([
        'name' => 'Phase Three Places',
        'slug' => 'phase-three-places',
        'has_menu' => 1,
        'has_booking' => 0,
        'has_delivery' => 1,
        'sort_order' => '12',
    ]);
    $serviceCategory = ServiceCategory::create([
        'name' => 'Phase Three Services',
        'slug' => 'phase-three-services',
        'has_menu' => 0,
        'has_booking' => 1,
        'has_delivery' => 0,
        'sort_order' => '7',
    ]);

    expect($placeCategory->has_menu)->toBeTrue()
        ->and($placeCategory->has_booking)->toBeFalse()
        ->and($placeCategory->has_delivery)->toBeTrue()
        ->and($placeCategory->sort_order)->toBe(12)
        ->and($serviceCategory->has_menu)->toBeFalse()
        ->and($serviceCategory->has_booking)->toBeTrue()
        ->and($serviceCategory->has_delivery)->toBeFalse()
        ->and($serviceCategory->sort_order)->toBe(7);
});

test('contact messages can be reached from their submitting user', function () {
    $user = User::factory()->create();

    $message = ContactMessage::create([
        'user_id' => $user->id,
        'name' => 'Contact User',
        'telephone' => '+93000000000',
        'email' => 'contact@example.com',
        'subject' => 'Schema relationship',
        'message' => 'Testing the relationship.',
    ]);

    expect($message->user->is($user))->toBeTrue()
        ->and($user->contactMessages()->whereKey($message->id)->exists())->toBeTrue();
});
