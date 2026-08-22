<?php

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view contact messages and opening one marks it read', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $message = ContactMessage::factory()->create([
        'subject' => 'Important request',
        'read_at' => null,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.contact-messages.index'))
        ->assertOk()
        ->assertSee('Important request');

    $this->actingAs($admin)
        ->get(route('admin.contact-messages.show', $message))
        ->assertOk()
        ->assertSee($message->email);

    expect($message->fresh()->read_at)->not->toBeNull();
});

test('admin can archive restore and delete contact messages', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $message = ContactMessage::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.contact-messages.archive', $message))
        ->assertSessionHas('success');

    expect($message->fresh()->archived_at)->not->toBeNull();

    $this->actingAs($admin)
        ->post(route('admin.contact-messages.restore', $message))
        ->assertSessionHas('success');

    expect($message->fresh()->archived_at)->toBeNull();

    $this->actingAs($admin)
        ->delete(route('admin.contact-messages.destroy', $message))
        ->assertRedirect(route('admin.contact-messages.index'));

    $this->assertDatabaseMissing('contact_messages', ['id' => $message->id]);
});

test('non-admin users cannot access contact messages admin pages', function () {
    $user = User::factory()->create(['role' => 'user']);

    $this->actingAs($user)
        ->get(route('admin.contact-messages.index'))
        ->assertForbidden();
});
