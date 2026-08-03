<?php

use App\Models\User;

test('admin cannot demote their own account', function () {
    $admin = User::factory()->admin()->create(['username' => 'admin_self_demote']);

    $this->actingAs($admin)
        ->from('/admin/users/'.$admin->id.'/edit')
        ->put('/admin/users/'.$admin->id, [
            'name' => $admin->name,
            'username' => $admin->username,
            'email' => $admin->email,
            'role' => 'user',
            'is_active' => '1',
        ])
        ->assertRedirect('/admin/users/'.$admin->id.'/edit')
        ->assertSessionHas('error', 'You cannot change your own administrator role.');

    expect($admin->fresh()->isAdmin())->toBeTrue();
});

test('admin cannot deactivate their own account through update', function () {
    $admin = User::factory()->admin()->create(['username' => 'admin_self_deactivate']);

    $this->actingAs($admin)
        ->from('/admin/users/'.$admin->id.'/edit')
        ->put('/admin/users/'.$admin->id, [
            'name' => $admin->name,
            'username' => $admin->username,
            'email' => $admin->email,
            'role' => 'admin',
        ])
        ->assertRedirect('/admin/users/'.$admin->id.'/edit')
        ->assertSessionHas('error', 'You cannot deactivate your own account.');

    expect($admin->fresh()->is_active)->toBeTrue();
});
