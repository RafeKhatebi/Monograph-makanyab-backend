<?php

namespace App\Services;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use RuntimeException;

class SocialAuthenticationService
{
    public const PROVIDERS = ['google', 'facebook'];

    public function findOrCreateUser(string $provider, SocialiteUser $socialUser): User
    {
        $this->ensureSupportedProvider($provider);

        return DB::transaction(function () use ($provider, $socialUser): User {
            $account = SocialAccount::query()
                ->where('provider', $provider)
                ->where('provider_user_id', $socialUser->getId())
                ->first();

            if ($account) {
                $this->refreshAccount($account, $socialUser);

                return $account->user;
            }

            $email = $this->normalizedEmail($socialUser);

            if (! $email) {
                throw new RuntimeException(__('auth.social_missing_email'));
            }

            if (User::query()->where('email', $email)->exists()) {
                throw new RuntimeException(__('auth.account_exists'));
            }

            $user = User::create([
                'name' => $socialUser->getName() ?: $socialUser->getNickname() ?: Str::before($email, '@'),
                'username' => $this->uniqueUsername($socialUser->getNickname() ?: Str::before($email, '@')),
                'email' => $email,
                'email_verified_at' => $this->providerVerifiedEmail($provider, $socialUser) ? now() : null,
                'password' => Str::password(48),
                'password_set_at' => null,
                'profile_picture' => $socialUser->getAvatar(),
                'role' => 'user',
                'is_active' => true,
            ]);

            $this->createAccount($user, $provider, $socialUser);

            return $user;
        });
    }

    public function linkToUser(User $user, string $provider, SocialiteUser $socialUser): SocialAccount
    {
        $this->ensureSupportedProvider($provider);

        return DB::transaction(function () use ($user, $provider, $socialUser): SocialAccount {
            $existingAccount = SocialAccount::query()
                ->where('provider', $provider)
                ->where('provider_user_id', $socialUser->getId())
                ->first();

            if ($existingAccount && ! $existingAccount->user->is($user)) {
                throw new RuntimeException(__('auth.social_account_linked'));
            }

            $email = $this->normalizedEmail($socialUser);

            if ($email && Str::lower($user->email) !== $email) {
                throw new RuntimeException(__('auth.social_email_mismatch'));
            }

            return $existingAccount
                ? tap($existingAccount, fn (SocialAccount $account) => $this->refreshAccount($account, $socialUser))
                : $this->createAccount($user, $provider, $socialUser);
        });
    }

    public function ensureSupportedProvider(string $provider): void
    {
        if (! in_array($provider, self::PROVIDERS, true)) {
            throw new RuntimeException(__('auth.social_provider_unsupported'));
        }
    }

    private function createAccount(User $user, string $provider, SocialiteUser $socialUser): SocialAccount
    {
        return $user->socialAccounts()->create([
            'provider' => $provider,
            'provider_user_id' => $socialUser->getId(),
            'provider_email' => $this->normalizedEmail($socialUser),
            'avatar_url' => $socialUser->getAvatar(),
        ]);
    }

    private function refreshAccount(SocialAccount $account, SocialiteUser $socialUser): void
    {
        $account->update([
            'provider_email' => $this->normalizedEmail($socialUser),
            'avatar_url' => $socialUser->getAvatar(),
        ]);
    }

    private function normalizedEmail(SocialiteUser $socialUser): ?string
    {
        $email = $socialUser->getEmail();

        return $email ? Str::lower($email) : null;
    }

    private function providerVerifiedEmail(string $provider, SocialiteUser $socialUser): bool
    {
        $raw = method_exists($socialUser, 'getRaw') ? $socialUser->getRaw() : [];

        return $provider === 'google'
            && array_key_exists('email_verified', $raw)
            && filter_var($raw['email_verified'], FILTER_VALIDATE_BOOL);
    }

    private function uniqueUsername(string $base): string
    {
        $base = Str::of($base)
            ->lower()
            ->replaceMatches('/[^a-z0-9_]+/', '_')
            ->trim('_')
            ->limit(32, '')
            ->value() ?: 'user';

        $username = $base;
        $counter = 1;

        while (User::query()->where('username', $username)->exists()) {
            $username = Str::limit($base, 24, '').'_'.$counter;
            $counter++;
        }

        return $username;
    }
}
