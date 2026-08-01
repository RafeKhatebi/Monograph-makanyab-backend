<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

test('email verification screen can be rendered', function () {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get('/verify-email');

    $response
        ->assertStatus(200)
        ->assertSee('A verification email has been sent. Please check your inbox to continue.');
});

test('verification email can be resent', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->post('/email/verification-notification');

    $response
        ->assertRedirect()
        ->assertSessionHas('status', __('auth.verification_sent'));

    Notification::assertSentTo($user, VerifyEmail::class);
});

test('email can be verified', function () {
    $user = User::factory()->unverified()->create();

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    Event::assertDispatched(Verified::class);
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response
        ->assertRedirect(route('home'))
        ->assertSessionHas('status', __('auth.email_verified'));
});

test('email is not verified with invalid hash', function () {
    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
    $response
        ->assertRedirect(route('verification.notice'))
        ->assertSessionHasErrors(['email' => __('auth.verification_invalid')]);
});

test('email is not verified with expired link', function () {
    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->subMinute(),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
    $response
        ->assertRedirect(route('verification.notice'))
        ->assertSessionHasErrors(['email' => __('auth.verification_invalid')]);
});

test('unverified users cannot access protected pages', function () {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get('/profile');

    $response->assertRedirect(route('verification.notice'));
});
