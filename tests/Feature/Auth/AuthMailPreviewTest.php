<?php

use App\Models\User;

test('auth mail preview requires authentication', function () {
    $this->get('/dev/auth-mail-preview')
        ->assertRedirect(route('login'));
});

test('verification email preview can be rendered locally', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)
        ->get('/dev/auth-mail-preview/verification')
        ->assertOk()
        ->assertSee('Email Verification Preview')
        ->assertSee('Enter verification code')
        ->assertSee('verify-email', false);
});

test('password reset email preview can be rendered locally', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dev/auth-mail-preview/password-reset')
        ->assertOk()
        ->assertSee('Password Reset Preview')
        ->assertSee('Reset password')
        ->assertSee('reset-password', false);
});
