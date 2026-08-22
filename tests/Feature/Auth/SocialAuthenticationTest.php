<?php

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

function configureSocialProvider(string $provider): void
{
    config([
        "services.{$provider}.client_id" => "{$provider}-client-id",
        "services.{$provider}.client_secret" => "{$provider}-client-secret",
        "services.{$provider}.redirect" => "http://localhost/auth/{$provider}/callback",
    ]);
}

function socialiteUser(
    string $id = 'provider-user-1',
    ?string $email = 'social@example.com',
    string $name = 'Social User',
    array $raw = ['email_verified' => true]
): SocialiteUser {
    $user = Mockery::mock(SocialiteUser::class);
    $user->shouldReceive('getId')->andReturn($id);
    $user->shouldReceive('getEmail')->andReturn($email);
    $user->shouldReceive('getName')->andReturn($name);
    $user->shouldReceive('getNickname')->andReturn(null);
    $user->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.jpg');
    $user->shouldReceive('getRaw')->andReturn($raw);

    return $user;
}

function mockSocialiteCallback(string $provider, SocialiteUser $user): void
{
    $driver = Mockery::mock(Provider::class);
    $driver->shouldReceive('user')->andReturn($user);

    Socialite::shouldReceive('driver')
        ->once()
        ->with($provider)
        ->andReturn($driver);
}

function mockSocialiteConnectCallback(string $provider, SocialiteUser $user): void
{
    $driver = Mockery::mock(Provider::class);
    $driver->shouldReceive('redirectUrl')
        ->once()
        ->with(route('social.connect.callback', $provider))
        ->andReturnSelf();
    $driver->shouldReceive('user')->andReturn($user);

    Socialite::shouldReceive('driver')
        ->once()
        ->with($provider)
        ->andReturn($driver);
}

test('login and registration screens show google and facebook sign in options', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('Sign in with Google')
        ->assertSee('Sign in with Facebook')
        ->assertSee(route('social.redirect', 'google'), false)
        ->assertSee(route('social.redirect', 'facebook'), false);

    $this->get('/register')
        ->assertOk()
        ->assertSee('Sign up with Google')
        ->assertSee('Sign up with Facebook');
});

test('social redirect sends user to configured provider', function () {
    configureSocialProvider('google');

    $driver = Mockery::mock(Provider::class);
    $driver->shouldReceive('redirect')->andReturn(new RedirectResponse('https://accounts.google.test/oauth'));

    Socialite::shouldReceive('driver')->once()->with('google')->andReturn($driver);

    $this->get(route('social.redirect', 'google'))
        ->assertRedirect('https://accounts.google.test/oauth');
});

test('social redirect reports missing provider configuration', function () {
    config([
        'services.google.client_id' => null,
        'services.google.client_secret' => null,
        'services.google.redirect' => null,
    ]);

    $this->get(route('social.redirect', 'google'))
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors(['social' => __('auth.social_not_configured')]);
});

test('google callback creates a user and social account', function () {
    configureSocialProvider('google');
    mockSocialiteCallback('google', socialiteUser(
        id: 'google-123',
        email: 'new-google@example.com',
        name: 'Google Person',
        raw: ['email_verified' => true],
    ));

    $this->get(route('social.callback', 'google'))
        ->assertRedirect('/')
        ->assertSessionHas('status', __('auth.login_success'));

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'email' => 'new-google@example.com',
        'name' => 'Google Person',
        'is_active' => true,
    ]);
    $this->assertDatabaseHas('social_accounts', [
        'provider' => 'google',
        'provider_user_id' => 'google-123',
        'provider_email' => 'new-google@example.com',
    ]);
});

test('social callback signs in an already linked account', function () {
    configureSocialProvider('facebook');
    $user = User::factory()->create(['email' => 'linked@example.com']);
    SocialAccount::factory()->create([
        'user_id' => $user->id,
        'provider' => 'facebook',
        'provider_user_id' => 'facebook-123',
        'provider_email' => 'linked@example.com',
    ]);
    mockSocialiteCallback('facebook', socialiteUser(
        id: 'facebook-123',
        email: 'updated-linked@example.com',
        raw: [],
    ));

    $this->get(route('social.callback', 'facebook'))
        ->assertRedirect('/');

    $this->assertAuthenticatedAs($user);
    $this->assertDatabaseHas('social_accounts', [
        'provider' => 'facebook',
        'provider_user_id' => 'facebook-123',
        'provider_email' => 'updated-linked@example.com',
    ]);
});

test('social callback prevents duplicate accounts with the same email', function () {
    configureSocialProvider('google');
    User::factory()->create(['email' => 'existing@example.com']);
    mockSocialiteCallback('google', socialiteUser(
        id: 'google-existing',
        email: 'existing@example.com',
    ));

    $this->get(route('social.callback', 'google'))
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors(['social' => __('auth.account_exists')]);

    $this->assertGuest();
    $this->assertDatabaseMissing('social_accounts', [
        'provider' => 'google',
        'provider_user_id' => 'google-existing',
    ]);
});

test('facebook callback without an email fails safely', function () {
    configureSocialProvider('facebook');
    mockSocialiteCallback('facebook', socialiteUser(
        id: 'facebook-missing-email',
        email: null,
        raw: [],
    ));

    $this->get(route('social.callback', 'facebook'))
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors(['social' => __('auth.social_missing_email')]);

    $this->assertGuest();
});

test('social callback handles provider denial', function () {
    configureSocialProvider('google');

    $this->get(route('social.callback', ['provider' => 'google', 'error' => 'access_denied']))
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors(['social' => __('auth.social_failed')]);
});

test('authenticated user can safely link a matching social account', function () {
    configureSocialProvider('google');
    $user = User::factory()->create([
        'email' => 'connect@example.com',
        'password' => Hash::make('password'),
    ]);
    mockSocialiteConnectCallback('google', socialiteUser(
        id: 'google-connect',
        email: 'connect@example.com',
    ));

    $this->actingAs($user)
        ->get(route('social.connect.callback', 'google'))
        ->assertRedirect(route('profile.index'))
        ->assertSessionHas('status', __('auth.social_linked'));

    $this->assertDatabaseHas('social_accounts', [
        'user_id' => $user->id,
        'provider' => 'google',
        'provider_user_id' => 'google-connect',
    ]);
});

test('authenticated user cannot link a social account with a different email', function () {
    configureSocialProvider('google');
    $user = User::factory()->create(['email' => 'owner@example.com']);
    mockSocialiteConnectCallback('google', socialiteUser(
        id: 'google-mismatch',
        email: 'other@example.com',
    ));

    $this->actingAs($user)
        ->get(route('social.connect.callback', 'google'))
        ->assertRedirect(route('profile.index'))
        ->assertSessionHasErrors(['social' => __('auth.social_email_mismatch')]);

    $this->assertDatabaseMissing('social_accounts', [
        'provider' => 'google',
        'provider_user_id' => 'google-mismatch',
    ]);
});

test('social-only users cannot disconnect their only login method', function () {
    $user = User::factory()->create(['password_set_at' => null]);
    $account = SocialAccount::factory()->create([
        'user_id' => $user->id,
        'provider' => 'google',
    ]);

    $this->actingAs($user)
        ->delete(route('social.disconnect', 'google'))
        ->assertRedirect(route('profile.index'))
        ->assertSessionHasErrors(['social' => __('auth.social_last_login_method')]);

    expect($account->fresh())->not->toBeNull();
});

test('users with a password can disconnect a social account', function () {
    $user = User::factory()->create(['password_set_at' => now()]);
    $account = SocialAccount::factory()->create([
        'user_id' => $user->id,
        'provider' => 'facebook',
    ]);

    $this->actingAs($user)
        ->delete(route('social.disconnect', 'facebook'))
        ->assertRedirect(route('profile.index'))
        ->assertSessionHas('status', __('auth.social_unlinked'));

    $this->assertDatabaseMissing('social_accounts', ['id' => $account->id]);
});
