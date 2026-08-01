<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    Notification::fake();

    $email = 'test_'.uniqid().'@example.com';

    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => 'test_user_'.uniqid(),
        'email' => $email,
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $this->assertAuthenticated();
    $response
        ->assertRedirect(route('verification.notice'))
        ->assertSessionHas('status', __('auth.verification_sent'));

    $user = User::where('email', $email)->firstOrFail();

    expect($user->hasVerifiedEmail())->toBeFalse();
    Notification::assertSentTo($user, VerifyEmail::class);
});
