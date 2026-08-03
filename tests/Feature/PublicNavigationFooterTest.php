<?php

use App\Models\User;

test('public layout renders accessible navigation controls', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('aria-label="Primary navigation"', false)
        ->assertSee('class="mk-nav-dropdown-trigger', false)
        ->assertSee('aria-controls="mk-discover-menu"', false)
        ->assertSee('id="mk-hamburger"', false)
        ->assertSee('aria-expanded="false"', false)
        ->assertSee('aria-controls="mk-mobile"', false)
        ->assertSee('id="mk-mobile-search-input"', false);
});

test('footer uses real internal links instead of placeholders', function () {
    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertSee(route('privacy', absolute: false), false)
        ->assertSee(route('terms', absolute: false), false)
        ->assertSee(route('contact', absolute: false), false)
        ->assertDontSee('href="#"', false)
        ->assertDontSee('onsubmit="return false;"', false);
});

test('privacy and terms pages render from footer links', function () {
    $this->get('/privacy-policy')
        ->assertOk()
        ->assertSee('Privacy Policy')
        ->assertSee('Contact Makanyab');

    $this->get('/terms-of-service')
        ->assertOk()
        ->assertSee('Terms of Service')
        ->assertSee('Contact Support');
});

test('authenticated navbar renders user menu as an accessible button', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/')
        ->assertOk()
        ->assertSee('id="mk-user-trigger"', false)
        ->assertSee('aria-controls="mk-user-dropdown"', false)
        ->assertSee('aria-haspopup="true"', false);
});
