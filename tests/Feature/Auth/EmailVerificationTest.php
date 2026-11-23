<?php

use App\Models\EmailVerificationOtp;
use App\Models\User;
use App\Notifications\SendEmailVerificationOtpNotification;
use Illuminate\Support\Facades\Notification;

test('email verification screen can be rendered', function () {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get('/verify-email');

    $response
        ->assertStatus(200)
        ->assertSee('Verify your email address to continue.');
});

test('verification email can be resent', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->post('/email/verification-notification');

    $response
        ->assertRedirect()
        ->assertSessionHas('status', __('auth.verification_sent'));

    Notification::assertSentTo($user, SendEmailVerificationOtpNotification::class);
});

test('verification email resend is limited to once per minute', function () {
    Notification::fake();
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)->post(route('verification.send'))->assertRedirect();
    $this->actingAs($user)->post(route('verification.send'))->assertStatus(429);
});

test('unverified users cannot access protected pages', function () {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get('/profile');

    $response->assertRedirect(route('verification.notice'));
});

test('an OTP is hashed, can be used once, and verifies the user', function () {
    $user = User::factory()->unverified()->create();
    [, $code] = EmailVerificationOtp::issueForUser($user);

    $this->assertDatabaseMissing('email_verification_otps', ['otp_code' => $code]);

    $this->actingAs($user)
        ->post(route('verification.otp.verify'), ['otp_code' => $code])
        ->assertRedirect(route('home'));

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $this->actingAs($user)->get('/profile')->assertOk();

    $user->forceFill(['email_verified_at' => null])->save();
    $this->actingAs($user)
        ->post(route('verification.otp.verify'), ['otp_code' => $code])
        ->assertSessionHasErrors('otp_code');
});

test('invalid OTP attempts are rejected and rate limited', function () {
    $user = User::factory()->unverified()->create();
    EmailVerificationOtp::issueForUser($user);

    foreach (range(1, 6) as $_) {
        $this->actingAs($user)
            ->post(route('verification.otp.verify'), ['otp_code' => '000000'])
            ->assertSessionHasErrors('otp_code');
    }

    $this->actingAs($user)
        ->post(route('verification.otp.verify'), ['otp_code' => '000000'])
        ->assertTooManyRequests();
});

test('expired OTPs are rejected', function () {
    $user = User::factory()->unverified()->create();
    [$otp, $code] = EmailVerificationOtp::issueForUser($user);
    $otp->update(['expires_at' => now()->subSecond()]);

    $this->actingAs($user)
        ->post(route('verification.otp.verify'), ['otp_code' => $code])
        ->assertSessionHasErrors('otp_code');

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});
