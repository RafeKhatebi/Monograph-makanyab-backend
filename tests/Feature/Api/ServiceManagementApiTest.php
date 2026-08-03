<?php

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;

function servicePayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Emergency Plumbing',
        'service_category_id' => ServiceCategory::factory()->create()->id,
        'description' => 'Fast plumbing support for homes and offices.',
        'phone_1' => '+93000000000',
        'address' => 'Main Street',
        'country' => 'Afghanistan',
        'province' => 'Kabul',
        'city' => 'Kabul',
        'district' => 'District 1',
    ], $overrides);
}

test('owner can create a service through the api', function () {
    $owner = User::factory()->owner()->create();

    $this->actingAs($owner)
        ->postJson('/api/services', servicePayload([
            'is_verified' => true,
        ]))
        ->assertCreated()
        ->assertJsonFragment([
            'name' => 'Emergency Plumbing',
            'user_id' => $owner->id,
            'is_verified' => false,
        ]);
});

test('regular user cannot create a service through the api', function () {
    $user = User::factory()->create(['role' => 'user']);

    $this->actingAs($user)
        ->postJson('/api/services', servicePayload())
        ->assertForbidden();
});

test('owner can update their own service through the api', function () {
    $owner = User::factory()->owner()->create();
    $service = Service::factory()->create(['user_id' => $owner->id, 'is_verified' => true]);

    $this->actingAs($owner)
        ->patchJson('/api/services/'.$service->slug, servicePayload([
            'service_category_id' => $service->service_category_id,
            'name' => 'Updated Owner Service',
            'is_verified' => false,
        ]))
        ->assertOk()
        ->assertJsonFragment([
            'name' => 'Updated Owner Service',
            'is_verified' => true,
        ]);
});

test('non-owner cannot update or delete another owners service', function () {
    $service = Service::factory()->create(['user_id' => User::factory()->owner()->create()->id]);
    $otherOwner = User::factory()->owner()->create();

    $this->actingAs($otherOwner)
        ->patchJson('/api/services/'.$service->slug, servicePayload([
            'service_category_id' => $service->service_category_id,
            'name' => 'Unauthorized Update',
        ]))
        ->assertForbidden();

    $this->actingAs($otherOwner)
        ->deleteJson('/api/services/'.$service->slug)
        ->assertForbidden();

    $this->assertDatabaseHas('services', ['id' => $service->id]);
});

test('admin can delete any service through the api', function () {
    $admin = User::factory()->admin()->create();
    $service = Service::factory()->create();

    $this->actingAs($admin)
        ->deleteJson('/api/services/'.$service->slug)
        ->assertNoContent();

    $this->assertSoftDeleted('services', ['id' => $service->id]);
});
