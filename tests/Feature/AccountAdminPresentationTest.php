<?php

use App\Enums\SuggestionStatus;
use App\Models\ContactMessage;
use App\Models\PlaceSuggestion;
use App\Models\Post;
use App\Models\ServiceSuggestion;
use App\Models\User;
use App\Notifications\SendEmailVerificationOtpNotification;

test('authentication pages expose the localized account shell', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('data-show-password="Show password"', false)
        ->assertSeeText('Back to Makanyab')
        ->assertSeeText('Local knowledge, shared by the community')
        ->assertSee('data-auth-form', false);

    $this->get(route('register'))
        ->assertOk()
        ->assertSeeText('Create an account to contribute local knowledge')
        ->assertSee('data-loading-text="Creating account..."', false);
});

test('profile settings expose status and accessible fields', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('profile.index'))
        ->assertOk()
        ->assertSeeText('Verified')
        ->assertSeeText('Admin Panel')
        ->assertSee('for="profile-email"', false)
        ->assertSee('aria-controls="tab-settings"', false);
});

test('admin dashboard links each pending content type to its own page', function () {
    $admin = User::factory()->admin()->create();
    PlaceSuggestion::factory()->create(['suggestion_status' => SuggestionStatus::Pending]);
    ServiceSuggestion::factory()->create(['suggestion_status' => SuggestionStatus::Pending]);
    Post::factory()->unpublished()->create(['submission_status' => SuggestionStatus::UnderReview]);
    ContactMessage::factory()->create(['read_at' => null, 'archived_at' => null]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee(route('admin.places.pending'), false)
        ->assertSee(route('admin.services.pending'), false)
        ->assertSee(route('admin.posts.pending'), false)
        ->assertSeeText('Pending Places')
        ->assertSeeText('Pending Services')
        ->assertSeeText('Pending Posts');
});

test('verification email uses branded responsive rtl markup', function () {
    app()->setLocale('fa');
    $user = User::factory()->unverified()->create(['name' => 'کاربر آزمایشی']);
    $message = (new SendEmailVerificationOtpNotification)->toMail($user);
    $html = $message->render()->toHtml();

    expect($message->subject)->toBe(__('auth.mail.verification_subject'))
        ->and($message->actionText)->toBe(__('auth.mail.verification_action'))
        ->and($html)->toContain('dir="rtl"')
        ->and($html)->toContain('#0f766e')
        ->and($html)->toContain('کاربر آزمایشی');
});
